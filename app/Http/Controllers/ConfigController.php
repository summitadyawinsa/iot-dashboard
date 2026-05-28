<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use WebSocket\Client;
use Yajra\DataTables\DataTables;

class ConfigController extends Controller
{
    protected $config;
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    public function setup_before_confirm(Request $request)
    {
        $machines = $request->json()->all()['machine'] ?? [];
        // dd($machines);
        $production_date = $request->production_date;
        $customer = $request->customer;
        $job_num = $request->job_num;
        $shift = $request->shift;
        if (empty($machines) || !is_array($machines)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ], 422);
        }
        $db_job_num = DB::table("f_select_schedule_epicor('$job_num')")
            ->select('qty_plan', 'shift', 'item_no')
            ->get();
        if ($db_job_num->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ]);
        }
        if ($db_job_num->isEmpty()) {
            return response()->json(
                [
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
        DB::beginTransaction();
        try {
            foreach ($machines as $machine) {
                if (empty($machine['employee_id']) || $machine['employee_id'] == null || $machine['employee_id'] == '') {
                    continue;
                }
                if ($machine['machine_id'] == 'SSW-TG4R-2' || $machine['machine_id'] == 'SSW-TG4R-1' || $machine['machine_id'] == 'SSW-TG4R-3' || $machine['machine_id'] == 'SSW-TG4R-4' || $machine['machine_id'] == 'SSW-B-1' || $machine['machine_id'] == 'SSW-B-2' || $machine['machine_id'] == 'SSW-A-5' || $machine['machine_id'] == 'SSW-A-14' || $machine['machine_id'] == 'SSW-B-3' || $machine['machine_id'] == 'SSW-B-4' || $machine['machine_id'] == 'SSW-B-5' || $machine['machine_id'] == 'SSW-B-7' || $machine['machine_id'] == 'SSW-B-6' || $machine['machine_id'] == 'SSW-B-8' || $machine['machine_id'] == 'SSW-MA-3') {
                    $check_part = Http::withoutVerifying()->get(
                        config('services.api_factory.url') . 'setup-ssw/selector',
                        [
                            'machine_name' => $machine['machine_id'],
                            'part_number' => $part_no
                        ]
                    );
                    $response = $check_part->json();
                    if (!$check_part->successful() || ($response['status'] ?? '') !== 'success') {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Part number tidak terdaftar atau selector tidak ditemukan'
                        ], 422);
                    }
                }
                $emp = explode('~', $machine['employee_id']);
                $revisionModel = $this->config->revisionModel($job_num);
                if ($revisionModel && isset($revisionModel->RevisionNum)) {
                    $parts = explode('-', $revisionModel->RevisionNum);
                    $revisionNum = end($parts);
                }
                // $jo_lanjutan = $this->config->setup_jo_lanjutan($machine['machine_id'], $job_num);
                // if ($jo_lanjutan) {
                //     $qty_actual = $jo_lanjutan->qty_actual;
                // } else {
                //     $qty_actual = 0;
                // }
                $qty_actual = 0;
                $data_update = [
                    'job_num' => $job_num,
                    'standard_sph' => $machine['std_jph'],
                    'production_date' => $production_date,
                    'customer' => $customer,
                    'employee_id' => $emp[0],
                    'employee_name' => $emp[1],
                    'qty_plan' => $qty_plan,
                    'part_no' => $part_no,
                    'model' => $revisionNum ?? null,
                    'shift' => $shift,
                    'opr_seq' => $machine['opr_seq'] ?? 10
                ];
                $this->config->update_confirm($machine['machine_id'], $data_update);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Machine setup successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function setup(Request $request)
    {
        $machines = $request->json()->all()['machine'] ?? [];
        // dd($machines);
        $production_date = $request->production_date;
        $customer = $request->customer;
        $job_num = $request->job_num;
        $shift = $request->shift;
        if ($shift == 'SHIFT 2') {
            $shift = 6;
        }
        $shiftMap = [
            1 => 8,
            6 => 8.25,
            7 => 8.5,
            11 => 6.75,
            12 => 6.75,
            13 => 7,
        ];
        $avail_time = $shiftMap[$shift] ?? 8;
        $epicor_shift = DB::connection('sqlsrv4')
            ->table('Erp.JCShift')
            ->where('Shift', $shift)
            ->select('Description')
            ->first();
        $shift = $epicor_shift->Description;
        if (empty($machines) || !is_array($machines)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ], 422);
        }
        $db_job_num = DB::table("f_select_schedule_epicor('$job_num')")
            ->select('qty_plan', 'shift', 'item_no')
            ->get();
        if ($db_job_num->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ]);
        }
        if ($db_job_num->isEmpty()) {
            return response()->json(
                [
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
            // Http::withoutVerifying()->get(config('services.api_factory.url').'setup-ssw/selector?machine_name=SSW-B-7&part_number=73211C000P');
        }
        DB::beginTransaction();
        try {
            foreach ($machines as $machine) {
                if (empty($machine['employee_id']) || $machine['employee_id'] == null || $machine['employee_id'] == '') {
                    continue;
                }
                if ($machine['machine_id'] == 'SSW-TG4R-2' || $machine['machine_id'] == 'SSW-TG4R-4' || $machine['machine_id'] == 'SSW-B-3' || $machine['machine_id'] == 'SSW-B-4' || $machine['machine_id'] == 'SSW-B-5' || $machine['machine_id'] == 'SSW-B-7') {
                    $check_part = Http::withoutVerifying()->get(
                        config('services.api_factory.url') . 'setup-ssw/selector',
                        [
                            'machine_name' => $machine['machine_id'],
                            'part_number' => $part_no
                        ]
                    );
                    $response = $check_part->json();
                    if (!$check_part->successful() || ($response['status'] ?? '') !== 'success') {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Part number tidak terdaftar atau selector tidak ditemukan'
                        ], 422);
                    }
                }
                $emp = explode('~', $machine['employee_id']);
                $revisionModel = $this->config->revisionModel($job_num);
                if ($revisionModel && isset($revisionModel->RevisionNum)) {
                    $parts = explode('-', $revisionModel->RevisionNum);
                    $revisionNum = end($parts);
                }
                // $jo_lanjutan = $this->config->setup_jo_lanjutan($machine['machine_id'], $job_num);
                // if ($jo_lanjutan) {
                //     $qty_actual = $jo_lanjutan->qty_actual;
                // } else {
                //     $qty_actual = 0;
                // }
                $qty_actual = 0;
                $data_update = [
                    'started_at' => date('Y-m-d H:i:s'),
                    'job_num' => $job_num,
                    'standard_sph' => $machine['std_jph'],
                    'production_date' => $production_date,
                    'customer' => $customer,
                    'condition_id' => 1,
                    'is_active' => 1,
                    'employee_id' => $emp[0],
                    'employee_name' => $emp[1],
                    'qty_plan' => $qty_plan,
                    'part_no' => $part_no,
                    'model' => $revisionNum ?? null,
                    'status_finish' => 0,
                    'shift' => $shift,
                    'break_12' => 0,
                    'break_18' => 0,
                    'break_02' => 0,
                    'finished_at' => null,
                    'average_ct' => 0,
                    'operation_time' => 0,
                    'current_gsph' => 0,
                    'qty_actual' => $qty_actual
                ];
                // $this->config->update_confirm($machine['machine_id'], $data_update);
                $this->config->update_setup($machine['machine_id'], $data_update);
                $dataGsph = [
                    'machine_id' => $machine['machine_id'],
                    'gsph' => 0,
                    'cut_off' => date('Y-m-d'),
                    'cut_off_time' => date('Y-m-d H:i:s'),
                    'tool_id' => null,
                    'job_num' => $job_num,
                    'shift' => $shift,
                    'qty_actual' => $qty_actual
                ];
                $this->config->insertGsphRecord($dataGsph);
                $this->config->updateSummary($machine['machine_id'], $shift, $job_num, $production_date, $qty_plan);
                $result_oee = $this->config->resultOee($machine['machine_id'], $job_num, $shift, $production_date);
                $db_oee = $this->config->countDbOee($machine['machine_id'], $job_num, $shift, $production_date);
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
                    $this->config->updateOee($machine['machine_id'], $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
                } else {
                    $this->config->insertOee($machine['machine_id'], $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
                }
                Http::withoutVerifying()->post(
                    config('services.api_factory.url') . 'machine-status/update',
                    [
                        "machine_id" => $machine['machine_id'],
                        "condition_id" => 1,
                        "job_num" => $job_num
                    ]
                );
                $message = [
                    'machine_id' => $machine['machine_id'],
                    'job_num' => $job_num,
                    'qty_plan' => $qty_plan,
                    'qty_actual' => $qty_actual,
                    'part_no' => $part_no,
                    'production_date' => $production_date,
                    'standard_sph' => $machine['std_jph'],
                    'emp_name' => $emp[1]
                ];
                $client = new Client("ws://127.0.0.1:8080");
                $data = [
                    'action' => 'trigger',
                    'channel' => 'machine',
                    'event' => 'start-machine',
                    'data' => [
                        'message' => $message
                    ]
                ];
                $client->send(json_encode($data));
                $client->close();
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Machine setup successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function spesial_start(Request $request)
    {
        // dd($request->all());
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machine_id = $request->machine_id;
        $employee = $request->employee;
        $shift = $request->shift;
        $tool_ids = $request->tool_id;
        $job_nums = $request->job_number;
        $standard_sphs = $request->standard_sph;
        $shiftMap = [
            1 => 8,
            6 => 8.25,
            7 => 8.5,
            11 => 6.75,
            12 => 6.75,
            13 => 7,
        ];
        $work_time = $shiftMap[$shift] ?? 8;
        $emp = explode('~', $employee);
        $employeeCode = $emp[0];
        $employeeName = $emp[1];
        // $employeeCode = $employeeName = null;
        // if ($empId && strpos($empId, '-') !== false) {
        //     list($employeeCode, $employeeName) = explode('-', $empId, 2);
        //     $employeeCode = trim($employeeCode);
        //     $employeeName = trim($employeeName);
        // } else {
        //     $employeeCode = trim($empId);
        // }
        $epicor_shift = DB::connection('sqlsrv4')
            ->table('Erp.JCShift')
            ->where('Shift', $shift)
            ->select('Description')
            ->first();
        $shift = $epicor_shift->Description;
        foreach ($tool_ids as $index => $tool_id) {
            $fullJob = $job_nums[$index];
            $job_parts = explode('_', $fullJob);

            if (count($job_parts) < 2)
                continue;

            $job_num = $job_parts[0];
            $prod_code = $job_parts[1];
            $revisionModel = $this->config->revisionModel($job_num);
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
            $summary = $this->config->getSummaryIdTool($machine_id, $job_num, $date, $shift, $tool_id);

            if ($summary) {
                $this->config->updateMachineTool($machine_id, $tool_id, $summary->seq_id);
            }
            $oeeLog = $this->config->logOeeTool($machine_id, $job_num, $tool_id, $date, $shift);
            $aggregate = $this->config->aggregateSummaryTool($machine_id, $job_num, $tool_id, $date, $shift);
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
            $this->config->insertGsphRecordTool($machine_id, $date, $time, $tool_id, $job_num, $shift);
            Http::withoutVerifying()->post(
                config('services.api_factory.url') . 'machine-status/update',
                [
                    "machine_id" => $machine_id,
                    "condition_id" => 1,
                    "job_num" => $job_num
                ]
            );
            $machineData = DB::table('log_machine_tool')
                ->where('machine_id', $machine_id)
                ->where('tool_id', $tool_id)
                ->first();
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
            'status' => 'success',
            'machine_id' => $machine_id
        ]);
    }
    public function shift_get_all(Request $request)
    {
        $category = $request->category;
        $data = $this->config->shift_get_all($category);
        return response()->json($data);
    }
    public function get_job_all(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $category = $request->category;
        $data = $this->config->get_job_all($search, $category);
        $total = $this->config->get_job_all_count($search, $category);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function get_downtime(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 30;
        $offset = ($page - 1) * $limit;
        $dept = $request->dept;

        $data = $this->config->get_downtime($search, $dept, $offset, $limit);
        $total = $this->config->get_downtime_all_count($search, $dept);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function get_machine(Request $request)
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
        // dd($jobOpDtl);
        $machineData = DB::table('log_header_machine')
            ->whereIn('machine_id', $machineIds)
            // ->where('condition_id', 1)
            ->get();
        return response()->json(array_merge(
            $responseData,
            [
                'machine' => $machineData
            ]
        ));
    }
    public function get_employee(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $data = $this->config->get_employee($search, $offset, $limit);
        $total = $this->config->get_employee_count($search);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function save_downtime(Request $request)
    {
        $id = $request->downtime['id'];
        $machine = $request->machine;
        $remark = $request->remark;
        try {
            $downtime_list = $this->config->downtime_list_id($id);
            $machine_data = $this->config->machine_data($machine);
            if (!$machine_data) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data machine tidak ditemukan'
                ]);
            }
            $insert = $this->config->insert_downtime([
                'machine_id' => $machine,
                'job_num' => $machine_data->job_num,
                'shift' => $machine_data->shift,
                'downtime_id' => $downtime_list->id,
                'started_at' => now('Asia/Jakarta'),
                'downtime' => 0,
                'is_active' => 1,
                'production_date' => $machine_data->production_date
            ]);
            $this->config->insert_activity([
                'activity' => $downtime_list->name,
                'machine_id' => $machine,
                'job_num' => $machine_data->job_num,
                'start_date' => now('Asia/Jakarta'),
                'downtime_seq_id' => $insert,
                'shift' => $machine_data->shift,
                'production_date' => $machine_data->production_date,
                'note' => $remark
            ]);
            $machine = NULL;
            if (str_contains(strtoupper($machine_data->category_line_id), 'ASSY')) {
                $line = 'ASSY';
                if (str_contains(strtoupper($machine_data->machine_id), 'RSW')) {
                    $machine = 'RSW';
                }
                if (str_contains(strtoupper($machine_data->machine_id), 'SSW-B')) {
                    $machine = 'SSW-B';
                }
            } else {
                $line = 'STP';
                if (str_contains(strtoupper($machine_data->machine_id), 'A6')) {
                    $machine = 'A6';
                }
            }
            $employee_data = $this->config->employee_data($line, $machine, $downtime_list->type, 1);
            // dd($employee_data);
            foreach ($employee_data as $data) {
                Http::acceptJson()
                    ->post(config('services.ems_wa.url'), [
                        'phone' => $data->Telp,
                        'message' => "*[ESCALATION DOWNTIME]*

            Dear Bapak/Ibu,
Downtime sedang berlangsung dan memerlukan perhatian segera.
Machine  : {$machine_data->machine_id}
Downtime : {$downtime_list->name}
Keterangan : {$remark}
Start    : " . now()->format('Y-m-d H:i:s') . "
Duration : -20 menit

            Mohon segera dilakukan pengecekan dan tindak lanjut untuk meminimalisir impact terhadap produksi.

            Terima kasih."
                    ]);
            }
            if ($downtime_list->type == 'MTN') {
                $update = Http::withoutVerifying()->post(config('services.api_factory.url') . 'dies-status/update', [
                    "machine_id" => $machine,
                    "PartNum" => $machine_data->part_no,
                    "condition_id" => 2,
                ]);
                $response_update = $update->json();

                if (!isset($response_update['success']) || !$response_update['success']) {

                    return response()->json([
                        'status' => 401,
                        'message' => 'Gagal update',
                        'response' => $response_update
                    ]);
                }

                $create = Http::withoutVerifying()->post(
                    config('services.api_factory.url') . 'tickets/create',
                    [
                        "machine_id" => $machine,
                        "title" => $downtime_list->name,
                        "downtime_category" => 'Machine',
                        "impact_breakdown" => "Yes",
                        "downtime_type" => "Unplanned"
                    ]
                );

                $response_create = $create->json();

                if (isset($response_create['success']) && $response_create['success']) {

                    return response()->json([
                        'status' => 200,
                        'message' => 'Data berhasil di kirim',
                        'response' => $response_create
                    ]);
                }

                return response()->json([
                    'status' => 401,
                    'message' => 'Gagal Kirim data',
                    'response' => $response_create
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Data berhasil di kirim'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function stop_downtime(Request $request)
    {
        $machine = $request->machine;
        $job_num = $request->job_num;
        try {
            $machine_data = $this->config->machine_data($machine);
            if (!$machine_data) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data machine tidak ditemukan'
                ]);
            }
            if (str_contains(strtoupper($machine_data->category_line_id), 'ASSY')) {
                $line = 'ASSY';
                if (str_contains(strtoupper($machine_data->machine_id), 'RSW')) {
                    $machine = 'RSW';
                }
                if (str_contains(strtoupper($machine_data->machine_id), 'SSW-B')) {
                    $machine = 'SSW-B';
                }
            } else {
                $line = 'STP';
                if (str_contains(strtoupper($machine_data->machine_id), 'A6')) {
                    $machine = 'A6';
                }
            }
            // dd($machine, $job_num, $machine_data->production_date);
            $downtime_log = $this->config->downtime_log_get($machine_data->machine_id, $job_num, $machine_data->production_date);
            if (!$downtime_log) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data downtime tidak ditemukan'
                ]);
            }
            $downtime_list = $this->config->downtime_list_id($downtime_log->downtime_id);
            $position = 1;
            if ($downtime_log->notif_5m == 1) {
                $position = 2;
            }
            if ($downtime_log->notif_10m == 1) {
                $position = 3;
            }
            if ($downtime_log->notif_15m == 1) {
                $position = 4;
            }
            if ($downtime_log->notif_30m == 1) {
                $position = 5;
            }
            $employee_data = $this->config->employee_data_stop($line, $machine, $downtime_list->type, $position);
            // $employee_data = $this->config->employee_data($line, $machine, $downtime_list->type, 6);
            foreach ($employee_data as $data) {
                Http::acceptJson()
                    ->post(config('services.ems_wa.url'), [
                        'phone' => $data->Telp,
                        'message' => "*[INFORMASI DOWNTIME SELESAI]*
Dear Bapak/Ibu,
Downtime pada {$machine_data->machine_id} telah selesai.
Machine  : {$machine_data->machine_id}
Downtime : {$downtime_list->name}
Keterangan : {$downtime_log->remark}
Start    : {$downtime_log->started_at}
End      : " . now()->format('Y-m-d H:i:s') . "

                        Terima kasih."
                    ]);
            }
            $this->config->downtime_update($machine_data->machine_id, $job_num, $machine_data->production_date);
            $this->config->activity_update($machine_data->machine_id, $job_num, $machine_data->production_date);
            Http::withoutVerifying()->post(config('services.api_factory.url') . 'machine-status/update', [
                "PartNum" => $machine_data->part_no,
                "condition_id" => 1,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Downtime berhasil di hentikan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function finish_machine(Request $request)
    {
        $machine = $request->machine;
        $job_num = $request->job_num;
        try {
            if ($machine == 'RSW-5H45-10' || $machine == 'RSW-5H45-09') {
                $cur_machine = $this->config->current_machine_tool($job_num);
            } else {
                $cur_machine = $this->config->current_machine($job_num);
            }
            if (!$cur_machine) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data mesin tidak ditemukan'
                ], 404);
            }
            // if ($cur_machine->qty_plan > $cur_machine->qty_actual) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Qty actual tidak sesuai dengan Qty Plan'
            //     ], 422);
            // }
            $history = [
                'machine_id' => $machine,
                'line_id' => $cur_machine->line_id,
                'line_detail_id' => $cur_machine->line_detail_id,
                'category_line_id' => $cur_machine->category_line_id,
                'machine_code' => $cur_machine->machine_code,
                'machine_name' => $cur_machine->machine_name,
                'tonage' => $cur_machine->tonage,
                'average_ct' => $cur_machine->average_ct,
                'standard_sph' => $cur_machine->standard_sph,
                'started_at' => $cur_machine->started_at,
                'operation_time' => $cur_machine->operation_time,
                'break_12' => $cur_machine->break_12,
                'break_18' => $cur_machine->break_18,
                'break_02' => $cur_machine->break_02,
                'production_date' => $cur_machine->production_date,
                'part_no' => $cur_machine->part_no,
                'job_num' => $cur_machine->job_num,
                'qty_plan' => $cur_machine->qty_plan,
                'qty_actual' => $cur_machine->qty_actual,
                'qty_ng' => $cur_machine->qty_ng,
                'shift' => $cur_machine->shift,
                'current_gsph' => $cur_machine->current_gsph,
                'finished_at' => now('Asia/Jakarta'),
                'condition_id' => 0,
                'customer' => $cur_machine->customer,
                'employee_id' => $cur_machine->employee_id,
                'employee_name' => $cur_machine->employee_name,
                'qty_ok' => max(0, ($cur_machine->qty_actual ?? 0) - ($cur_machine->qty_ng ?? 0))
            ];
            $data = [
                'machine_id' => $machine,
                'part_no' => null,
                'job_num' => null,
                'qty_plan' => 0,
                'qty_actual' => 0,
                'qty_ng' => 0,
                'shift' => null,
                'current_gsph' => null,
                'finished_at' => now('Asia/Jakarta'),
                'condition_id' => 0,
                'status_finish' => true,
                'customer' => null,
                'employee_id' => null,

            ];
            // $this->config->insert_history_machine($history);
            // $this->config->update_setup($machine, $data);
            // Http::withoutVerifying()->post(
            //     config('services.api_factory.url') . 'machine-status/update',
            //     [
            //         "machine_id" => $machine,
            //         "condition_id" => 0,
            //         "job_num" => $job_num
            //     ]
            // );
            // return response()->json([
            //     'status' => true,
            //     'message' => 'Mesin berhasil di finish'
            // ], 201);
            if ($cur_machine->machine_id == $machine && $cur_machine->qty_plan == $cur_machine->qty_actual) {
                // if ($cur_machine->machine_id == 'RSW-5J45-04') {
                // $data['status_acc'] = true;
                return response()->json([
                    'status' => true,
                    'message' => 'The last machine',
                    'data' => $cur_machine
                ], 200);
            } else {
                if ($cur_machine->qty_plan == $cur_machine->qty_actual) {
                    $data['status_acc'] = true;
                } else {
                    $data['status_acc'] = false;
                }
                if ($machine == 'RSW-5H45-10' || $machine == 'RSW-5H45-09') {
                    $data['tool_id'] = $cur_machine->tool_id;
                    $history['tool_id'] = $cur_machine->tool_id;
                    $this->config->insert_history_machine($history);
                    $this->config->update_setup_tool($machine, $data, $cur_machine->tool_id);
                } else {
                    $this->config->insert_history_machine($history);
                    $this->config->update_setup($machine, $data);
                }
                Http::withoutVerifying()->post(
                    config('services.api_factory.url') . 'machine-status/update',
                    [
                        "machine_id" => $machine,
                        "condition_id" => 0,
                        "job_num" => $job_num
                    ]
                );
                return response()->json([
                    'status' => true,
                    'message' => 'Mesin berhasil di finish'
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function create_new(Request $request)
    {
        try {
            $employee = explode('~', $request->employee);
            $employee_id = $employee[0];
            $url = config('services.epicor_app.url') . '/Labor/CreateNew';
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url, [
                        'employeeNum' => $employee_id,
                        'startDate' => $request->production_date,
                        'nik' => $request->nik,
                        'password' => $request->password,
                    ]);
            $data = json_decode($response->body(), true);
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function change_shift(Request $request)
    {
        try {
            $url = config('services.epicor_app.url') . '/Labor/ChangeShift';
            $laborHedSeq = $request->laborHedSeq;
            $shift = DB::connection('sqlsrv4')
                ->table('Erp.JCShift')
                ->where('Description', $request->shift)
                ->select('Shift')
                ->first();
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url, [
                        'laborHedSeq' => $laborHedSeq,
                        'shift' => $shift->Shift,
                        'nik' => $request->nik,
                        'password' => $request->password,
                    ]);
            $data = json_decode($response->body(), true);
            return response()->json([
                'status' => $response->successful(),
                'epicor' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }

    }
    public function update_header(Request $request)
    {
        try {
            $shift = DB::connection('sqlsrv4')
                ->table('Erp.JCShift')
                ->where('Description', $request->shift)
                ->select('Shift')
                ->first();
            $url = config('services.epicor_app.url') . '/Labor/UpdateHeader';
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url, [
                        'workDate' => \Carbon\Carbon::parse($request->workDate)
                            ->utc()
                            ->format('Y-m-d\TH:i:s.v\Z'),
                        'laborHedSeq' => $request->laborHedSeq,
                        'shift' => $shift->Shift,
                        'payHours' => $request->payHour,
                        'clockInDate' => $request->clockInDate,
                        'actualClockinDate' => $request->actualClockinDate,
                        'clockInTime' => $request->clockInTime,
                        'actualClockInTime' => $request->actualClockInTime,
                        'clockOutTime' => $request->clockOutTime,
                        'actualClockOutTime' => $request->actualClockOutTime,
                        'lunchOutTime' => $request->lunchOutTime,
                        'actLunchOutTime' => $request->actLunchOutTime,
                        'lunchInTime' => $request->lunchInTime,
                        'actLunchInTime' => $request->actLunchInTime,
                        'nik' => $request->nik,
                        'password' => $request->password
                    ]);
            $data = json_decode($response->body(), true);
            if ($data['code'] == 200 && $data['status'] == 'Ok') {
                $re_data = $data['data'];
                return response()->json([
                    'status' => true,
                    'laborHedSeq' => $re_data['laborHedSeq']
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $data['status']
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function labor_submit_entry(Request $request)
    {
        try {
            $jobOpDtl = DB::connection('sqlsrv4')
                ->table('Erp.JobOpDtl')
                ->where('ResourceID', $request->resourceID)
                ->where('JobNum', $request->jobNum)
                ->select('OprSeq', 'ResourceGrpID', 'ResourceID')
                ->first();
            if (!$jobOpDtl) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job operation tidak ditemukan'
                ]);
            }
            $JCShift = DB::connection('sqlsrv4')
                ->table('Erp.JCShift')
                ->where('Description', $request->shift)
                ->select('Shift')
                ->first();
            $url = config('services.epicor_app.url');
            // $GetOprSeq = Http::withoutVerifying()->withHeaders([
            //     'Content-Type' => 'application/json',
            //     'Accept' => 'application/json'
            // ])->post($url . 'GetOprSeq', [
            //             'jobNum' => $request->jobNum,
            //             'nik' => $request->nik,
            //             'password' => $request->password
            //         ]);
            // $GetOprRes = json_decode($GetOprSeq->body(), true);
            // if (!$GetOprRes || !isset($GetOprRes['oprSeq'])) {
            //     return response()->json([
            //         'status' => false,
            //         'step' => 'get_opseq',
            //         'message' => 'OprSeq tidak ditemukan'
            //     ]);
            // }
            // $LaborDtl = DB::connection('sqlsrv4')
            //     ->table('Erp.LaborDtl')
            //     ->where('ResourceID', $request->resourceID)
            //     ->where('JobNum', $request->input('jobNum'))
            //     ->select('OprSeq')
            //     ->first();
            // $oprSeq = 10;
            // if ($LaborDtl) {
            //     if ($LaborDtl->OprSeq == 10) {
            //         $oprSeq = 20;
            //     }
            // }
            // Log::info('REQUEST GET NEW', [
            //     'payload' => [
            //         'laborTypePseudo' => 'P',
            //         'laborHedSeq' => $request->input('laborHedSeq'),
            //         'jobNum' => $request->input('jobNum'),
            //         'opSeq' => '10',
            //         'date' => $request->input('date'),
            //         'nik' => $request->input('nik'),
            //         'password' => $request->input('password'),
            //         'resourceGrpID' => "",
            //         'resourceID' => "",
            //         'indirectCode' => "",
            //         'indirectDescription' => ""
            //     ]
            // ]);
            $getNewLaborDtl = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url . '/Labor/GeNewtLaborDtl', [
                        'laborTypePseudo' => 'P',
                        'laborHedSeq' => $request->input('laborHedSeq'),
                        'jobNum' => $request->input('jobNum'),
                        'opSeq' => $request->input('opr_seq') ?? '10',
                        'date' => $request->input('date'),
                        'nik' => $request->input('nik'),
                        'password' => $request->input('password'),
                        'resourceGrpID' => "",
                        'resourceID' => "",
                        'indirectCode' => "",
                        'indirectDescription' => ""
                    ]);
            $resp_get_new = json_decode($getNewLaborDtl->body(), true);
            if ($resp_get_new['code'] !== 200) {
                Log::error('Error GetNewtLaborDtl', [
                    'response' => $resp_get_new,
                    'request' => $request->all()
                ]);
                $cur_machine = $this->config->current_machine($request->input('jobNum'));
                $history = [
                    'machine_id' => $request->resourceID,
                    'line_id' => $cur_machine->line_id,
                    'line_detail_id' => $cur_machine->line_detail_id,
                    'category_line_id' => $cur_machine->category_line_id,
                    'machine_code' => $cur_machine->machine_code,
                    'machine_name' => $cur_machine->machine_name,
                    'tonage' => $cur_machine->tonage,
                    'average_ct' => $cur_machine->average_ct,
                    'standard_sph' => $cur_machine->standard_sph,
                    'started_at' => $cur_machine->started_at,
                    'operation_time' => $cur_machine->operation_time,
                    'break_12' => $cur_machine->break_12,
                    'break_18' => $cur_machine->break_18,
                    'break_02' => $cur_machine->break_02,
                    'production_date' => $cur_machine->production_date,
                    'part_no' => $cur_machine->part_no,
                    'job_num' => $cur_machine->job_num,
                    'qty_plan' => $cur_machine->qty_plan,
                    'qty_actual' => $cur_machine->qty_actual,
                    'qty_ng' => $cur_machine->qty_ng,
                    'shift' => $cur_machine->shift,
                    'current_gsph' => $cur_machine->current_gsph,
                    'finished_at' => now('Asia/Jakarta'),
                    'condition_id' => 0,
                    'customer' => $cur_machine->customer,
                    'employee_id' => $cur_machine->employee_id,
                    'employee_name' => $cur_machine->employee_name,
                    'qty_ok' => max(0, ($cur_machine->qty_actual ?? 0) - ($cur_machine->qty_ng ?? 0))
                ];
                $data = [
                    'machine_id' => $request->resourceID,
                    'part_no' => null,
                    'job_num' => null,
                    'qty_plan' => 0,
                    'qty_actual' => 0,
                    'qty_ng' => 0,
                    'shift' => null,
                    'current_gsph' => null,
                    'finished_at' => now('Asia/Jakarta'),
                    'condition_id' => 0,
                    'status_finish' => true,
                    'customer' => null,
                    'employee_id' => null,

                ];
                $this->config->insert_history_machine($history);
                $this->config->update_setup($request->resourceID, $data);
                Http::withoutVerifying()->post(
                    config('services.api_factory.url') . 'machine-status/update',
                    [
                        "machine_id" => $request->resourceID,
                        "condition_id" => 0,
                        "job_num" => $request->input('jobNum')
                    ]
                );
                return response()->json([
                    'status' => true,
                    'step' => 'GeNewtLaborDtl',
                    'message' => 'Mesin berhasil finish gagal time entry'
                ]);
            }
            $data_get_new = $resp_get_new['data'];
            $changeLabor = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url . '/Labor/ChangeLaborTime', [
                        'laborHedSeq' => $request->laborHedSeq,
                        'laborDtlSeq' => $data_get_new['laborDtlSeq'],
                        'shift' => $JCShift->Shift,
                        // 'shift' => 2,
                        'shiftDescription' => "",
                        'clockinTime' => $request->clockinTime,
                        'clockOutTime' => $request->clockOutTime,
                        'nik' => $request->nik,
                        'password' => $request->password
                    ]);
            $res_change_labor = json_decode($changeLabor->body(), true);
            if ($res_change_labor['code'] !== 200) {
                return response()->json([
                    'status' => false,
                    'step' => 'ChangeLaborTime',
                    'message' => $res_change_labor['status']
                ]);
            }
            $data_change_labor = $res_change_labor['data'];
            $updateDtl = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url . '/Labor/UpdateDtl', [
                        'laborHedSeq' => (int) $request->laborHedSeq,
                        'laborDtlSeq' => (int) $data_get_new['laborDtlSeq'],
                        'date' => (string) $request->date,
                        'clockInDate' => (string) $request->clockInDate,
                        'clockinTime' => (string) $request->clockinTime,
                        'clockOutTime' => (string) $request->clockOutTime,
                        'laborHrs' => (float) $data_change_labor['laborHrs'],
                        'burdenHrs' => (float) $data_change_labor['burdenHrs'],
                        'laborQty' => $request->laborQty,
                        'scrapQty' => 0,
                        'discrepQty' => (float) $request->discrepQty,
                        'discrpRsnCode' => (string) $request->discrpRsnCode,
                        'scrapReasonCode' => "",
                        'resourceGrpID' => $jobOpDtl->ResourceGrpID,
                        'resourceID' => $jobOpDtl->ResourceID,
                        'resourceGrpDescription' => "",
                        'indirectCode' => "",
                        'laborNote' => (string) $request->laborNote,
                        'rowMod' => "U",
                        'nik' => (string) $request->nik,
                        'password' => (string) $request->password
                    ]);
            $res_update_dtl = json_decode($updateDtl->body(), true);
            if ($res_update_dtl['code'] !== 200) {
                return response()->json([
                    'status' => false,
                    'step' => 'UpdateDtl',
                    'message' => $res_update_dtl['status']
                ]);
            }
            $submit = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($url . '/Labor/Submit', [
                        'laborHedSeq' => $request->laborHedSeq,
                        'laborDtlSeq' => $data_get_new['laborDtlSeq'],
                        'nik' => $request->nik,
                        'password' => $request->password
                    ]);
            $res_submit = json_decode($submit->body(), true);
            if ($res_submit['code'] == 200 && $res_submit['status'] == 'OK') {
                if ($request->resourceID == 'RSW-5H45-10' || $request->resourceID == 'RSW-5H45-09') {
                    $cur_machine = DB::table('log_machine_tool')
                        ->where('machine_id', $request->resourceID)
                        ->where('job_num', $request->jobNum)
                        ->first();
                } else {
                    $cur_machine = DB::table('log_header_machine')
                        ->where('machine_id', $request->resourceID)
                        ->where('job_num', $request->jobNum)
                        ->first();
                }
                $history = [
                    'machine_id' => $request->resourceID,
                    'line_id' => $cur_machine->line_id ?? 0,
                    'line_detail_id' => $cur_machine->line_detail_id ?? 0,
                    'category_line_id' => $cur_machine->category_line_id ?? 0,
                    'machine_code' => $cur_machine->machine_code,
                    'machine_name' => $cur_machine->machine_name,
                    'tonage' => $cur_machine->tonage,
                    'average_ct' => $cur_machine->average_ct,
                    'standard_sph' => $cur_machine->standard_sph,
                    'started_at' => $cur_machine->started_at,
                    'operation_time' => $cur_machine->operation_time,
                    'break_12' => $cur_machine->break_12,
                    'break_18' => $cur_machine->break_18,
                    'break_02' => $cur_machine->break_02,
                    'production_date' => $cur_machine->production_date,
                    'part_no' => $cur_machine->part_no,
                    'job_num' => $cur_machine->job_num,
                    'qty_plan' => $cur_machine->qty_plan,
                    'qty_actual' => $cur_machine->qty_actual,
                    'qty_ng' => $cur_machine->qty_ng,
                    'shift' => $cur_machine->shift,
                    'current_gsph' => $cur_machine->current_gsph,
                    'finished_at' => now('Asia/Jakarta'),
                    'condition_id' => 0,
                    'customer' => $cur_machine->customer,
                    'employee_id' => $cur_machine->employee_id,
                    'employee_name' => $cur_machine->employee_name,
                    'qty_ok' => max(0, ($cur_machine->qty_actual ?? 0) - ($cur_machine->qty_ng ?? 0))
                ];
                $data = [
                    'machine_id' => $request->resourceID,
                    'part_no' => null,
                    'job_num' => null,
                    'qty_plan' => 0,
                    'qty_ng' => 0,
                    'qty_actual' => 0,
                    'shift' => null,
                    'current_gsph' => null,
                    'finished_at' => now('Asia/Jakarta'),
                    'condition_id' => 0,
                    'status_finish' => true,
                    'customer' => null,
                    'employee_id' => null,

                ];
                Http::withoutVerifying()->post(
                    config('services.api_factory.url') . 'machine-status/update',
                    [
                        "machine_id" => $request->resourceID,
                        "condition_id" => 0,
                        "job_num" => $cur_machine->job_num
                    ]
                );
                if ($request->resourceID == 'RSW-5H45-10' || $request->resourceID == 'RSW-5H45-09') {
                    $data['tool_id'] = $cur_machine->tool_id;
                    $history['tool_id'] = $cur_machine->tool_id;
                    $this->config->insert_history_machine($history);
                    $this->config->update_setup_tool($request->resourceID, $data, $cur_machine->tool_id);
                } else {
                    $this->config->insert_history_machine($history);
                    $this->config->update_setup($request->resourceID, $data);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Mesin berhasil di finish'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'step' => 'submit_entry',
                    'message' => $res_submit['status']
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function technician_arrived(Request $request)
    {
        $machine_id = $request->machine;
        $data = Http::withoutVerifying()->post(config('services.api_factory.url') . "machine/{$machine_id}/stop-alarm");
        return response()->json([
            'status' => $data->status(),
            'message' => $data->body()
        ]);
    }
    public function special_setup_work_time(Request $request)
    {
        $machine_id = $request->machine;
        $data = $this->config->WorkTime($machine_id);
        return response()->json([
            'data' => $data
        ]);
    }
    public function job_list(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-d', strtotime('-20 days'));
        $finish = date('Y-m-d');
        $machine_id = $request->machine_id;
        $shift = $request->shift;
        if ($shift == 1 || $shift == 6 || $shift == 7 || $shift == 11) {
            $shift = 'SHIFT 2';
        } else {
            $shift = 'SHIFT 1';
        }
        $data = DB::table("f_production_schedule_epicor('$start', '$finish', '$machine_id', '$shift')")->get();
        return response()->json($data);
    }
    public function scan_qr(Request $request)
    {
        $refs = explode('~', $request->scanVal);
        $jo = $refs[0];
        $oprSe = $refs[1];
        $data = DB::connection('sqlsrv4')
            ->table('Erp.JobHead')
            ->where('JobNum', $jo)
            ->where('JobClosed', 0)
            ->select('JobCode')
            ->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'JO tidak ditemukan atau sudah close'
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $data
        ]);
    }
    public function confirm_table(Request $request)
    {
        $page = $request->page;
        if ($page == 'RBT-5J45') {
            $page = 'RSW-5J45';
        }
        $data = DB::table('log_machine_confirm')
            ->when($page, function ($query, $page) {
                $query->where('machine_id', 'LIKE', "%{$page}%");
            });

        return DataTables::of($data)
            ->addColumn('start', function ($row) {
                if ($row->job_num) {
                    $date = date('H:i', strtotime($row->started_at));
                } else {
                    $date = '-';
                }
                return $date;
            })
            ->addColumn('action', function ($row) {
                if ($row->job_num) {
                    $return = '<button class="btn btn-sm btn-primary" onclick="confirm_jo(\'' . $row->machine_id . '\')">Confirm</button>';
                } else {
                    $return = '-';
                }
                return $return;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function confirm_submit(Request $request)
    {
        try {
            $data = DB::table('log_machine_confirm')
                ->where('machine_id', $request->machine_id)
                ->first();
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mesin tidak ada'
                ]);
            }
            $shift = $data->shift;
            if ($data->shift == 'SHIFT 2') {
                $shift = 6;
            }
            $shiftMap = [
                1 => 8,
                6 => 8.25,
                7 => 8.5,
                11 => 6.75,
                12 => 6.75,
                13 => 7,
            ];
            $avail_time = $shiftMap[$shift] ?? 8;
            $epicor_shift = DB::connection('sqlsrv4')
                ->table('Erp.JCShift')
                ->where('Shift', $shift)
                ->select('Description')
                ->first();
            $shift = $epicor_shift->Description;
            $this->config->update_setup($data->machine_id, [
                'started_at' => date('Y-m-d H:i:s'),
                'job_num' => $data->job_num,
                'standard_sph' => $data->standard_sph,
                'production_date' => $data->production_date,
                'customer' => $data->customer,
                'condition_id' => 1,
                'is_active' => 1,
                'employee_id' => $data->employee_id,
                'employee_name' => $data->employee_name,
                'qty_plan' => $data->qty_plan,
                'part_no' => $data->part_no,
                'model' => $data->model,
                'status_finish' => 0,
                'shift' => $shift,
                'break_12' => 0,
                'break_18' => 0,
                'break_02' => 0,
                'finished_at' => null,
                'average_ct' => 0,
                'operation_time' => 0,
                'current_gsph' => 0,
                'qty_actual' => 0,
                'opr_seq' => $data->opr_seq ?? 10
            ]);
            $dataGsph = [
                'machine_id' => $data->machine_id,
                'gsph' => 0,
                'cut_off' => date('Y-m-d'),
                'cut_off_time' => date('Y-m-d H:i:s'),
                'tool_id' => null,
                'job_num' => $data->job_num,
                'shift' => $shift,
                'qty_actual' => 0
            ];
            $this->config->insertGsphRecord($dataGsph);
            $this->config->updateSummary($data->machine_id, $shift, $data->job_num, $data->production_date, $data->qty_plan);
            $result_oee = $this->config->resultOee($data->machine_id, $data->job_num, $shift, $data->production_date);
            $db_oee = $this->config->countDbOee($data->machine_id, $data->job_num, $shift, $data->production_date);
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
                $this->config->updateOee($data->machine_id, $data->job_num, $shift, $data->production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
            } else {
                $this->config->insertOee($data->machine_id, $data->job_num, $shift, $data->production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng);
            }
            Http::withoutVerifying()->post(
                config('services.api_factory.url') . 'machine-status/update',
                [
                    "machine_id" => $data->machine_id,
                    "condition_id" => 1,
                    "job_num" => $data->job_num
                ]
            );
            $message = [
                'machine_id' => $data->machine_id,
                'job_num' => $data->job_num,
                'qty_plan' => $data->qty_plan,
                'qty_actual' => 0,
                'part_no' => $data->part_no,
                'production_date' => $data->production_date,
                'standard_sph' => $data->standard_sph,
                'emp_name' => $data->standard_sph
            ];
            $client = new Client("ws://127.0.0.1:8080");
            $data = [
                'action' => 'trigger',
                'channel' => 'machine',
                'event' => 'start-machine',
                'data' => [
                    'message' => $message
                ]
            ];
            $client->send(json_encode($data));
            $client->close();
            DB::table('log_machine_confirm')
                ->where('machine_id', $request->machine_id)
                ->update([
                    'started_at' => date('Y-m-d H:i:s'),
                    'job_num' => null,
                    'standard_sph' => null,
                    'production_date' => null,
                    'customer' => null,
                    'employee_id' => null,
                    'employee_name' => null,
                    'qty_plan' => null,
                    'part_no' => null,
                    'model' => null,
                ]);
            return response()->json([
                'status' => true,
                'message' => 'confirm submit'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function downtime_message(Request $request)
    {
        $machine_id = $request->machine;
        $job_num = $request->job_num;
        $position = $request->position;
        if (str_contains($machine_id, '~')) {
            $machine_id = explode('~', $machine_id)[0];
            $data_machine = $this->config->machine_data_tool($machine_id, $machine_id[1]);
            $downtime_log = $this->config->downtime_log($machine_id, $job_num, $machine_id[1]);
        } else {
            $data_machine = $this->config->machine_data($machine_id);
            $downtime_log = $this->config->downtime_log($machine_id, $job_num, null);
        }
        if (!$data_machine) {
            return response()->json([
                'status' => false,
                'message' => 'Data mesin tidak ditemukan'
            ]);
        }
        if (!$downtime_log) {
            return response()->json([
                'status' => false,
                'message' => 'Data downtime tidak ditemukan'
            ]);
        }
        if (str_contains(strtoupper($data_machine->category_line_id), 'ASSY')) {
            $line = 'ASSY';
            if (str_contains(strtoupper($data_machine->machine_id), 'RSW')) {
                $machine = 'RSW';
            }
            if (str_contains(strtoupper($data_machine->machine_id), 'SSW-B')) {
                $machine = 'SSW-B';
            }
        } else {
            $line = 'STP';
            if (str_contains(strtoupper($data_machine->machine_id), 'A6')) {
                $machine = 'A6';
            }
        }
        $downtime_list = $this->config->downtime_list_id($downtime_log->downtime_id);
        $employee = $this->config->employee_data($line, $machine, $downtime_list->type, 6);
        foreach ($employee as $emp) {
            Http::acceptJson()
                ->post(config('services.ems_wa.url'), [
                    'phone' => $emp->Telp,
                    'message' => "*[NOTIFIKASI DOWNTIME]*

                    Dear Bapak/Ibu,

                    Terdapat informasi downtime yang memerlukan perhatian.

                    Machine ID : {$machine_id}
                    Downtime : {$downtime_list->name}
                    Start Time : " . now()->format('Y-m-d H:i:s') . "
                    Duration : 5 Menit+

                    Mohon segera dilakukan pengecekan dan tindak lanjut.

                    Terima kasih."
                ]);
        }
        return response()->json([
            'status' => true,
            'data' => [
                'machine' => $data_machine,
                'downtime_log' => $downtime_log,
                'downtime_list' => $downtime_list,
                'employee' => $employee
            ]
        ]);
    }
}
