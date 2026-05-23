<?php

namespace App\Http\Controllers;

use App\Models\JobNum;
use App\Models\LogMachine;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WebSocket\Client;
class JobNumController extends Controller
{
    protected $JobNum;
    protected $LogMachine;
    public function __construct(JobNum $jobNum, LogMachine $LogMachine)
    {
        $this->JobNum = $jobNum;
        $this->LogMachine = $LogMachine;
    }
    // public function get_all(Request $request)
    // {
    //     $date = $request->production_date;
    //     $category = $request->category;
    //     $shift = (int) $request->shift;
    //     if ($shift == 6) {
    //         $shift = 2;
    //     }
    //     $data_shift = 'SHIFT ' . $shift;
    //     $baseDate = Carbon::parse($date);
    //     $start_date = $baseDate->copy()->subDays(10)->format('Y-m-d');
    //     $finish_date = $baseDate->copy()->addDays(2)->format('Y-m-d');
    //     if ($category == 'ASSY') {
    //         $category = 'ASY';
    //     }
    //     $data = $this->JobNum->get_all(
    //         $category,
    //         $data_shift,
    //         $start_date,
    //         $finish_date
    //     );
    //     return response()->json([
    //         'results' => $data->map(function ($item) {
    //             return [
    //                 'id' => $item->JobNum,
    //                 'text' => $item->JobNum . ' - ' . $item->ProdCode
    //             ];
    //         })
    //     ]);
    // }
    public function get_all(Request $request)
    {
        $production_date = now('Asia/Jakarta')->format('y-m-d');
        $id = 'SAI00001';
        $category_dept = $this->JobNum->category_dept($id);
        $data_shift = $this->JobNum->get_shift($production_date, $category_dept);
        $baseDate = Carbon::parse($production_date);
        $start_date = $baseDate->copy()->subDays(10)->format('Y-m-d');
        $finish_date = $baseDate->copy()->addDays(2)->format('Y-m-d');
        // if ($category == 'ASSY') {
        //     $category = 'ASY';
        // }
        $data = $this->JobNum->get_all(
            // $category,
            $data_shift,
            $start_date,
            $finish_date
        );
        return response()->json([
            'results' => $data->map(function ($item) {
                return [
                    'id' => $item->JobNum,
                    'text' => $item->JobNum . ' - ' . $item->ProdCode
                ];
            })
        ]);
    }
    public function get_customer(Request $request)
    {
        return response()->json($this->JobNum->get_customer($request->JobNum));
    }
    public function list_setup_machine(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $jobNum = $request->input('job_num');
        $shift = $request->input('shift');
        $response = Http::withoutVerifying()
            ->post('https://192.168.1.251:8000/EPIAPI/JobEntry/GetDetailJob', [
                'ipJobNum' => $jobNum
            ]);
        $responseData = $response->json();
        if (!isset($responseData['data'])) {
            return response()->json([
                'message' => 'Data tidak ditemukan dalam response dari API JobEntry.',
                'raw_response' => $responseData
            ], 500);
        }
        $jobOpDtl = $responseData['data']['jobOpDtl'] ?? [];
        $machineIds = [];
        foreach ($jobOpDtl as $detail) {
            if (!empty($detail['resourceID'])) {
                $machineIds[] = $detail['resourceID'];
            }
        }
        $machineData = LogMachine::whereIn('machine_id', $machineIds)->get();
        return response()->json(array_merge(
            $responseData,
            [
                'machine' => $machineData
            ]
        ));
    }
    public function start_machine_std(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql_ = "'" . date('Y-m-d') . "'";
        $date_time_sql = date('Y-m-d H:i:s');
        $job_num = $request->job_num;
        $shift = $request->shift;
        $avail_time = $request->avail_time;
        $standard_sph = $request->standard_sph;
        $production_date = $request->production_date;
        $machineId = $request->machine_id;
        $employeeId = $request->employee_id;
        $category = $request->category_id;
        if (!is_array($machineId) || empty($machineId)) {
            return response()->json([
                'code' => 400,
                'status' => 'Failed',
                'message' => 'machine_id harus berupa array dan tidak boleh kosong'
            ]);
        }
        if (!is_array($employeeId) || empty($employeeId)) {
            return response()->json([
                'code' => 400,
                'status' => 'Failed',
                'message' => 'employee_id harus berupa array dan tidak boleh kosong'
            ]);
        }
        $db_job_num = DB::table("f_select_schedule_epicor('$job_num')")
            ->select('qty_plan', 'shift', 'item_no')
            ->get();
        if ($db_job_num->isEmpty()) {
            return response()->json(
                [
                    'machine_id' => $machineId,
                    'job_num' => $job_num,
                    'status' => false,
                    'message' => 'Data machine tidak ditemukan atau OFF',
                ],
                404,
            );
        }
        foreach ($db_job_num as $row) {
            $qty_plan = $row->qty_plan;
            $part_no = $row->item_no;
            $qty_actual = 0;
        }
        foreach ($machineId as $i => $row) {
            $empId = $employeeId[$i] ?? null;
            $standardSph = $standard_sph[$i] ?? null;
            $employeeCode = null;
            $employeeName = null;
            if ($empId && strpos($empId, '-') !== false) {
                list($employeeCode, $employeeName) = explode('-', $empId, 2);
                $employeeCode = trim($employeeCode);
                $employeeName = trim($employeeName);
            } else {
                $employeeCode = $empId;
                $employeeName = '';
            }
            $line = LogMachine::find($row);
            $lineCheck = $line->category_line_id ?? '-';
            $revisionModel = $this->LogMachine->revisionModel($job_num);
            if ($revisionModel && isset($revisionModel->RevisionNum)) {
                $parts = explode('-', $revisionModel->RevisionNum);
                $revisionNum = end($parts);
            }
            $nextJob = $this->LogMachine->nextJob($row, $job_num);
            if ($nextJob) {
                $dataMachine = [
                    'started_at' => $date_time_sql,
                    'finished_at' => null,
                    'average_ct' => 0,
                    'operation_time' => 0,
                    'current_gsph' => 0,
                    'production_date' => $production_date,
                    'standard_sph' => $standardSph,
                    'job_num' => $job_num,
                    'part_no' => $part_no ?? null,
                    'qty_plan' => $qty_plan ?? 0,
                    'qty_actual' => $nextJob->qty_actual,
                    'condition_id' => 1,
                    'shift' => $shift,
                    'break_12' => 0,
                    'break_18' => 0,
                    'break_02' => 0,
                    'customer' => $request->customer,
                    'employee_id' => $employeeCode,
                    'employee_name' => $employeeName,
                    'status_finish' => 0
                ];
                if ($lineCheck === 'ASSY-002') {
                    $dataMachine['model'] = $revisionNum;
                }
                $this->LogMachine->updateMachine($row, $dataMachine);
            } else {
                $dataMachine = [
                    'started_at' => $date_time_sql,
                    'finished_at' => null,
                    'average_ct' => 0,
                    'operation_time' => 0,
                    'current_gsph' => 0,
                    'production_date' => $production_date,
                    'standard_sph' => $standardSph,
                    'job_num' => $job_num,
                    'part_no' => $part_no ?? null,
                    'qty_plan' => $qty_plan,
                    'qty_actual' => 0,
                    'condition_id' => 1,
                    'shift' => $shift,
                    'break_12' => 0,
                    'break_18' => 0,
                    'break_02' => 0,
                    'customer' => $request->customer,
                    'employee_id' => $employeeCode,
                    'employee_name' => $employeeName,
                    'status_finish' => 0
                ];
                if ($lineCheck === 'ASSY-002') {
                    $dataMachine['model'] = $revisionNum;
                }
                $this->LogMachine->updateMachine($row, $dataMachine);
            }
            $dataGsph = [
                'machine_id' => $row,
                'gsph' => 0,
                'cut_off' => date('Y-m-d'),
                'cut_off_time' => date('Y-m-d H:i:s'),
                'tool_id' => null,
                'job_num' => $job_num,
                'shift' => $shift,
                'qty_actual' => 0
            ];
            $this->LogMachine->insertGsphRecord($dataGsph);
        }
        $db_update_summary = $this->LogMachine->updateSummary($machineId, $shift, $job_num, $production_date, $qty_plan);
        $result_oee = $this->LogMachine->resultOee($machineId, $job_num, $shift, $production_date);
        $existingMachines = LogMachine::whereIn('machine_id', $machineId)
            ->where('is_active', 1)
            ->pluck('machine_id')
            ->toArray();
        foreach ($existingMachines as $i => $row) {
            $empId = $employeeId[$i] ?? null;
            $standardSph = $standard_sph[$i] ?? null;
            $employeeCode = null;
            $employeeName = null;
            if ($empId && strpos($empId, '-') !== false) {
                list($employeeCode, $employeeName) = explode('-', $empId, 2);
                $employeeCode = trim($employeeCode);
                $employeeName = trim($employeeName);
            } else {
                $employeeCode = $empId;
                $employeeName = '';
            }
            $line = LogMachine::find($row);
            $lineCheck = $line->category_line_id;
            $revisionModel = $this->LogMachine->revisionModel($job_num);
            if ($revisionModel && isset($revisionModel->RevisionNum)) {
                $parts = explode('-', $revisionModel->RevisionNum);
                $revisionNum = end($parts);
            }
            $updateData = [
                'started_at' => $date_time_sql,
                'finished_at' => null,
                'average_ct' => 0,
                'operation_time' => 0,
                'current_gsph' => 0,
                'production_date' => $production_date,
                'standard_sph' => $standardSph,
                'job_num' => $job_num,
                'part_no' => $part_no,
                'qty_plan' => $qty_plan,
                // 'qty_actual' => 0,
                'condition_id' => 1,
                'shift' => $shift,
                'break_12' => 0,
                'break_18' => 0,
                'break_02' => 0,
                'customer' => $request->customer,
                'employee_id' => $employeeCode,
                'employee_name' => $employeeName,
                'status_finish' => 0
            ];
            if ($lineCheck === 'ASSY-002') {
                $updateData['model'] = $revisionNum;
            }
            $update = LogMachine::where('machine_id', $row)
                ->where('is_active', 1)
                ->update($updateData);
        }
        if ($update) {
            if ($db_update_summary) {
                DB::table('log_header_machine_summary')
                    ->whereIn('machine_id', $machineId)
                    ->update(['is_active' => 0]);
                foreach ($machineId as $i => $row) {
                    $standardSph = $standard_sph[$i] ?? null;
                    $this->LogMachine->updateLogheaderSummary($row, $shift, $job_num, $production_date, $qty_plan, $qty_actual, $standardSph);
                }
                $summary_id = $this->LogMachine->getSummaryId($machineId, $shift, $job_num, $production_date, $qty_plan);
            } else {
                foreach ($machineId as $i => $machine) {
                    $standardSph = $standard_sph[$i] ?? null;
                    $this->LogMachine->insertHeaderSummary($machine, $shift, $job_num, $production_date, $qty_plan, $qty_actual, $standardSph);
                }
                $summary_id = $this->LogMachine->getSummaryId($machineId, $shift, $job_num, $production_date, $qty_plan);
            }
            foreach ($summary_id as $row) {
                LogMachine::where('machine_id', $row->machine_id)
                    ->where('is_active', 1)
                    ->update([
                        'summary_id' => $row->seq_id
                    ]);
            }
            $db_oee = $this->LogMachine->countDbOee($machineId, $job_num, $shift, $production_date);
            if ($result_oee->isNotEmpty()) {
                $operation_time = $result_oee[0]->operation_time;
                $downtime = $result_oee[0]->downtime;
                $qty_actual = $result_oee[0]->qty_actual;
                $qty_ng = $result_oee[0]->qty_ng;
            } else {
                $operation_time = 0;
                $downtime = 0;
                $qty_actual = 0;
                $qty_ng = 0;
            }

            if ($db_oee > 0) {
                $this->LogMachine->updateOee($machineId, $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
            } else {
                foreach ($machineId as $raw) {
                    $this->LogMachine->insertOee($raw, $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
                }
            }
            $dataMachine = LogMachine::whereIn('machine_id', $machineId)
                ->where('is_active', 1)
                ->get();
            if ($job_num != $dataMachine[0]->job_num || empty($dataMachine[0]->started_at)) {
                $started_at = $date_time_sql;
                $update_header['started_at'] = $date_time_sql;
            } else {
                $started_at = $dataMachine[0]->started_at;
            }
            $finished_at = $date_time_sql;
            $mulaiDateTime = new DateTime($started_at);
            $selesaiDateTime = new DateTime($finished_at);
            $selisihInterval = $mulaiDateTime->diff($selesaiDateTime);
            $operation_time = ($selisihInterval->days * 24 * 60 * 60 + $selisihInterval->h * 60 * 60 + $selisihInterval->i * 60 + $selisihInterval->s) / 3600;
            $category = $dataMachine[0]->category_line_id;
            $no_downtime = 0;
            $data_chart_downtime = '';

            if (Str::startsWith($category, 'assy')) {
                foreach ($machineId as $row) {
                    $db_downtime_record = DB::table("f_downtime_logs('$row')")
                        ->select('*')
                        ->get();

                    foreach ($db_downtime_record as $drow) {
                        $data_chart_downtime .= (int) $drow->downtime;
                        $data_chart_downtime .= ($no_downtime == 4 ? '' : ', ');
                        $no_downtime++;
                    }
                }
            } else {
                foreach ($machineId as $row) {
                    $db_downtime_record = DB::table("f_downtime_logs_stp('$row')")
                        ->select('*')
                        ->get();

                    foreach ($db_downtime_record as $drow) {
                        $data_chart_downtime .= (int) $drow->downtime;
                        $data_chart_downtime .= ($no_downtime == 4 ? '' : ', ');
                        $no_downtime++;
                    }
                }
            }

            $dataShift = $dataMachine[0]->shift == '' ? '0' : $dataMachine[0]->shift;
            $data_chart_gsph = '';
            $no_gsph = 0;
            foreach ($machineId as $row) {
                $db_gsph_record = DB::table("f_stroke_hour_logs_shift_2('$row', $date_sql_)")
                    ->select('*')
                    ->get();

                if ($db_gsph_record->isNotEmpty()) {
                    $data_chart_gsph .= (int) $db_gsph_record->first()->gsph . ($no_gsph == 10 ? '' : ', ');
                } else {
                    $data_chart_gsph .= '0' . ($no_gsph == 10 ? '' : ', ');
                }
                $no_gsph++;
            }

            $ct_log_detail = [];
            $no = 1;

            $db_log_detail = $this->LogMachine->logDetailMachine($job_num, $dataShift, $machineId);
            foreach ($db_log_detail as $row) {
                $cycle_time = $row->cycle_time == '' ? 0 : str_replace(',', '', number_format($row->cycle_time * 60, 0));
                $ct_log_detail[] = $cycle_time;
                if ($no == 25) {
                    break;
                }
                $no++;
            }
            $ct_log_detail = array_reverse($ct_log_detail);
            $ct_log_detail = implode(',', $ct_log_detail);
            $dataNew = LogMachine::whereIn('machine_id', $machineId)->where('is_active', 1)->get();

            $db_oee_machine = $this->LogMachine->getLogOee($machineId, $dataShift, $production_date);
            if ($db_oee_machine->count() > 0) {
                foreach ($db_oee_machine as $b) {
                    $oee_quality = $b->total_qty > 0 ? floor($b->total_ng / $b->total_qty) * 100 : 0;
                    $oee_availability = $b->available_time > 0 ? ceil(($b->operation_time / $b->available_time) * 100) : 0;
                }
            } else {
                $oee_quality = 0;
                $oee_availability = 0;
            }
            foreach ($dataNew as $row) {
                $machineData['operation_time'] = $started_at . ' ' . $finished_at . ' ' . ($selisihInterval->days * 24 * 60 * 60 + $selisihInterval->h * 60 * 60 + $selisihInterval->i * 60 + $selisihInterval->s) / 3600;
                $machineData['qty_actual'] = number_format($row->qty_actual, 0);
                $machineData['dresser_count'] = number_format($row->dresser_count, 0);
                $machineData['spot_count'] = number_format($row->spot_count, 0);
                $machineData['current_gsph'] = number_format($row->current_gsph, 0);
                $machineData['current_gsph_persen'] = $row->current_gsph > 0 && $row->standard_sph > 0 ? ceil(($row->current_gsph / $row->standard_sph) * 100) : 0;
                $machineData['condition_id'] = $row->condition_id;
                $machineData['job_num'] = $row->job_num;
                $machineData['qty_plan'] = number_format($row->qty_plan, 0);
                $machineData['part_no'] = $row->part_no;
                $machineData['data_chart_gsph'] = $data_chart_gsph;
                $machineData['data_chart_downtime'] = $data_chart_downtime;
                $machineData['ct_log_detail'] = $ct_log_detail;
                $machineData['average_ct'] = $row->average_ct > 0 ? number_format($row->average_ct * 60, 0) : 0;
                $machineData['bar_progress'] = $row->qty_plan > 0 ? number_format(($row->qty_actual / $row->qty_plan) * 100, 0) : 0;
                $machineData['oee_quality'] = $oee_quality;
                $machineData['oee_availability'] = $oee_availability;
                $machineData['oee_performance'] = $row->current_gsph > 0 && $row->standard_sph > 0 ? ceil($row->current_gsph / $row->standard_sph) : 0;
                $machineData['machine_id'] = $row->machine_id;
                $machineData['emp_name'] = $row->employee_name;
            }
            $client = new Client("ws://127.0.0.1:8080");
            $data = [
                'action' => 'trigger',
                'channel' => 'machine',
                'event' => 'start-machine',
                'data' => [
                    'message' => $machineData
                ]
            ];
            $client->send(json_encode($data));
            $client->close();
            return response()->json(
                [
                    'machine_id' => $machineId,
                    'job_num' => $job_num,
                    'customer' => $request->customer,
                    'employee' => $employeeId,
                    'next' => $nextJob,
                    'status' => true,
                    'message' => 'Berhasil update',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'machine_id' => $machineId,
                    'job_num' => $job_num,
                    'customer' => $request->customer,
                    'employee' => $employeeId,
                    'status' => false,
                    'message' => 'Gagal update',
                ],
                404,
            );
        }
    }
    public function start_machine_tool(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machine_id = $request->machineID;
        $production_date = $request->production_date;
        $shift = $request->shift;
        $tool_ids = $request->toolID;
        $job_nums = $request->jobNumber;
        $standard_sphs = $request->standardSPH;
        $work_time = $request->workTime;
        $empId = $request->employeeID;
        $employeeCode = $employeeName = null;
        if ($empId && strpos($empId, '-') !== false) {
            list($employeeCode, $employeeName) = explode('-', $empId, 2);
            $employeeCode = trim($employeeCode);
            $employeeName = trim($employeeName);
        } else {
            $employeeCode = trim($empId);
        }
        foreach ($tool_ids as $index => $tool_id) {
            $fullJob = $job_nums[$index];
            $job_parts = explode('_', $fullJob);

            if (count($job_parts) < 2)
                continue;

            $job_num = $job_parts[0];
            $prod_code = $job_parts[1];
            $revisionModel = $this->LogMachine->revisionModel($job_num);
            $revisionNum = null;
            if ($revisionModel && isset($revisionModel->RevisionNum)) {
                $parts = explode('-', $revisionModel->RevisionNum);
                $revisionNum = end($parts);
            }
            $standard_sph = $standard_sphs[$index] ?? null;
            $schedule = DB::table("f_select_schedule_epicor('$job_num')")
                ->select('qty_plan', 'shift', 'item_no')
                ->first();

            if (!$schedule)
                continue;
            DB::table('log_machine_tool')
                ->where('machine_id', $machine_id)
                ->where('tool_id', $tool_id)
                ->update([
                    'job_num' => $job_num,
                    'production_date' => $date,
                    'shift' => $shift,
                    'qty_plan' => $schedule->qty_plan,
                    'qty_actual' => 0,
                    'qty_ng' => 0,
                    'standard_sph' => $standard_sph,
                    'customer' => $prod_code,
                    'employee_id' => $employeeCode,
                    'employee_name' => $employeeName,
                    'started_at' => $time,
                    'finished_at' => null,
                    'average_ct' => 0,
                    'is_active' => true,
                    'condition_id' => 1,
                    'part_no' => $schedule->item_no,
                    'status_finish' => false,
                    'model' => $revisionNum
                ]);
            DB::table('log_header_machine_summary')->insert([
                'machine_id' => $machine_id,
                'job_num' => $job_num,
                'production_date' => $date,
                'shift' => $shift,
                'standard_sph' => $standard_sph,
                'qty_plan' => $schedule->qty_plan,
                'qty_actual' => 0,
                'started_at' => $time,
                'average_ct' => 0,
                'is_active' => true,
                'tool_id' => $tool_id
            ]);
            $summary = $this->LogMachine->getSummaryIdTool($machine_id, $job_num, $date, $shift, $tool_id);

            if ($summary) {
                $this->LogMachine->updateMachineTool($machine_id, $tool_id, $summary->seq_id);
            }
            $oeeLog = $this->LogMachine->logOeeTool($machine_id, $job_num, $tool_id, $date, $shift);
            $aggregate = $this->LogMachine->aggregateSummaryTool($machine_id, $job_num, $tool_id, $date, $shift);
            $oeeData = [
                'operation_time' => $aggregate->operation_time ?? 0,
                'total_qty' => $aggregate->qty_actual ?? 0,
                'total_ng' => $aggregate->qty_ng ?? 0,
                'downtime' => $aggregate->downtime ?? 0,
                'available_time' => $work_time,
                'production_date' => $date
            ];

            if ($oeeLog) {
                DB::table('oee_log_machine')
                    ->where('machine_id', $machine_id)
                    ->where('job_num', $job_num)
                    ->where('tool_id', $tool_id)
                    ->where('shift', $shift)
                    ->where('production_date', $date)
                    ->update($oeeData);
            } else {
                DB::table('oee_log_machine')
                    ->insert(array_merge($oeeData, [
                        'machine_id' => $machine_id,
                        'job_num' => $job_num,
                        'tool_id' => $tool_id,
                        'shift' => $shift
                    ]));
            }
            $this->LogMachine->insertGsphRecordTool($machine_id, $date, $time, $tool_id, $job_num, $shift);
            $machineData = DB::table('log_machine_tool')
                ->where('machine_id', $machine_id)
                ->where('tool_id', $tool_id)
                ->first();
            // dd($machineData);
            $message = [
                'machine_id' => $machineData->machine_id,
                'job_num' => $machineData->job_num,
                'qty_plan' => $machineData->qty_plan,
                'qty_actual' => $machineData->qty_actual,
                'part_no' => $machineData->part_no,
                'production_date' => $machineData->production_date,
                'standard_sph' => $machineData->standard_sph,
                'emp_name' => $machineData->employee_name
            ];
            $client = new Client("ws://127.0.0.1:8080");
            $data = [
                'action' => 'trigger',
                'channel' => 'machine',
                'event' => 'start-machine-tool',
                'data' => [
                    'message' => $message
                ]
            ];
            $client->send(json_encode($data));
            $client->close();
        }
        return response()->json([
            'message' => 'Berhasil menyalakan mesin untuk semua tool',
            'status' => true,
            'machine_id' => $machine_id
        ]);
    }
}
