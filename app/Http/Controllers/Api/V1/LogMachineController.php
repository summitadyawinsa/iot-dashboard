<?php

namespace App\Http\Controllers\Api\V1;
use App\Exports\HistoryLogMachineExport;
use App\Http\Controllers\Controller;
use App\Models\LogMachine;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use WebSocket\Client;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Models\Config;

class LogMachineController extends Controller
{
    protected $LogMachine;
    protected $config;
    public function __construct(LogMachine $logMachine, Config $config)
    {
        $this->LogMachine = $logMachine;
        $this->config = $config;
    }

    public function index($id)
    {
        $data = LogMachine::find("$id");
        if (strpos($data->category_line_id, 'STP') !== false) {
            return view('dashboard.stamping.page', [
                'id' => $id,
                'machine_code' => $data->machine_code,
            ]);
        } else {
            return view('dashboard.assy.page', [
                'id' => $id,
                'machine_code' => $data->machine_code,
            ]);
        }

    }

    public function downtime_list(Request $request)
    {
        $category_id = $request->category_id;
        $machine_id = "'" . $request->machine_id . "'";
        $data = $this->LogMachine->listDowntime($machine_id, $category_id);
        $element = '<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 p-5">';
        foreach ($data as $row) {
            $downtime_id = $row->id;
            $element .= '<div><button type="button" class="btn ' . ($row->id == $row->downtime_id ? "btn-danger" : "btn-light") . ' w-full m-2" dt-data="' . $row->seq_id . '" id="btnUpdate-' . $row->id . '" onclick="setDowntime(' . $downtime_id . ')">' . $row->code . '</button></div>';
        }
        $element .= '</div>';
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'element' => $element,
        ]);
    }
    public function check_status_machine(Request $request)
    {
        $id = $request->header('machine_id');
        $machine = LogMachine::find("$id");
        return response()->json([
            'last_counter' => (int) $machine->qty_actual,
            'job_num' => $machine->job_num,
            'qty_plan' => (int) $machine->qty_plan,
            'condition_id' => (int) $machine->condition_id,
            'machine_code' => $machine->machine_code,
            'status' => true,
            'message' => 'Data ditemukan',
        ], 200);
    }

    public function get_sch_production(Request $request)
    {
        $id = $request->machine_id;
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $db_sch_machine = $this->LogMachine->schProduction($id, $date_sql);
        $sch_production = '';
        foreach ($db_sch_machine as $a) {
            $sch_production .= '
                <div class="flex items-center">
                    <div class="w-9 h-9">
                        <div
                            class="bg-primary/10 text-primary rounded-xl w-9 h-9 flex justify-center items-center dark:bg-primary dark:text-white-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle opacity="0.5" cx="12" cy="12" r="4">
                                </circle>
                                <line opacity="0.5" x1="21.17" y1="8" x2="12" y2="8"></line>
                                <line opacity="0.5" x1="3.95" y1="6.06" x2="8.54" y2="14"></line>
                                <line opacity="0.5" x1="10.88" y1="21.94" x2="15.46" y2="14"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="px-3 flex-initial w-full">
                        <div class="w-summary-info flex justify-between font-semibold text-white-dark mb-1">
                            <h6>' . $a->item_no . '</h6>
                            <p class="ltr:ml-auto rtl:mr-auto text-xs">' . number_format($a->qty_actual / $a->qty_1 * 100, 0) . '%</p>
                        </div>
                        <div>
                            <div
                                class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:bg-dark-light/10 dark:shadow-none">
                                <div class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                    style="width: ' . number_format($a->qty_actual / $a->qty_1 * 100, 0) . '%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        ;
        $sch_production = '';
        return response()->json([
            'sch_production' => $sch_production,
            'status' => true,
            'message' => 'Data ditemukan',
        ], 200);
    }

    public function set_status_machine(Request $request)
    {
        $id = $request->header('machine_id');
        $condition_id = $request->header('condition_id');
        if ($condition_id == 0) {
            $update = LogMachine::where('machine_id', "$id")
                ->update([
                    'started_at' => NULL,
                    'finished_at' => NULL,
                    'average_ct' => 0,
                    'operation_time' => 0,
                    'current_gsph' => 0,
                    'production_date' => NULL,
                    'job_num' => NULL,
                    'part_no' => NULL,
                    'qty_plan' => NULL,
                    // 'qty_actual' => NULL,
                    'condition_id' => 0,
                    'shift' => NULL,
                    'break_12' => 0,
                    'break_18' => 0,
                    'break_02' => 0,
                ]);
        } else {
            $update = LogMachine::where('machine_id', "$id")
                ->update([
                    'condition_id' => $condition_id,
                ]);
        }
        if ($update) {
            return response()->json([
                'machine_id' => $id,
                'condition_id' => $condition_id,
                'status' => true,
                'message' => 'Berhasil update',
            ], 200);
        } else {
            return response()->json([
                'machine_id' => $id,
                'condition_id' => $condition_id,
                'status' => false,
                'message' => 'Gagal update',
            ], 404);
        }
    }

    public function set_dreser_update($machine_id, $dresser_count, $spot_count)
    {
        $update = LogMachine::where('machine_id', "$machine_id")
            ->update([
                'dresser_count' => $dresser_count,
                'spot_count' => $spot_count,
            ]);
        if ($update) {
            $dataMachine['machine_id'] = "$machine_id";
            $dataMachine['dresser_count'] = $dresser_count;
            $dataMachine['spot_count'] = $spot_count;
            $dataMachine['topic'] = 'dresser_count';
            MessageCreated::dispatch($dataMachine);
            return response()->json([
                'machine_id' => $machine_id,
                'dresser_count' => $dresser_count,
                'spot_count' => $spot_count,
                'status' => true,
                'message' => 'Berhasil update',
            ], 200);
        } else {
            return response()->json([
                'machine_id' => $machine_id,
                'dresser_count' => $dresser_count,
                'spot_count' => $spot_count,
                'status' => false,
                'message' => 'Gagal update',
            ], 404);
        }
    }

    public function mass_dresser_update(Request $request)
    {
        $machine_data = $request->header('data');
        // dd($machine_data);
        $array_data = json_decode($machine_data, true);
        $data_success = [];
        $spot_count = 0;
        foreach ($array_data as $machine) {
            $update = self::set_dreser_update($machine[0], $machine[1], $machine[2]);
            if ($update == true) {
                $data_success[] = [
                    'machine_id' => "$machine[0]",
                    'dresser' => $machine[1],
                    'spot' => $machine[2],
                    'Status' => true,
                    'data' => "$machine_data"
                ];
            } else {
                $data_success[] = [
                    'machine_id' => "$machine[0]",
                    'dresser' => $machine[1],
                    'spot' => $machine[2],
                    'Status' => false,
                    'data' => "$machine_data"
                ];
            }
        }
        return $data_success;
    }
    public function show($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $time_sql = date("H:i:s");
        $date_sql_ = "'" . date('Y-m-d') . "'";
        $machine = LogMachine::find("$id");
        // dd($machine);
        if ($machine) {
            $shift = ($machine->shift == '' ? 0 : $machine->shift);
            $db_gsph_record = DB::table("f_stroke_hour_logs_shift_2('$id', $date_sql_)")->select('*')->get();
            $data_chart_gsph = '';
            $no_gsph = 0;
            foreach ($db_gsph_record as $a) {
                $data_chart_gsph .= (int) $a->gsph . ($no_gsph == 10 ? '' : ', ');

                $no_gsph++;
            }
            $log_activity = DB::table('log_activity')
                ->where('machine_id', "$id")
                ->where('production_date', "$date_sql")
                ->where('shift', "$machine->shift")
                ->orderBy('seq_id', 'desc')
                ->get();
            $activity = '';

            $db_log_detail = DB::table('log_detail_machine')
                ->where('shift', "$machine->shift")
                ->where('machine_id', "$id")
                ->orderBy('seq_id', 'desc')
                ->limit(24)
                ->get();

            $ct_log_detail = [];
            $no = 1;

            foreach ($db_log_detail as $row) {
                $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time * 60, 0));
                $ct_log_detail[] = $cycle_time;
                if ($no == 25) {
                    break;
                }
                $no++;
            }
            $ct_log_detail = array_reverse($ct_log_detail);
            $ct_log_detail = implode(',', $ct_log_detail);
            $activity = '';
            if ($log_activity->count() > 0) {
                $bg_1 = 'bg-secondary shadow shadow-secondary';
                $bg_2 = 'bg-success shadow-success';
                $bg_3 = 'bg-primary';
                $bg_4 = 'bg-warning';
                foreach ($log_activity as $a) {
                    $activity .= '
                    <div class="flex">
                        <div
                            class="shrink-0 ltr:mr-2 rtl:ml-2 relative z-10 before:w-[2px] before:h-[calc(100%-24px)] before:bg-white-dark/30 before:absolute before:top-10 before:left-4">
                            <div class="' . ${'bg_' . random_int(1, 4)} . ' w-8 h-8 rounded-full flex items-center justify-center text-white">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M4 12.9L7.14286 16.5L15 7.5"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M20.0002 7.5625L11.4286 16.5625L11.0002 16" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div>
                        <h5 class="font-semibold dark:text-white-light">' . $a->activity . ' start from ' . substr($a->start_date, 11, 5) . ' until ' . substr($a->end_date, 11, 5) . '</h5>
                        <p class="text-white-dark text-xs">' . substr($a->start_date, 0, 10) . '</p>
                        </div>
                    </div>
                    ';
                }
                ;
            } else {
                $activity .= '';
            }
            // $db_sch_machine = DB::table('v_sch_production')->where('id', $id)->where('shift', $machine->shift)->where('doc_date', "$date_sql")->limit(4)->orderBy('qty_2', 'desc')->orderBy('qty_actual', 'desc')->get();
            $db_sch_machine = DB::table("f_sch_production('$id', '$date_sql')")->limit(4)->orderBy('qty_plan', 'asc')->get();
            $sch_production = '';
            if ($db_sch_machine->count() > 0) {
                foreach ($db_sch_machine as $a) {
                    if ($machine->job_num != $machine->job_num) {
                        $sch_production .= '
                            <div class="flex items-center">
                                <div class="w-9 h-9">
                                    <div
                                        class="bg-primary/10 text-primary rounded-xl w-9 h-9 flex justify-center items-center dark:bg-primary dark:text-white-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle opacity="0.5" cx="12" cy="12" r="4">
                                            </circle>
                                            <line opacity="0.5" x1="21.17" y1="8" x2="12" y2="8"></line>
                                            <line opacity="0.5" x1="3.95" y1="6.06" x2="8.54" y2="14"></line>
                                            <line opacity="0.5" x1="10.88" y1="21.94" x2="15.46" y2="14"></line>
                                        </svg>
                                    </div>
                                </div>
                                <div class="px-3 flex-initial w-full">
                                    <div class="w-summary-info flex justify-between font-semibold text-white-dark mb-1">
                                        <h6>' . $a->item_no . ' | ' . $machine->job_num . ' | ' . $a->shift . ' | Plan : ' . number_format($a->qty_plan, 0) . '</h6>
                                        <p class="ltr:ml-auto rtl:mr-auto text-xs">' . ($a->achievment) . '%</p>
                                    </div>
                                    <div>
                                    <div class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:bg-dark-light/10 dark:shadow-none">
                                        <div id="bar_progress" class="bg-gradient-to-r from-[#e7515a] to-[#00ab55] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                                            style="width: ' . ($a->achievment) . '%;"></div>
                                    </div>
                                    </div>
                                </div>
                            </div> ';
                    }
                }
                ;
            } else {
                $sch_production .= '
                <div class="panel h-full overflow-hidden before:absolute before:-right-44 before:top-0 before:bottom-0 before:m-auto before:rounded-full before:w-96 before:h-96 content-between gap-6">
                        <div class="text-center text-white-light z-[7]">
                            <div class="align-center xl:text-4xl sm:text-xl shadow-[0_0_2px_0_#bfc9d4] rounded p-2">
                                <p class="align-center text-white-light p-1 xl:text-lg sm:text-sm">
                                    No Schedule
                                </p>
                            </div>
                        </div>
                    </div>
                ';
            }
            $sch_production = '';
            if ($machine->category_line_id == 'ASSY') {
                $db_downtime_record = DB::table("f_downtime_logs('$id')")->select('*')->get();
            } else {
                $db_downtime_record = DB::table("f_downtime_logs_stp('$id')")->select('*')->get();
            }
            $data_chart_downtime = '';
            $no_downtime = 0;
            foreach ($db_downtime_record as $a) {
                $data_chart_downtime .= (int) $a->downtime . ($no_downtime == 4 ? '' : ', ');
                $no_downtime++;
            }


            $new_result_oee = DB::table('oee_log_machine')
                ->where('machine_id', '=', "$id")
                ->where('shift', '=', "$machine->shift")
                ->where('job_num', '=', "$machine->job_num")
                ->where('production_date', '=', "$machine->production_date" . ' 00:00:00')
                ->get();
            if ($machine->job_num !== '') {
                if ($new_result_oee->count() > 0) {
                    $new_result_oee = DB::table('oee_log_machine')
                        ->where('machine_id', '=', "$id")
                        ->where('shift', '=', "$machine->shift")
                        ->where('job_num', '=', "$machine->job_num")
                        ->where('production_date', '=', "$machine->production_date" . ' 00:00:00')
                        ->get();

                    foreach ($new_result_oee as $b) {
                        $oee_quality = (($b->total_qty > 0 && $b->total_ng > 0) ? 100 - ceil($b->total_ng / $b->total_qty * 100) : 100);
                        $oee_availability = ($b->available_time > 0 ? ceil($b->operation_time / ($b->available_time) * 100) : 0);
                        $oee_performance = (($b->operation_time_standard > 0 && $b->operation_time > 0) ? ceil($b->operation_time_standard / $b->operation_time * 100) : 0);
                    }
                } else {
                    $oee_quality = 100;
                    $oee_availability = 0;
                    $oee_performance = 0;
                }
            } else {
                $oee_quality = 0;
                $oee_availability = 0;
                $oee_performance = 0;
            }

            $date_by_header = "'" . $machine->production_date . "'";
            $db_log_accumulate_stroke = DB::table("f_stroke_hour_logs_shift_2('$id', $date_by_header)")
                ->where(function ($query) {
                    $query->where('gsph', '>', 0);
                    $query->orWhere('seq_id', 1);
                })
                ->orderBy('seq_id', 'asc')
                ->get();

            $log_accumulate_stroke = [];
            $data_stroke = 0;
            $no = 1;
            foreach ($db_log_accumulate_stroke as $row) {
                $data_stroke += (int) $row->gsph;
                $log_accumulate_stroke[] = $data_stroke;
            }
            $data_chart_accumulate = implode(',', $log_accumulate_stroke);
            $responseData = [
                'message' => [
                    'standard_sph' => ($machine->standard_sph <= 0 ? 0 : (int) $machine->standard_sph),
                    'total_stroke' => number_format($machine->total_stroke, 0),
                    'qty_actual' => number_format($machine->qty_actual, 0),
                    'dresser_count' => number_format($machine->dresser_count, 0),
                    'spot_count' => number_format($machine->spot_count, 0),
                    'current_gsph' => number_format($machine->current_gsph, 0),
                    'current_gsph_persen' => (($machine->current_gsph > 0 && $machine->standard_sph > 0) ? ceil($machine->current_gsph / $machine->standard_sph * 100) : 0),
                    'condition_id' => $machine->condition_id,
                    'job_num' => $machine->job_num,
                    'qty_plan' => number_format($machine->qty_plan, 0),
                    'part_no' => $machine->part_no,
                    'shift' => $machine->shift,
                    'average_ct' => ($machine->average_ct > 0 ? number_format(($machine->average_ct * 60), 0) : 0),
                    'bar_progress' => ($machine->qty_plan > 0 ? number_format($machine->qty_actual / $machine->qty_plan * 100, 0) : 0),
                    'ct_log_detail' => $ct_log_detail,
                    'activity' => $activity,
                    'sch_production' => $sch_production,
                    'data_chart_gsph' => $data_chart_gsph,
                    'data_chart_downtime' => $data_chart_downtime,
                    'machine_code' => $machine->machine_code,
                    'oee_quality' => $oee_quality . "%",
                    'oee_availability' => $oee_availability . "%",
                    'oee_performance' => $oee_performance . "%",
                    'oee_value' => floor(($oee_quality + $oee_availability + $oee_performance) / 3),
                    'data_chart_accumulate' => $data_chart_accumulate,
                ],
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['message' => 'Machine not found'], 404);
        }
    }
    public function machine_list_by_category($id)
    {
        if (!$id) {
            $id = 'STP';
        }
        $data = LogMachine::where('category_line_id', 'LIKE', '%' . $id . '%')->where('is_active', 1)->get();
        $db = '<select id="machine-select" class="selectize-machine">';
        foreach ($data as $row) {
            $db .= '<option value="' . $row->machine_code . '">' . $row->machine_code . '</option>';
        }
        ;
        $db .= '</select>';
        return $db;
    }

    public function job_num_list(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start = date('Y-m-d', strtotime('-20 days'));
        $finish = date('Y-m-d');
        $machine_id = $request->machine_id;
        $shift = $request->shift;
        $data = DB::table("f_production_schedule_epicor('$start', '$finish', '$machine_id', '$shift')")->get();
        $db = '<select id="jo-select" class="selectize-jo">';
        foreach ($data as $row) {
            $db .= '<option value="' . $row->jo_num . '" ' . $row->selected . '>' . $row->jo_num . '</option>';
        }
        ;
        $db .= '</select>';
        return $db;
    }

    public function shift_list(Request $request)
    {
        $machine_id = $request->machine_id;
        $data = DB::table("log_header_machine_summary")->where("machine_id", "$machine_id")->where("is_active", 1)->get();
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $shift = $row->shift;
            }
            ;
        } else {
            $shift = 'Shift 1';
        }

        $db = '<select id="shift-select" class="selectize-shift">';
        $db .= '<option value="SHIFT 2" selected>Shift 2</option>';
        $db .= '<option value="SHIFT 1" selected>Shift 1</option>';

        $db .= '</select>';
        return $db;
    }

    public function get_avail_time(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $machine_id = ($request->machine_id === 'null' ? 1 : $request->machine_id);
        $data = $this->LogMachine->availTime($machine_id);
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $available_time = $row->available_time;
            }
            ;
        } else {
            $weekday = date("N", strtotime($date));
            if ($weekday == 5) {
                $available_time = 7;
            } else {
                $available_time = 8.25;
            }
        }
        $avail_1 = 8.25;
        $avail_2 = 7;
        $db = '<select id="avail-select" class="selectize-avail">';
        for ($i = 1; $i < 3; $i++) {
            $db .= '<option value="' . ${'avail_' . $i} . '" ' . (${'avail_' . $i} == $available_time ? 'selected' : '') . '>' . ${'avail_' . $i} . ' H</option>';
        }
        $db .= '</select>';
        return $db;
    }

    public function get_property(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $machine_id = ($request->machine_id === 'null' ? 1 : $request->machine_id);
        $data = DB::table("log_header_machine_summary as a")
            ->join('oee_log_machine AS b', function ($join) {
                $join->on('a.machine_id', 'b.machine_id');
                $join->on('a.production_date', 'a.production_date');
                $join->on('a.shift', 'a.shift');
            })
            ->where("a.machine_id", "$machine_id")
            ->where("a.is_active", 1)
            ->select('b.available_time', 'a.production_date', 'a.standard_sph', 'a.job_num')
            ->get();
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $standard_sph = (int) $row->standard_sph;
                $production_date = $row->production_date;
                $job_num = $row->job_num;
                $status = true;
            }
            ;
        } else {
            $standard_sph = 0;
            $production_date = $date;
            $job_num = null;
            $status = false;
        }

        return response()->json([
            'job_num' => $job_num,
            'standard_sph' => $standard_sph,
            'production_date' => $production_date,
            'status' => $status,
            'message' => 'Berhasil',
        ], 200);
    }

    public function get_profile_line(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_time = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $line_id = $request->header('line_id');
        $db = LogMachine::where('line_id', $line_id)->where('is_active', 1)->get();
        $results = [];
        foreach ($db as $row) {
            $machine_id = $row->machine_id;
            $mc_code = $row->machine_code;
            $plan = (int) $row->qty_plan;
            $last_counter = (int) $row->qty_actual;
            $job_num = $row->job_num;
            $condition_id = (int) $row->condition_id;

            $results[] = [
                'machine_id' => "$machine_id",
                'mc_code' => $mc_code,
                'plan' => $plan,
                'last_counter' => $last_counter,
                'job_num' => $job_num,
                'condition_id' => $condition_id
            ];
        }

        return response()->json([
            'line_id' => $line_id,
            'results' => $results,
        ], 200);

    }
    public function mass_update(Request $request)
    {
        $machine_data = $request->getContent();
        // Log::info($machine_data);
        $array_data = json_decode($machine_data, true);
        if (!is_array($array_data)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid JSON data.',
            ], 400);
        }
        $data_success = [];

        foreach ($array_data as $entry) {
            $id = null;
            $counter = 0;
            $tool = 0;

            if (is_array($entry)) {
                $id = $entry[0] ?? null;
                $counter = $entry[1] ?? 0;
                $tool = $entry[2] ?? 0;
            } elseif (is_object($entry)) {
                $id = $entry->machine_id ?? null;
                $counter = $entry->counter ?? 0;
                $tool = $entry->tool ?? 0;
            } else {
                $id = $entry['machine_id'] ?? null;
                $counter = $entry['counter'] ?? 0;
                $tool = $entry['tool'] ?? 0;
            }

            try {
                if ((int) $tool === 0) {
                    $update = $this->mass_update_stroke($id, $counter);
                } else {
                    $update = $this->mass_update_tool($id, $counter, $tool);
                }
                if ($update instanceof \Illuminate\Http\JsonResponse) {
                    $responseData = $update->getData(true);
                    $status = $responseData['status'] ?? false;
                } else {
                    $status = $update;
                }

                $data_success[] = [
                    'machine_id' => $id,
                    'counter' => $counter,
                    'tool' => $tool,
                    'status' => $status,
                ];
            } catch (\Exception $e) {
                $data_success[] = [
                    'machine_id' => $id,
                    'counter' => $counter,
                    'tool' => $tool,
                    'status' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json($data_success);
    }
    public function mass_update_stroke($id, $counter)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $date_time_sql = date('Y-m-d H:i:s');
        $machineID = LogMachine::find($id);
        $cycle_time = 0;
        if ($machineID->started_at) {
            $start = Carbon::parse($machineID->started_at);
            $finish = now();
            $interval = $start->diff($finish);
            $cycle_time = ($interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s) / 60;
            $operation_time = ($interval->days * 24 * 60 * 60 + $interval->h * 3600 + $interval->i * 60 + $interval->s) / 3600;
        }
        $ngRatio = 1 / 5;
        $qty_ng = floor($counter * $ngRatio);
        $mHeader = DB::table('log_header_machine')
            ->where('machine_id', $id)
            ->where('is_active', 1)
            ->where('condition_id', 1)
            ->first();
        if ($mHeader->part_no == '65701-TG1-T000-50-BL' || $mHeader->part_no == '71251-3M0-3000-BL' || $mHeader->part_no == '65166-TG4-U000-50-BL') {
            DB::table('log_header_machine')
                ->where('machine_id', $id)
                ->where('is_active', 1)
                ->where('condition_id', 1)
                ->where('qty_actual', '<', $counter)
                ->update([
                    'qty_actual' => $counter,
                    'qty_ok' => $counter - $qty_ng,
                    'qty_ng' => $qty_ng,
                    'operation_time' => $operation_time,
                    'current_gsph' => $operation_time > 0 ? ceil($counter / $operation_time) : 0
                ]);
        } else {
            DB::table('log_header_machine')
                ->where('machine_id', $id)
                ->where('is_active', 1)
                ->where('condition_id', 1)
                ->where('qty_actual', '<', $counter)
                ->update([
                    'qty_actual' => $counter,
                    'qty_ok' => $counter - $mHeader->qty_ng,
                    'operation_time' => $operation_time,
                    'current_gsph' => $operation_time > 0 ? ceil($counter / $operation_time) : 0
                ]);
        }
        $detail = DB::table('log_detail_machine')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('cut_off_date', $date_sql)
            ->where('shift', $machineID->shift)
            ->exists();
        if ($detail) {
            DB::table('log_detail_machine')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('cut_off_date', $date_sql)
                ->where('shift', $machineID->shift)
                ->where('counter', '<', $counter)
                ->update([
                    'counter' => $counter,
                    'cycle_time' => $cycle_time
                ]);
        } else {
            DB::table('log_detail_machine')
                ->insert([
                    'machine_id' => $id,
                    'summary_id' => $machineID->summary_id,
                    'job_num' => $machineID->job_num,
                    'cut_off_date' => $date_sql,
                    'counter' => $counter,
                    'created_at' => $date_time_sql,
                    'cycle_time' => $cycle_time,
                    'shift' => $machineID->shift
                ]);
        }
        $oee = DB::table('oee_log_machine')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('production_date', $date_sql)
            ->where('shift', $machineID->shift)
            ->exists();
        if ($oee) {
            DB::table('oee_log_machine')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('production_date', $date_sql)
                ->where('shift', $machineID->shift)
                ->where('total_qty', '<', $counter)
                ->update([
                    'total_qty' => $counter,
                    'operation_time' => $operation_time
                ]);
        } else {
            DB::table('oee_log_machine')
                ->insert([
                    'machine_id' => $id,
                    'shift' => $machineID->shift,
                    'production_date' => $date_sql,
                    'operation_time' => $operation_time,
                    'total_qty' => $counter,
                    'job_num' => $machineID->job_num
                ]);
        }
        $machineExists = LogMachine::where('machine_id', $id)
            ->where('is_active', 1)
            ->where('condition_id', 1)
            ->exists();
        if ($machineExists) {
            $lastGsphRecord = DB::table('gsph_record')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('cut_off', $date_sql)
                ->whereNull('tool_id')
                ->orderByDesc('cut_off_time')
                ->first();
            if (!$lastGsphRecord) {
                DB::table('gsph_record')->insert([
                    'machine_id' => $id,
                    'gsph' => $counter,
                    'qty_actual' => $counter,
                    'cut_off' => $date_sql,
                    'cut_off_time' => $date_time_sql,
                    'job_num' => $machineID->job_num,
                    'shift' => $machineID->shift
                ]);
            } else {
                $lastCutOffTime = Carbon::parse($lastGsphRecord->cut_off_time);
                $diffMinutes = $lastCutOffTime->diffInMinutes(now());
                if ($diffMinutes >= 60) {
                    if ($counter >= $lastGsphRecord->gsph) {
                        $gsphCounter = $counter - $lastGsphRecord->gsph;
                    } else {
                        $gsphCounter = $counter;
                    }
                    DB::table('gsph_record')->insert([
                        'machine_id' => $id,
                        'gsph' => $gsphCounter,
                        'qty_actual' => $counter,
                        'cut_off' => $date_sql,
                        'cut_off_time' => $date_time_sql,
                        'job_num' => $machineID->job_num,
                        'shift' => $machineID->shift
                    ]);
                }
            }
        }
        $logDowntime = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('shift', $machineID->shift)
            ->where('production_date', $date_sql)
            ->where('is_active', true)
            ->whereNull('tool_id')
            ->orderByDesc('started_at')
            ->first();
        if ($logDowntime) {
            $startDateTime = new DateTime($logDowntime->started_at);
            $finishDateTime = new DateTime($date_time_sql);
            $interval = $startDateTime->diff($finishDateTime);
            $minutesDiff = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
            $downtimeDuration = ($minutesDiff > 0 ? $minutesDiff / 60 : 0);
            DB::table('log_downtime')
                ->where('seq_id', $logDowntime->seq_id)
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('production_date', $date_sql)
                ->where('is_active', true)
                ->whereNull('tool_id')
                ->update([
                    'finished_at' => $date_time_sql,
                    'downtime' => $downtimeDuration,
                    'production_date' => $date_sql,
                    'is_active' => false
                ]);
            $logActivity = DB::table('log_activity')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('production_date', $date_sql)
                ->whereNull('tool_id')
                ->orderByDesc('start_date')
                ->first();
            if ($logActivity) {
                DB::table('log_activity')
                    ->where('machine_id', $id)
                    ->where('job_num', $machineID->job_num)
                    ->where('shift', $machineID->shift)
                    ->where('production_date', $date_sql)
                    ->whereNull('tool_id')
                    ->update([
                        'end_date' => $date_time_sql
                    ]);
            }
        }
        $goodProduct = $counter - $machineID->qty_ng;
        $oee_quality = round($goodProduct / $counter) * 100;
        $potongIstirahat = 0;
        $started_at = Carbon::parse($machineID->started_at);
        $finished_at = Carbon::parse(date('Y-m-d H:i:s'));
        $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
        $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
        if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
            $potongIstirahat += 2700; // 45 menit
        }
        $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
        $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
        if ($finished_at->lessThan($started_at)) {
            $finished_at->addDay();
        }
        if ($finished_at->greaterThan($istirahatMalamSelesai)) {
            $istirahatMalamMulai->addDay();
            $istirahatMalamSelesai->addDay();
        }
        if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
            $potongIstirahat += 2700; // 45 menit
        }
        $start = new DateTime($machineID->started_at);
        $finish = new DateTime($date_time_sql);
        $operation_time = $finish->getTimestamp() - $start->getTimestamp();
        $operation_time -= $potongIstirahat;
        $oprTimeMenit = $operation_time / 60;
        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->whereDate('production_date', $date_sql)
            ->where('shift', $machineID->shift)
            ->orderBy('started_at')
            ->get();
        $totalDowntimeMinutes = $downtimes->sum('downtime');
        $operTimeDT = $oprTimeMenit - $totalDowntimeMinutes;
        $standardCTMenit = 60 / $machineID->standard_sph;
        $oee_performance = $standardCTMenit * $machineID->qty_actual / $operTimeDT * 100;
        $oee_availability = $operTimeDT / $oprTimeMenit * 100;
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);

        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
        }
        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $labelHours = range(7, 16);
        } else {
            $labelHours = array_merge(range(16, 23), range(0, 7));
        }
        $gsphLabelStatic = collect($labelHours)->map(function ($hour) {
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':30';
        });
        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("DATEPART(HOUR, cut_off_time) as hour_int, SUM(gsph) as gsph")
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            // ->where('shift', $machineID->shift)
            ->where('cut_off', $date_sql)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();
        $gsphByHour = $gsphRecord->pluck('gsph', 'hour_int');
        $gsphValues = $gsphLabelStatic->map(function ($label) use ($gsphByHour) {
            $hour = intval(substr($label, 0, 2));
            return $gsphByHour->get($hour, 0);
        });
        $machineUpdate = LogMachine::find($id);
        if ($machineUpdate->part_no == '65701-TG1-T000-50-BL' || $machineUpdate->part_no == '71251-3M0-3000-BL' || $machineUpdate->part_no == '65166-TG4-U000-50-BL') {
            $qtyOK = $counter - $qty_ng;
        } else {
            $qtyOK = $counter - $machineUpdate->qty_ng;
        }
        $message = [
            'operation_time' => $machineUpdate->started_at . ' ' . now() . ' ' . $operation_time,
            'qty_actual' => $machineUpdate->qty_actual,
            'qty_ok' => $qtyOK,
            'qty_plan' => $machineUpdate->qty_plan,
            'qty_ng' => $machineUpdate->qty_ng,
            'act_gsph' => $machineUpdate->current_gsph,
            'act_cycletime' => 3600 / $machineUpdate->current_gsph,
            'ooe_quality' => $oee_quality,
            'oee_performance' => $oee_performance,
            'oee_availability' => $oee_availability,
            'oee_average' => ($oee_availability + $oee_performance + $oee_quality) / 3,
            'oprMenit' => $oprTimeMenit,
            'dtDuration' => $totalDowntimeMinutes,
            'oprTimeDT' => $operTimeDT,
            'ctMenit' => $standardCTMenit,
            'current_gsph' => $machineUpdate->current_gsph,
            'gsphLabel' => $gsphByHour,
            'gsphValues' => $gsphValues,
            'date_time' => $date_time_sql,
            'machine_id' => $id,
            'condition_id' => $machineUpdate->condition_id
        ];
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'update_stroke',
            'data' => [
                'message' => $message
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return true;
    }
    public function mass_update_tool($id, $counter, $tool)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $date_time_sql = date('Y-m-d H:i:s');
        // Log::info([
        //     'id' => $id,
        //     'count' => $counter,
        //     'tool' => $tool
        // ]);
        $machineID = DB::table('log_machine_tool')
            ->where('machine_id', $id)
            ->where('tool_id', $tool)
            ->where('condition_id', true)
            ->first();
        $cycle_time = 0;
        $operation_time = 1;
        if ($machineID->started_at) {
            $start = Carbon::parse($machineID->started_at);
            $finish = now();
            $interval = $start->diff($finish);
            $cycle_time = ($interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s) / 60;
            $operation_time = ($interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s) / 3600;
        }
        DB::table('log_machine_tool')
            ->where('machine_id', $id)
            ->where('tool_id', $tool)
            ->where('is_active', true)
            ->where('condition_id', true)
            ->where('qty_actual', '<', $counter)
            ->update([
                'qty_actual' => $counter,
                'operation_time' => $operation_time,
                'current_gsph' => $operation_time > 0 ? ceil($counter / $operation_time) : 0
            ]);
        $detail = DB::table('log_detail_machine')
            ->where('machine_id', $id)
            ->where('cut_off_date', $date_sql)
            ->where('tool_id', $tool)
            ->where('job_num', $machineID->job_num)
            ->where('shift', $machineID->shift)
            ->exists();
        if ($detail) {
            DB::table('log_detail_machine')
                ->where('machine_id', $id)
                ->where('cut_off_date', $date_sql)
                ->where('tool_id', $tool)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('counter', '<', $counter)
                ->update([
                    'counter' => $counter,
                    'cycle_time' => $cycle_time
                ]);
        } else {
            DB::table('log_detail_machine')
                ->insert([
                    'job_num' => $machineID->job_num,
                    'machine_id' => $machineID->machine_id,
                    'cut_off_date' => $machineID->production_date,
                    'counter' => $counter,
                    'created_at' => $date_time_sql,
                    'cycle_time' => $cycle_time,
                    'shift' => $machineID->shift,
                    'tool_id' => $tool
                ]);
        }
        $oee = DB::table('oee_log_machine')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('production_date', $date_sql)
            ->where('shift', $machineID->shift)
            ->where('tool_id', $tool)
            ->exists();
        if ($oee) {
            DB::table('oee_log_machine')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('production_date', $date_sql)
                ->where('shift', $machineID->shift)
                ->where('tool_id', $tool)
                ->where('total_qty', '<', $counter)
                ->update([
                    'total_qty' => $counter,
                    'operation_time' => $operation_time
                ]);
        } else {
            DB::table('oee_log_machine')
                ->insert([
                    'machine_id' => $id,
                    'shift' => $machineID->shift,
                    'production_date' => $date_sql,
                    'operation_time' => $operation_time,
                    'total_qty' => $counter,
                    'job_num' => $machineID->job_num,
                    'tool_id' => $tool
                ]);
        }
        $machineExists = DB::table('log_machine_tool')
            ->where('machine_id', $id)
            ->where('tool_id', $tool)
            ->where('is_active', true)
            ->where('condition_id', true)
            ->where('status_finish', false)
            ->exists();
        if ($machineExists) {
            $lastGsphRecord = DB::table('gsph_record')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('cut_off', $date_sql)
                ->where('tool_id', $tool)
                ->orderByDesc('cut_off_time')
                ->first();

            if (!$lastGsphRecord) {
                DB::table('gsph_record')->insert([
                    'machine_id' => $id,
                    'gsph' => $counter,
                    'qty_actual' => $counter,
                    'cut_off' => $date_sql,
                    'cut_off_time' => $date_time_sql,
                    'job_num' => $machineID->job_num,
                    'shift' => $machineID->shift,
                    'tool_id' => $tool
                ]);
            } else {
                $lastCutOffTime = Carbon::parse($lastGsphRecord->cut_off_time);
                $diffMinutes = $lastCutOffTime->diffInMinutes(now());
                if ($diffMinutes >= 30) {
                    $gsphCounter = $counter - $lastGsphRecord->gsph;
                    DB::table('gsph_record')->insert([
                        'machine_id' => $id,
                        'gsph' => $gsphCounter,
                        'qty_actual' => $counter,
                        'cut_off' => $date_sql,
                        'cut_off_time' => $date_time_sql,
                        'job_num' => $machineID->job_num,
                        'shift' => $machineID->shift,
                        'tool_id' => $tool
                    ]);
                }
            }
        }
        $logDowntime = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('shift', $machineID->shift)
            ->where('production_date', $date_sql)
            ->where('is_active', true)
            ->where('tool_id', $tool)
            ->first();
        if ($logDowntime) {
            $startDateTime = new DateTime($logDowntime->started_at);
            $finishDateTime = new DateTime($date_time_sql);
            $interval = $startDateTime->diff($finishDateTime);
            $minutesDiff = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
            $downtimeDuration = ($minutesDiff > 0 ? $minutesDiff / 60 : 0);
            DB::table('log_downtime')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('production_date', $date_sql)
                ->where('is_active', true)
                ->where('tool_id', $tool)
                ->update([
                    'finished_at' => $date_time_sql,
                    'downtime' => $downtimeDuration,
                    'production_date' => $date_sql,
                    'is_active' => false
                ]);
            DB::table('log_activity')
                ->where('machine_id', $id)
                ->where('job_num', $machineID->job_num)
                ->where('shift', $machineID->shift)
                ->where('production_date', $date_sql)
                ->where('tool_id', $tool)
                ->update([
                    'end_date' => $date_time_sql
                ]);
        }
        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->whereDate('production_date', $date_sql)
            ->where('shift', $machineID->shift)
            ->where('tool_id', $tool)
            ->where('is_active', true)
            ->get();

        $totalDowntimeMinutes = $downtimes->sum('downtime');
        $oprTimeMenit = $operation_time * 60;
        $operTimeDT = max($oprTimeMenit - $totalDowntimeMinutes, 0.0001);
        $goodProduct = max($counter - $machineID->qty_ng, 0);
        $oee_quality = $counter > 0 ? round($goodProduct / $counter * 100, 2) : 0;
        $standardCTMenit = $machineID->standard_sph > 0 ? 60 / $machineID->standard_sph : 0;
        $oee_performance = $operTimeDT > 0 ? $standardCTMenit * $counter / $operTimeDT * 100 : 0;
        $oee_availability = $oprTimeMenit > 0 ? $operTimeDT / $oprTimeMenit * 100 : 0;
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);
        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
            $labelHours = range(7, 16);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
            $labelHours = array_merge(range(16, 23), range(0, 7));
        }
        $gsphLabelStatic = collect($labelHours)->map(fn($hour) => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':30');

        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("DATEPART(HOUR, cut_off_time) as hour_int, SUM(gsph) as gsph")
            ->where('machine_id', $id)
            ->where('job_num', $machineID->job_num)
            ->where('shift', $machineID->shift)
            ->where('cut_off', $date_sql)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->where('tool_id', $tool)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();

        $gsphByHour = $gsphRecord->pluck('gsph', 'hour_int');
        $gsphValues = $gsphLabelStatic->map(fn($label) => $gsphByHour->get(intval(substr($label, 0, 2)), 0));
        $machineUpdate = DB::table('log_machine_tool')
            ->where('machine_id', $id)
            ->where('tool_id', $tool)
            ->where('is_active', true)
            ->where('condition_id', true)
            ->first();
        $message = [
            'operation_time' => $machineUpdate->started_at . ' ' . now() . ' ' . $operation_time,
            'qty_actual' => $machineUpdate->qty_actual,
            'qty_ng' => $machineUpdate->qty_ng,
            'qty_plan' => $machineUpdate->qty_plan,
            'act_gsph' => $machineUpdate->current_gsph,
            'act_cycletime' => 3600 / $machineUpdate->current_gsph,
            'ooe_quality' => $oee_quality,
            'oee_performance' => $oee_performance,
            'oee-availability' => $oee_availability,
            'oprMenit' => $oprTimeMenit,
            'dtDuration' => $totalDowntimeMinutes,
            'oprTimeDT' => $operTimeDT,
            'ctMenit' => $standardCTMenit,
            'current_gsph' => $machineUpdate->current_gsph,
            'gsphLabel' => $gsphByHour,
            'gsphValues' => $gsphValues,
            'date_time' => $date_time_sql,
            'machine_id' => $id,
            'tool_id' => $machineUpdate->tool_id,
            'condition_id' => $machineUpdate->condition_id
        ];
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'update_stroke_tool',
            'data' => [
                'message' => $message
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return true;
    }
    public function getCountDoc(Request $request)
    {
        $monthId = $request->input('month_id');
        $startDate = date('Y-m-01', strtotime($monthId)); // 2025-01-01
        $endDate = date('Y-m-t', strtotime($monthId));   // 2025-01-31

        try {
            $totalOpenDoc = DB::connection('sqlsrv4')->table('MaintReq AS a')
                ->whereBetween('RequestDt', [$startDate, $endDate])
                ->where('ProgressStatus_c', '<>', 'D')
                ->where('ProgressStatus_c', '<>', '')
                ->where('ReqStatus', '<>', 'Aprv')
                ->count();
            $totalIssueDoc = DB::connection('sqlsrv4')->table('MaintReq AS a')
                ->where(function ($query) {
                    $query->where('a.ProgressStatus_c', '<>', 'D');
                    $query->where('a.ProgressStatus_c', '<>', '');
                    $query->orWhere('a.ReqStatus', 'Cmp');
                })
                ->whereBetween('a.RequestDt', [$startDate, $endDate])
                ->count();
            $totalCloseDoc = DB::connection('sqlsrv4')
                ->table('MaintReq AS a')
                ->whereBetween('RequestDt', [$startDate, $endDate])
                ->where('ReqStatus', 'Cmp')
                ->count();

            return response()->json([
                'total_open_doc' => $totalOpenDoc,
                'total_issue_doc' => $totalIssueDoc,
                'total_close_doc' => $totalCloseDoc,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPPMList(Request $request)
    {
        $monthId = $request->input('month_id');
        $startDate = date('Y-m-01', strtotime($monthId)); // 2025-01-01
        $endDate = date('Y-m-t', strtotime($monthId));   // 2025-01-31


        $db = DB::connection('sqlsrv4')->table('MaintReq AS a')
            ->leftJoin('Erp.Equip AS b', 'a.EquipID', 'b.EquipID')
            ->leftJoin('Erp.UserFile AS c', 'a.Requestor', 'c.DcdUserID')
            ->where(function ($query) {
                $query->where('a.ProgressStatus_c', '<>', 'D');
                $query->where('a.ProgressStatus_c', '<>', '');
                $query->orWhere('a.ReqStatus', 'Cmp');
            })
            ->whereBetween('a.RequestDt', [$startDate, $endDate])
            ->select([
                DB::raw('a.RequestDt AS doc_date'),
                DB::raw('a.ReqStatus AS doc_status'),
                DB::raw('a.ReqID AS ppm_num'),
                DB::raw('b.Description AS mc_num'),
                DB::raw('c.Name AS requested_by'),
                DB::raw('a.ResDesc AS remark'),
                DB::raw('a.ProgressStatus_c AS status')
            ]);
        $search = $request->input('search');
        $columns = array(
            0 => 'a.ReqID',
            1 => 'a.ReqID',
            2 => 'b.Description',
            3 => 'a.Requestor',
            4 => 'a.RequestDt',
            5 => 'a.ProgressStatus_c'
        );
        $totalData = $db->get()->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = ($request->input('order.0.column') == 0 ? $columns[1] : $columns[$request->input('order.0.column')]);
        $dir = ($request->input('order.0.column') == 0 ? 'desc' : $request->input('order.0.dir'));
        if (empty($search)) {
            $posts = $db
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $posts = $db
                ->where(function ($query) use ($search) {
                    $query->where('a.ReqID', 'LIKE', "%$search%");
                    $query->orWhere('a.EquipID', 'LIKE', "%$search%");
                    $query->orWhere('a.ResDesc', 'LIKE', "%$search%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = $db
                ->where(function ($query) use ($search) {
                    $query->where('a.ReqID', 'LIKE', "%$search%");
                    $query->orWhere('a.EquipID', 'LIKE', "%$search%");
                    $query->orWhere('a.ResDesc', 'LIKE', "%$search%");
                })->get()->count();
        }
        $data = array();
        if (!empty($posts)) {
            $no = $start;
            foreach ($posts as $post) {
                $no++;
                $ppm_num = "'" . $post->ppm_num . "'";
                $status = 'Draft';
                $bg = 'warning';
                if ($post->status == 'D') {
                    $status = 'Draft';
                    $bg = 'danger';
                } else if ($post->status == 'O') {
                    $status = 'Open';
                    $bg = 'danger';
                } else if ($post->status == 'V') {
                    $status = 'Visit';
                    $bg = 'primary';
                } else if ($post->status == 'R') {
                    $status = 'Receipt';
                    $bg = 'warning';
                }

                if ($post->doc_status == 'Cmp') {
                    $status = 'Close';
                    $bg = 'success';
                }
                $button = '<a onclick=""><div style="cursor: pointer;" class="shrink-0 bg-' . $bg . ' text-white rounded-sm w-15 h-6 flex justify-center items-center dark:bg-' . $bg . ' dark:text-white ">
                                        <div class="align-center text-xs">
                                            <span id="total_open_doc">' . $status . '</span>
                                        </div>
                                    </div></a>';

                $nestedData['no'] = $no;
                $nestedData['ppm_num'] = $post->ppm_num;
                $nestedData['mc_num'] = $post->mc_num;
                $nestedData['requested_by'] = $post->requested_by;
                $nestedData['doc_date'] = $post->doc_date;
                $nestedData['status'] = $button;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
    // Table default machine running
    public function table_page($id, Request $request)
    {
        $line_id = match ($id) {
            'A1' => 'STP-002',
            'A2' => 'STP-003',
            'A6' => 'STP-007',
            'RBT-5H45' => 'ASSY-002',
            'RBT-5J45' => 'ASSY-014',
            'SSW' => 'ASSY-009',
            default => null,
        };
        if ($line_id === null) {
            return response()->json([
                'error' => 'Invalid ID'
            ], 400);
        }
        if (!$request->ajax()) {
            abort(404);
        }
        $machineIds = [
            'RSW-5H45-07',
            'RSW-5H45-08',
            'RSW-5H45-09',
            'RSW-5H45-10',
            'RSW-5H45-11',
            'RSW-5H45-12',
        ];
        $query = $this->LogMachine->queryTablePage($id, $line_id, $machineIds);
        return DataTables::of($query)
            ->editColumn('job_num', function ($row) {
                if ($row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = $row->job_num;
                }
                return $data;
            })
            ->addColumn('model', function ($row) use ($id) {
                if ($id === 'RBT-5H45') {
                    return $row->model ?? '-';
                }
                return null;
            })
            ->editColumn('part_no', function ($row) {
                if ($row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = $row->part_no;
                }
                return $data;
            })
            ->editColumn('qty_plan', function ($row) {
                return $row->qty_plan;
            })
            ->editColumn('qty_actual', function ($row) {
                if ($row->qty_plan == 0) {
                    $data = 0;
                } else {
                    $data = $row->qty_actual;
                }
                return $data;
            })
            ->addColumn('qty_ok', function ($row) {
                return number_format($row->qty_ok, 0, '', '.') ?? '-';
            })
            ->editColumn('started_at', function ($row) {
                if (!$row->job_num || $row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = Carbon::parse($row->started_at)->format('H:i:s');
                }
                return $data;
            })
            ->editColumn('finished_at', function ($row) use ($id) {
                if ($id === 'RBT-5H45') {
                    if ($row->status_finish = false || $row->qty_actual <= $row->qty_plan || !$row->started_at) {
                        $data = '-';
                    } else {
                        $data = Carbon::parse($row->finished_at)->format('H:i:s');
                    }
                } else {
                    if (!$row->started_at || $row->status_finish = false || $row->qty_actual <= $row->qty_plan) {
                        $data = '-';
                    } else {
                        $data = Carbon::parse($row->finished_at)->format('H:i:s');
                    }
                }

                return $data;
            })
            ->editColumn('operation_time', function ($row) {
                if (
                    !$row->started_at ||
                    !$row->operation_time ||
                    $row->operation_time == 0 ||
                    $row->qty_actual == 0
                ) {
                    return '-';
                }
                $hours = floor($row->operation_time);
                $minutes = floor(($row->operation_time - $hours) * 60);
                return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
            })
            ->addColumn('planvsact', function ($row) {
                $plan = floatval($row->qty_plan);
                $actual = floatval($row->qty_actual);
                $remaining = max($plan - $actual, 0);
                $percentage = $plan > 0 ? ($actual / $plan) * 100 : 0;
                return intval($percentage) . '%';
            })
            ->rawColumns(['planvsact'])
            ->make(true);
    }
    //table Job Pending
    public function table_page_pending($lineId)
    {
        // dd($lineId);
        $sql = $this->LogMachine->queryTablePending();
        // $bindings = [$lineId];
        $bindings = [trim($lineId) . '%'];

        $results = DB::connection('sqlsrv4')->select($sql, $bindings);
        $collection = collect($results);

        return DataTables::of($collection)
            ->make(true);
    }
    //Table job history atau yang sudah finish
    public function table_page_history($id, Request $request)
    {
        $line_id = match ($id) {
            'A1' => 'STP-002',
            'A2' => 'STP-003',
            'A6' => 'STP-007',
            'RBT-5H45' => 'ASSY-002',
            'RBT-5J45' => 'ASSY-014',
            'SSW' => 'ASSY-009',
            default => null,
        };

        if ($line_id === null) {
            return response()->json([
                'error' => 'Invalid ID'
            ], 400);
        }

        if (!$request->ajax()) {
            abort(404);
        }

        $query = DB::table('history_header_machine')
            ->where('category_line_id', $line_id);

        if ($request->filled('start') && $request->filled('end')) {
            $start = $request->input('start');
            $end = $request->input('end');
            $query->whereBetween(DB::raw('CAST(production_date AS DATE)'), [$start, $end]);
        }

        return DataTables::of($query)
            ->editColumn('machine_id', function ($row) {
                if (empty($row->tool_id)) {
                    $data = $row->machine_id;
                } else {
                    $data = $row->machine_id . '/' . $row->tool_id;
                }
                return $data;
            })
            ->editColumn('qty_plan', function ($row) {
                return $row->qty_plan;
            })
            ->editColumn('qty_actual', function ($row) {
                return $row->qty_actual;
            })
            ->editColumn('finished_at', function ($row) {
                return $row->started_at ? $row->finished_at : '-';
            })
            ->addColumn('planvsact', function ($row) {
                $plan = floatval($row->qty_plan);
                $actual = floatval($row->qty_actual);
                $percentage = $plan > 0 ? ($actual / $plan) * 100 : 0;
                return intval($percentage) . '%';
            })
            ->rawColumns(['planvsact'])
            ->make(true);
    }

    //Fungsi Export Tabel history
    public function exportHistoryTable(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $id = $request->lineID;
        //Download history_header_machine
        return Excel::download(new HistoryLogMachineExport($id, $start, $end), 'IoT_export_' . now()->format('Ymd_His') . '.xlsx');
    }
    //Fungsi dashboard versi 2
    public function dashboard_v2($id)
    {
        // dd($id);
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $machine = LogMachine::find($id);
        // dd($machine);
        $oee = DB::table('oee_log_machine')
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->get();
        // dd($oee);
        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machine->job_num)
            ->whereDate('production_date', $date_sql)
            ->where('shift', $machine->shift)
            ->orderBy('started_at')
            ->get();
        // dd($downtimes);
        $totalDowntimeMinutes = $downtimes->sum('downtime');
        if ($machine) {
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;
            $started_at = new DateTime($machine->started_at);
            $finished_at = new DateTime(date('Y-m-d H:i:s'));
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();
            if ($operation_time <= 0) {
                $oee_performance = 0;
                $oee_availability = 0;
            } else {
                $potongIstirahat = 0;
                $started_at = Carbon::parse($machine->started_at);
                $finished_at = Carbon::parse(date('Y-m-d H:i:s'));
                $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
                $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
                if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                    $potongIstirahat += 2700;
                }
                $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
                $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
                if ($finished_at->lessThan($started_at)) {
                    $finished_at->addDay();
                }
                if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                    $istirahatMalamMulai->addDay();
                    $istirahatMalamSelesai->addDay();
                }
                if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                    $potongIstirahat += 2700;
                }
                $operation_time -= $potongIstirahat;
                $operasi_time = $operation_time / 60;
                $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
                // $standardCT = 60 / $machine->standard_sph
                $standardCT = $machine->standard_sph > 0
                    ? 60 / $machine->standard_sph
                    : 0;
                // $actualCT = $waktuOperasi / $machine->qty_actual;
                $actualCT = $machine->qty_actual > 0
                    ? $waktuOperasi / $machine->qty_actual
                    : 0;

                $stdQTY_Actual = $standardCT * $waktuOperasi;
                // $oee_performance = $standardCT * $machine->qty_actual / $waktuOperasi * 100;
                // $oee_availability = $waktuOperasi / $operasi_time * 100;
                $oee_performance = ($waktuOperasi > 0 && $machine->qty_actual > 0)
                    ? ($standardCT * $machine->qty_actual / $waktuOperasi) * 100
                    : 0;

                $oee_availability = $operasi_time > 0
                    ? ($waktuOperasi / $operasi_time) * 100
                    : 0;
            }
        } else {
            $oee_quality = 100;
            $oee_availability = 0;
            $oee_performance = 0;
        }

        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine->machine_id)
            ->where('d.job_num', $machine->job_num)
            ->where('d.production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        $downtimeFive = $downtime->take(5);
        $downtimeOthers = $downtime->slice(5)->sum('total_downtime');

        if ($downtimeOthers > 0) {
            $downtimeFive->push((object) [
                'name' => 'Others',
                'total_downtime' => $downtimeOthers
            ]);
        }

        $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
        $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();

        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine->machine_id)
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->limit(5)
            ->get();
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);

        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
        }
        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $labelHours = range(7, 16);
        } else {
            $labelHours = array_merge(range(16, 23), range(0, 7));
        }

        $gsphLabelStatic = collect($labelHours)->map(function ($hour) {
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':30';
        });
        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("DATEPART(HOUR, cut_off_time) as hour_int, SUM(gsph) as gsph")
            ->where('machine_id', $machine->machine_id)
            ->where('job_num', $machine->job_num)
            ->where('shift', $machine->shift)
            ->where('cut_off', $date_sql)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();
        $gsphByHour = $gsphRecord->pluck('gsph', 'hour_int');
        $gsphValues = $gsphLabelStatic->map(function ($label) use ($gsphByHour) {
            $hour = intval(substr($label, 0, 2));
            return $gsphByHour->get($hour, 0);
        });
        if ($oee_performance >= 100) {
            $oee_performance = 100;
        }
        if ($oee_availability >= 100) {
            $oee_availability = 100;
        }
        if ($oee_quality >= 100) {
            $oee_quality = 100;
        }
        return response()->json([
            'machine' => $machine,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabels' => $gsphByHour,
            'gsphValues' => $gsphValues,
            'oee' => $oee->first(),
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'activity' => $log_activity,
            'total_dt' => $totalDowntimeMinutes,
            'opr_time' => $operation_time / 60,
            'opr_dt' => $waktuOperasi,
            'ct' => $standardCT
        ]);
    }
    //Fungsi dashboard history
    public function historyDashboard(Request $request)
    {
        $machine_id = $request->machine_id;
        $job_num = $request->job_num;
        $production_date = $request->production_date;
        $shift = 'SHIFT ' . $request->shift;
        $machine = DB::table('history_header_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->first();
        $oee = DB::table('oee_log_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('shift', $shift)
            ->get();
        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->whereDate('production_date', $production_date)
            ->where('shift', $shift)
            ->orderBy('started_at')
            ->get();
        $totalDowntimeMinutes = $downtimes->sum('downtime');
        if ($machine) {
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;
            $started_at = new DateTime($machine->started_at);
            $finished_at = new DateTime($machine->finished_at);
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();
            $potongIstirahat = 0;
            $started_at = Carbon::parse($machine->started_at);
            $finished_at = Carbon::parse($machine->finished_at);
            $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
            $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
            if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                $potongIstirahat += 2700; // 45 menit
            }
            $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
            $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
            if ($finished_at->lessThan($started_at)) {
                $finished_at->addDay();
            }
            if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                $istirahatMalamMulai->addDay();
                $istirahatMalamSelesai->addDay();
            }
            if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                $potongIstirahat += 2700; // 45 menit
            }
            // Oee Performance
            $operation_time -= $potongIstirahat; // Jika di potong istirahat
            $operasi_time = $operation_time / 60;
            $waktuOperasi = $operasi_time - $totalDowntimeMinutes; // dalam menit
            $standardCT = 60 / $machine->standard_sph; // Menit
            $actualCT = $waktuOperasi / $machine->qty_actual;
            $stdQTY_Actual = $standardCT * $waktuOperasi;
            $oee_performance = $standardCT * $machine->qty_actual / $waktuOperasi * 100;
            $oee_availability = $waktuOperasi / $operasi_time * 100;
        } else {
            $oee_quality = 100;
            $oee_availability = 0;
            $oee_performance = 0;
        }

        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine_id)
            ->where('d.job_num', $job_num)
            ->where('d.production_date', $production_date)
            ->where('shift', $shift)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        $downtimeFive = $downtime->take(5);
        $downtimeOthers = $downtime->slice(5)->sum('total_downtime');

        if ($downtimeOthers > 0) {
            $downtimeFive->push((object) [
                'name' => 'Others',
                'total_downtime' => $downtimeOthers
            ]);
        }

        $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
        $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();

        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('shift', $shift)
            ->limit(5)
            ->get();
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);

        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
        }
        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $labelHours = range(7, 16);
        } else {
            $labelHours = array_merge(range(16, 23), range(0, 7));
        }

        $gsphLabelStatic = collect($labelHours)->map(function ($hour) {
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        });
        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("DATEPART(HOUR, cut_off_time) as hour_int, SUM(gsph) as gsph")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('cut_off', $production_date)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();
        $gsphByHour = $gsphRecord->pluck('gsph', 'hour_int');
        $gsphValues = $gsphLabelStatic->map(function ($label) use ($gsphByHour) {
            $hour = intval(substr($label, 0, 2));
            return $gsphByHour->get($hour, 0);
        });
        return response()->json([
            'machine' => $machine,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabels' => $gsphByHour,
            'gsphValues' => $gsphValues,
            'oee' => $oee->first(),
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'activity' => $log_activity,
            'total_dt' => $totalDowntimeMinutes,
            'opr_time' => $operasi_time,
            'opr_dt' => $waktuOperasi,
            'ct' => $standardCT
        ]);
    }
    public function historyDashboardTool(Request $request)
    {
        $machine_id = $request->machine_id;
        $job_num = $request->job_num;
        $production_date = $request->production_date;
        $shift = 'SHIFT ' . $request->shift;
        $tool_id = $request->tool_id;
        $machine = DB::table('history_header_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->where('tool_id', $tool_id)
            ->first();
        $oee = DB::table('oee_log_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('shift', $shift)
            ->where('tool_id', $tool_id)
            ->get();
        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->whereDate('production_date', $production_date)
            ->where('shift', $shift)
            ->where('tool_id', $tool_id)
            ->orderBy('started_at')
            ->get();
        $totalDowntimeMinutes = $downtimes->sum('downtime');
        if ($machine) {
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;
            $started_at = new DateTime($machine->started_at);
            $finished_at = new DateTime($machine->finished_at);
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();
            $potongIstirahat = 0;
            $started_at = Carbon::parse($machine->started_at);
            $finished_at = Carbon::parse($machine->finished_at);
            $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
            $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
            if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                $potongIstirahat += 2700; // 45 menit
            }
            $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
            $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
            if ($finished_at->lessThan($started_at)) {
                $finished_at->addDay();
            }
            if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                $istirahatMalamMulai->addDay();
                $istirahatMalamSelesai->addDay();
            }
            if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                $potongIstirahat += 2700; // 45 menit
            }
            // Oee Performance
            $operation_time -= $potongIstirahat; // Jika di potong istirahat
            $operasi_time = $operation_time / 60;
            $waktuOperasi = $operasi_time - $totalDowntimeMinutes; // dalam menit
            $standardCT = 60 / $machine->standard_sph; // Menit
            $actualCT = $waktuOperasi / $machine->qty_actual;
            $stdQTY_Actual = $standardCT * $waktuOperasi;
            $oee_performance = $standardCT * $machine->qty_actual / $waktuOperasi * 100;
            $oee_availability = $waktuOperasi / $operasi_time * 100;
        } else {
            $oee_quality = 100;
            $oee_availability = 0;
            $oee_performance = 0;
        }

        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine_id)
            ->where('d.job_num', $job_num)
            ->where('d.production_date', $production_date)
            ->where('shift', $shift)
            ->where('tool_id', $tool_id)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        $downtimeFive = $downtime->take(5);
        $downtimeOthers = $downtime->slice(5)->sum('total_downtime');

        if ($downtimeOthers > 0) {
            $downtimeFive->push((object) [
                'name' => 'Others',
                'total_downtime' => $downtimeOthers
            ]);
        }

        $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
        $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();

        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('shift', $shift)
            ->where('tool_id', $tool_id)
            ->limit(5)
            ->get();
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);

        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
        }
        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $labelHours = range(7, 16);
        } else {
            $labelHours = array_merge(range(16, 23), range(0, 7));
        }

        $gsphLabelStatic = collect($labelHours)->map(function ($hour) {
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        });
        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("DATEPART(HOUR, cut_off_time) as hour_int, SUM(gsph) as gsph")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('cut_off', $production_date)
            ->where('tool_id', $tool_id)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();
        $gsphByHour = $gsphRecord->pluck('gsph', 'hour_int');
        $gsphValues = $gsphLabelStatic->map(function ($label) use ($gsphByHour) {
            $hour = intval(substr($label, 0, 2));
            return $gsphByHour->get($hour, 0);
        });
        return response()->json([
            'machine' => $machine,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabels' => $gsphByHour,
            'gsphValues' => $gsphValues,
            'oee' => $oee->first(),
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'activity' => $log_activity,
            'total_dt' => $totalDowntimeMinutes,
            'opr_time' => $operasi_time,
            'opr_dt' => $waktuOperasi,
            'ct' => $standardCT
        ]);
    }
    public function dashboard_summary($id)
    {
        $line_id = match ($id) {
            'A1' => 'STP-002',
            'A2' => 'STP-003',
            'A6' => 'STP-007',
            'RBT-5H45' => 'ASSY-002',
            'RBT-5J45' => 'ASSY-014',
            'SSW' => 'ASSY-009',
            default => null
        };

        if (!$line_id) {
            return response()->json(['error' => 'Invalid ID'], 400);
        }

        $now = Carbon::now('Asia/Jakarta');
        $shift = ($now->hour >= 7 && $now->hour < 17) ? 'SHIFT 2' : 'SHIFT 1';

        $machines = DB::table("history_header_machine")
            ->where('category_line_id', $line_id)
            ->where('production_date', $now->format('Y-m-d'))
            ->get();

        if ($machines->isEmpty()) {
            $machines = LogMachine::where('category_line_id', $line_id)
                ->where('production_date', $now->format('Y-m-d'))
                ->get();
        }

        $allDowntime = collect();
        $allGsph = collect();
        $totalQtyActual = 0;
        $totalQtyPlan = 0;
        $sumOeePerformance = 0;
        $sumOeeAvailability = 0;
        $sumOeeQuality = 0;
        $countMachines = $machines->count();

        $lastOperationTime = 0;
        $lastWaktuOperasi = 0;
        $lastStandardCT = 0;
        $lastTotalDowntime = 0;

        foreach ($machines as $mac) {
            $gsphPerHour = DB::table('gsph_record')
                ->selectRaw("RIGHT('0' + CAST(DATEPART(HOUR, cut_off_time) AS VARCHAR), 2) + ':30' as hour_label, SUM(gsph) as gsph")
                ->where('machine_id', $mac->machine_id)
                ->where('shift', $shift)
                ->where('cut_off', $now->format('Y-m-d'))
                ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
                ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
                ->get();
            $allGsph = $allGsph->merge($gsphPerHour);
            $downtime = DB::table('log_downtime as d')
                ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
                ->where('d.machine_id', $mac->machine_id)
                ->whereDate('d.production_date', $mac->production_date)
                ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
                ->groupBy('l.name')
                ->get();
            $allDowntime = $allDowntime->merge($downtime);
            $totalDowntimeMinutes = DB::table('log_downtime')
                ->where('machine_id', $mac->machine_id)
                ->where('production_date', $now->format('Y-m-d'))
                ->where('shift', $shift)
                ->sum('downtime');
            $oeeQuality = ($mac->qty_actual > 0 && $mac->qty_ng > 0)
                ? 100 - ceil(($mac->qty_ng / $mac->qty_actual) * 100)
                : 100;
            $started_at = Carbon::parse($mac->started_at);
            $finished_at = Carbon::parse(date('Y-m-d H:i:s'));

            $operation_time = $finished_at->timestamp - $started_at->timestamp;

            if ($operation_time <= 0) {
                $oeePerformance = 0;
                $oeeAvailability = 0;
            } else {
                $potongIstirahat = 0;
                $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
                $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
                if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                    $potongIstirahat += 2700; // 45 menit
                }
                $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
                $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
                if ($finished_at->lessThan($started_at)) {
                    $finished_at->addDay();
                }
                if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                    $istirahatMalamMulai->addDay();
                    $istirahatMalamSelesai->addDay();
                }
                if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                    $potongIstirahat += 2700; // 45 menit
                }
                $operation_time -= $potongIstirahat;
                $operasi_time = $operation_time / 60;
                $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
                $standardCT = 60 / $mac->standard_sph;
                $oeePerformance = $waktuOperasi > 0 ? ($standardCT * $mac->qty_actual / $waktuOperasi * 100) : 0;
                $oeeAvailability = $operasi_time > 0 ? ($waktuOperasi / $operasi_time * 100) : 0;
            }
            $oeePerformance = min($oeePerformance, 100);
            $oeeAvailability = min($oeeAvailability, 100);
            $oeeQuality = min($oeeQuality, 100);
            $totalQtyActual += $mac->qty_actual;
            $totalQtyPlan += $mac->qty_plan;
            $sumOeePerformance += $oeePerformance;
            $sumOeeAvailability += $oeeAvailability;
            $sumOeeQuality += $oeeQuality;
            $lastOperationTime = $operation_time / 60;
            $lastWaktuOperasi = $waktuOperasi;
            $lastStandardCT = $mac->standard_sph > 0 ? (60 / $mac->standard_sph) : 0;
            $lastTotalDowntime = $totalDowntimeMinutes;
        }
        $groupedDowntime = $allDowntime
            ->groupBy('name')
            ->map(fn($items) => $items->sum('total_downtime'))
            ->sortDesc();

        $topFour = $groupedDowntime->take(4);
        $othersTotal = $groupedDowntime->slice(4)->sum();
        if ($othersTotal > 0) {
            $topFour = $topFour->put('Others', $othersTotal);
        }

        $downtimeChartLabels = $topFour->keys()->toArray();
        $downtimeChartValues = $topFour->values()->toArray();

        // Group GSPH
        $groupedGsph = $allGsph->groupBy('hour_label')
            ->map(fn($rows) => $rows->sum('gsph'))
            ->sortKeys();

        $gsphLabel = $groupedGsph->keys()->toArray();
        $gsphValues = $groupedGsph->values()->toArray();
        $oee_availability = $countMachines ? round($sumOeeAvailability / $countMachines, 2) : 0;
        $oee_performance = $countMachines ? round($sumOeePerformance / $countMachines, 2) : 0;
        $oee_quality = $countMachines ? round($sumOeeQuality / $countMachines, 2) : 100;

        return response()->json([
            'qty_actual' => $totalQtyActual,
            'qty_plan' => $totalQtyPlan,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabel' => $gsphLabel,
            'gsphValues' => $gsphValues,
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'oprTime' => $lastOperationTime,
            'oprDT' => $lastWaktuOperasi,
            'ct' => $lastStandardCT,
            'totalDT' => $lastTotalDowntime
        ]);
    }
    //Fungsi kategori stp atau assy
    public function getCategory($id)
    {
        //Tampilkan log_header_machine_summary
        // dd($id);
        $data = $this->LogMachine->getCategory($id);
        return response()->json([
            'message' => $data
        ]);
    }
    public function shiftJo(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $shift = $request->shift;

        $dataJob = $this->LogMachine->shiftJo($shift);
        return response()->json([
            'data' => $dataJob
        ]);
    }
    //Get machine
    public function getMachine(Request $request)
    {
        $search = $request->input('q', '');
        $page = (int) $request->input('page', 1);
        $perPage = 10;

        $machineList = Cache::remember('machine_list', 600, function () {
            //Tampilkan log_header_machine yang aktif
            return DB::table('log_header_machine')->select('machine_id')->where('is_active', 1)->get()->toArray();
        });
        if ($search) {
            $machineList = array_filter($machineList, function ($machine) use ($search) {
                return stripos($machine->machine_id, $search) !== false;
            });
        }

        $total = count($machineList);
        $machineList = array_values($machineList);

        $pagedMachines = array_slice($machineList, ($page - 1) * $perPage, $perPage);

        $results = array_map(function ($machine) {
            return [
                'id' => $machine->machine_id,
                'text' => $machine->machine_id
            ];
        }, $pagedMachines);

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $total > $page * $perPage
            ]
        ]);
    }
    //Fungsi menampilkan karyawan
    public function getEmployee(Request $request)
    {
        $search = $request->input('q', '');
        $page = (int) $request->input('page', 1);
        $perPage = 10;
        $employees = Cache::remember('employees_list', 600, function () {
            //API Epicor getEmployee
            $url = config('services.epicor_app.url');
            $empApi = Http::withoutVerifying()->post($url . '/Labor/GetEmployee');
            return $empApi->json();
        });
        if ($search) {
            $employees = array_filter($employees, function ($emp) use ($search) {
                return stripos($emp['name'], $search) !== false || stripos($emp['empID'], $search) !== false;
            });
        }

        $total = count($employees);
        $employees = array_slice($employees, ($page - 1) * $perPage, $perPage);
        $results = array_map(function ($emp) {
            return [
                'id' => $emp['empID'] . ' - ' . $emp['name'],
                'text' => $emp['empID'] . ' - ' . $emp['name']
            ];
        }, $employees);

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $total > $page * $perPage
            ]
        ]);
    }
    public function getEmpSelect(Request $request)
    {
        $search = $request->input('q', '');
        $page = (int) $request->input('page', 1);
        $perPage = 10;
        $employees = Cache::remember('employees_list', 600, function () {
            //API Epicor getEmployee
            $url = config('services.epicor_app.url');
            $empApi = Http::withoutVerifying()->post($url . '/Labor/GetEmployee');
            return $empApi->json();
        });
        if ($search) {
            $employees = array_filter($employees, function ($emp) use ($search) {
                return stripos($emp['name'], $search) !== false || stripos($emp['empID'], $search) !== false;
            });
        }

        $total = count($employees);
        $employees = array_slice($employees, ($page - 1) * $perPage, $perPage);
        $results = array_map(function ($emp) {
            return [
                'id' => $emp['empID'],
                'text' => $emp['empID'] . ' - ' . $emp['name']
            ];
        }, $employees);

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $total > $page * $perPage
            ]
        ]);
    }
    //JobEntry
    public function JobEntry(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $jobNum = $request->input('job_num');
        $shift = $request->input('shift');
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()
            ->post($url . '/JobEntry/GetDetailJob', [
                'ipJobNum' => $jobNum
            ]);
        $responseResult = $response->body();
        $responseData = json_decode($responseResult, true);
        // dd($responseData['data']);
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
        $shift = $request->input('shift');
        if ($shift == 'SHIFT 1') {
            $availData = [8];
        } else {
            $availData = [8.25, 7];
        }
        $downTime = DB::table('downtime_list')
            ->where('category_id', $request->category_id)->get();
        return response()->json(array_merge(
            $responseData,
            [
                'availData' => $availData,
                'machine' => $machineData,
                'downtime' => $downTime
            ]
        ));
    }
    //Fungsi start menjalankan mesin
    public function setJobNumberV2(Request $request)
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
        // Http::withoutVerifying()->post(
        //     'https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update',
        //     [
        //         "machine_id" => $machineId,
        //         "condition_id" => 0,
        //         "job_num" => $job_num
        //     ]
        // );
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
        //log_header_machine_summary
        $db_update_summary = $this->LogMachine->updateSummary($machineId, $shift, $job_num, $production_date, $qty_plan);
        //Tampilkan data OEE
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
            //Tampilkan log_header_machine
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
                $updateData['model'] = $revisionNum;
            }
            $update = LogMachine::where('machine_id', $row)
                ->where('is_active', 1)
                ->update($updateData);
        }
        if ($update) {
            if ($db_update_summary) {
                //update log_header_machine_summary is_active false
                DB::table('log_header_machine_summary')
                    ->whereIn('machine_id', $machineId)
                    ->update(['is_active' => 0]);
                foreach ($machineId as $i => $row) {
                    $standardSph = $standard_sph[$i] ?? null;
                    //Update log_header_machine_summary
                    $this->LogMachine->updateLogheaderSummary($row, $shift, $job_num, $production_date, $qty_plan, $qty_actual, $standardSph);
                }
                $summary_id = $this->LogMachine->getSummaryId($machineId, $shift, $job_num, $production_date, $qty_plan);
            } else {
                foreach ($machineId as $i => $machine) {
                    $standardSph = $standard_sph[$i] ?? null;
                    //Insert log_header_machine_summary
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
    //Fungsi mencari JobNumber
    public function GetMachineRunning(Request $request)
    {
        //category_line_id
        $line_id = match ($request->categoryLine) {
            'A1' => 'STP-002',
            'A2' => 'STP-003',
            'A6' => 'STP-007',
            'RBT-5H45' => 'ASSY-002',
            'RBT-5J45' => 'ASSY-014',
            'SSW' => 'ASSY-009',
            default => null,
        };

        $machineData = $this->LogMachine->getRunningMachine($line_id);
        $uniqueJobs = $machineData->pluck('job_num')->unique()->filter(fn($jobNum) => $jobNum !== '1' && !empty($jobNum));
        $epicorResponses = [];
        $url = config('services.epicor_app.url');
        //API Epicor JobEntry Detail JO
        $responses = Http::pool(function ($pool) use ($uniqueJobs) {
            return collect($uniqueJobs)
                ->mapWithKeys(function ($jobNum) use ($pool) {
                    return [
                        $jobNum => $pool->as($jobNum)->withoutVerifying()->post('https://192.168.1.251:8000/EPIAPI/JobEntry/GetDetailJob', [
                            'ipJobNum' => $jobNum
                        ])
                    ];
                })->all();
        });

        foreach ($responses as $jobNum => $response) {
            $epicorResponses[$jobNum] = $response->json();
        }
        $machineCombinations = $machineData->map(fn($item) => [
            'machine_id' => $item->machine_id,
            'job_num' => $item->job_num,
            'production_date' => $item->production_date,
            'shift' => $item->shift,
        ])->unique();
        $query = DB::table('log_downtime')->where('is_active', true);
        $query->where(function ($q) use ($machineCombinations) {
            foreach ($machineCombinations as $combo) {
                $q->orWhere(function ($q2) use ($combo) {
                    $q2->where('machine_id', $combo['machine_id'])
                        ->where('job_num', $combo['job_num'])
                        ->whereDate('production_date', $combo['production_date'])
                        ->where('shift', $combo['shift']);
                });
            }
        });

        $activeDowntimes = $query->get();
        $downtimeStatus = $activeDowntimes->mapWithKeys(fn($row) => [
            $row->machine_id . '|' . $row->job_num . '|' . $row->production_date . '|' . $row->shift => true
        ]);

        $mergedMachines = [];

        foreach ($machineData as $machine) {
            $laborEntryMethod = null;
            $oprSeq = null;
            $jobNum = $machine->job_num;

            if (isset($epicorResponses[$jobNum]['data'])) {
                $epicorData = $epicorResponses[$jobNum]['data'];
                $jobOpDtl = $epicorData['jobOpDtl'] ?? [];
                $jobOper = $epicorData['jobOper'] ?? [];

                $matchOpDtl = collect($jobOpDtl)->firstWhere('resourceID', $machine->machine_id);

                if ($matchOpDtl) {
                    $oprSeq = $matchOpDtl['oprSeq'];
                    $matchJobOper = collect($jobOper)->firstWhere('oprSeq', $oprSeq);

                    if ($matchJobOper) {
                        $laborEntryMethod = $matchJobOper['laborEntryMethod'];
                    }
                }
            }

            $key = $machine->machine_id . '|' . $machine->job_num . '|' . $machine->production_date . '|' . $machine->shift;
            $is_active = $downtimeStatus[$key] ?? false;

            $mergedMachines[] = array_merge($machine->toArray(), [
                'laborEntryMethod' => $laborEntryMethod,
                'opr' => $oprSeq,
                'status_downtime' => $is_active,
            ]);
        }
        //category blok = assembly atau stamping
        $ctDT = match ($request->blok) {
            'assy' => 'ASSY',
            'stamping' => 'STP',
            default => null,
        };
        //Susunan Downtime
        $downTime = DB::table('downtime_list')
            ->where('category_id', $ctDT)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'machine' => $mergedMachines,
            'downtime' => $downTime,
        ]);
    }
    public function toolRunningMachine()
    {
        $latestDowntime = $this->LogMachine->latestDowntime();
        $machineData = $this->LogMachine->toolMachineData($latestDowntime);
        $downTime = DB::table('downtime_list')
            ->where('category_id', 'ASSY')->get();
        return response()->json([
            'machine' => $machineData,
            'downtime' => $downTime
        ]);
    }
    public function getOneMachine(Request $request)
    {
        $machineId = $request->input('machine_id');
        //log_header_machine by mesin ID
        $data = LogMachine::find($machineId);
        $shiftData = DB::table('log_header_machine_summary')
            ->where('is_active', 1)
            ->where('machine_id', $machineId)
            ->get();
        $selectedShift = 'SHIFT 1';
        if ($shiftData->count() > 0) {
            $selectedShift = $shiftData->last()->shift;
        }
        $shiftOptions = [
            ['id' => '1', 'text' => 'SHIFT 1'],
            ['id' => '2', 'text' => 'SHIFT 2'],
        ];
        if ($data) {
            return response()->json(data: [
                'machine' => $data,
                'selected_shift' => $selectedShift,
                'shift_options' => $shiftOptions
            ]);
        } else {
            return response()->json([
                'message' => 'Mesin tidak ditemukan'
            ], 404);
        }
    }
    public function getAllJobNumber(Request $request)
    {
        $search = $request->q;
        $page = $request->page ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $query = $this->LogMachine->allJO($search);
        $results = $query->offset($offset)->limit($limit + 1)->get();
        $hasMore = $results->count() > $limit;
        $results = $results->take($limit)->map(function ($item) {
            return [
                'id' => $item->JobNum,
                'text' => $item->JobNum . ' - ' . $item->ProdCode
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => $hasMore]
        ]);
    }
    public function JoList(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $shift = $request->shift;
        $category = strtoupper(trim($request->category_id));
        if ($shift == 1 || $shift == 8 || $shift == 9 || $shift == 10 || $shift == 13) {
            $shift = 'SHIFT 1';
        } else {
            $shift = 'SHIFT 2';
        }
        // if ($category === 'ASSY') {
        // $start = date('Y-m-d', strtotime('-3 day'));
        // $finish = date('Y-m-d', strtotime('+1 day'));
        // } else {
        $start = date('Y-m-d', strtotime('-10 day'));
        $finish = date('Y-m-d', strtotime('+1 day'));
        // }

        $query = $this->LogMachine->joList($start, $finish, $shift);
        if (!empty($category)) {
            if ($category === 'ASSY') {
                $query->where('JobNum', 'LIKE', 'ASY%');
            } else {
                $query->where('JobNum', 'LIKE', 'STP%');
            }
        }
        $data = $query->get();
        // Log::info([
        //     'shift' => $shift,
        //     'category' => $category,
        //     'start' => $start,
        //     'finish' => $finish
        // ]);
        return response()->json([
            'data' => $data,
            'start' => $start,
            'finish' => $finish,
            'shift' => $shift,
            'category' => $category
        ]);
    }
    public function ShiftList(Request $request)
    {
        $data = DB::table('log_header_machine_summary')
            ->where('is_active', 1)
            ->get();
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $shift = $row->shift;
            }
        } else {
            $shift = 'SHIFT 1';
        }
        $db = '<select id="shift-select" class="selectize-shift">';
        $db .= '<option value="SHIFT 1"' . ($shift == 'SHIFT 1' ? ' selected' : '') . '>Shift 1</option>';
        $db .= '<option value="SHIFT 2"' . ($shift == 'SHIFT 2' ? ' selected' : '') . '>Shift 2</option>';
        $db .= '</select>';
        return $db;
    }

    public function getAvail(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $job_num = $request->job_num === 'null' ? 1 : $request->machine_id;
        $data = DB::table('log_header_machine_summary as a')
            ->join('oee_log_machine AS b', function ($join) {
                $join->on('a.job_num', '=', 'b.job_num');
                $join->on('a.machine_id', 'b.machine_id');
                $join->on('a.production_date', 'a.production_date');
                $join->on('a.shift', 'a.shift');
            })
            ->where('a.job_num', $job_num)
            // ->where('a.is_active', 1)
            ->select('b.available_time')
            ->get();
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $available_time = $row->available_time;
            }
        } else {
            $weekday = date('N', strtotime($date));
            if ($weekday == 5) {
                $available_time = 7;
            } else {
                $available_time = 8.25;
            }
        }
        $avail_1 = 8.25;
        $avail_2 = 7;
        $db = '<select id="avail-select" class="selectize-avail">';
        for ($i = 1; $i < 3; $i++) {
            $db .= '<option value="' . ${'avail_' . $i} . '" ' . (${'avail_' . $i} == $available_time ? 'selected' : '') . '>' . ${'avail_' . $i} . ' H</option>';
        }
        $db .= '</select>';
        return $db;
    }
    public function SetFinish(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql_ = date('Y-m-d');
        $date_time_sql = date('Y-m-d H:i:s');
        $id = $request->machine_id;
        $jobNumber = LogMachine::where('machine_id', $id)->value('job_num');
        $allJobNum = LogMachine::where('job_num', $jobNumber)->get();
        foreach ($allJobNum as $row) {
            $mHeader = DB::table('log_header_machine')
                ->where('machine_id', $row->machine_id)
                ->where('is_active', 1)
                ->where('condition_id', 1)
                ->first();
            if ($mHeader->part_no == '65701-TG1-T000-50-BL' || $mHeader->part_no == '71251-3M0-3000-BL' || $mHeader->part_no == '65166-TG4-U000-50-BL') {
                $dataHistory['qty_ok'] = $mHeader->qty_ok;
            }
            $historyRecord = DB::table('history_header_machine')
                ->where('machine_id', $row->machine_id)
                ->where('job_num', $row->job_num)
                ->where('production_date', $row->production_date)
                ->where('shift', $row->shift)
                ->first();
            if ($historyRecord) {
                $dataHistory['qty_actual'] = $row->qty_actual;
            } else {
                $dataHistory['qty_actual'] = $row->qty_actual;
            }
            $dataHistory['machine_id'] = $row->machine_id;
            $dataHistory['line_id'] = $row->line_id;
            $dataHistory['line_detail_id'] = $row->line_detail_id;
            $dataHistory['category_line_id'] = $row->category_line_id;
            $dataHistory['machine_code'] = $row->machine_code;
            $dataHistory['machine_name'] = $row->machine_name;
            $dataHistory['tonage'] = $row->tonage;
            $dataHistory['started_at'] = $row->started_at;
            $dataHistory['finished_at'] = $date_time_sql;
            $dataHistory['average_ct'] = $row->average_ct;
            $dataHistory['operation_time'] = $row->operation_time;
            $dataHistory['current_gsph'] = $row->current_gsph;
            $dataHistory['production_date'] = $row->production_date;
            $dataHistory['standard_sph'] = $row->standard_sph;
            $dataHistory['job_num'] = $row->job_num;
            $dataHistory['part_no'] = $row->part_no;
            $dataHistory['qty_plan'] = $row->qty_plan;
            $dataHistory['condition_id'] = 0;
            $dataHistory['shift'] = $row->shift;
            $dataHistory['break_12'] = $row->break_12;
            $dataHistory['break_18'] = $row->break_18;
            $dataHistory['break_02'] = $row->break_02;
            $dataHistory['is_active'] = 0;
            $dataHistory['customer'] = $row->customer;
            $dataHistory['employee_id'] = $row->employee_id;
            $dataHistory['employee_name'] = $row->employee_name;
            DB::Table('history_header_machine')->insert($dataHistory);
            DB::table('oee_log_machine')
                ->where('job_num', $row->job_num)
                ->where('machine_id', $row->machine_id)
                ->where('shift', $row->shift)
                ->update([
                    'operation_time' => $row->operation_time,
                    'operation_time_standard' => $row->qty_actual / $row->standard_sph,
                    'total_qty' => $row->qty_actual,
                    'total_ng' => $row->qty_ng
                ]);
        }
        // Http::withoutVerifying()->post(
        //     'https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update',
        //     [
        //         "machine_id" => $id,
        //         "condition_id" => 0,
        //         "job_num" => $jobNumber
        //     ]
        // );
        $update = LogMachine::where('job_num', $jobNumber)
            ->where('is_active', 1)
            ->update([
                'started_at' => null,
                'finished_at' => $date_time_sql,
                'average_ct' => 0,
                'operation_time' => 0,
                'current_gsph' => 0,
                'production_date' => null,
                'job_num' => '',
                'part_no' => '',
                'qty_plan' => null,
                'qty_actual' => 0,
                'qty_ok' => 0,
                'qty_ng' => 0,
                'condition_id' => 0,
                'shift' => null,
                'break_12' => 0,
                'break_18' => 0,
                'break_02' => 0,
                'customer' => '',
                'employee_id' => '',
                'employee_name' => '',
                'status_finish' => 1,
                'model' => null
            ]);
        if ($update) {
            DB::table('log_header_machine_summary')
                ->where('machine_id', "$id")
                ->update(['is_active' => 0]);
            $dataNew = LogMachine::where('machine_id', $id)->get();
            $last_update = DB::table('log_detail_machine')
                ->where('machine_id', $id)
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('created_at', 'asc')
                ->first();
            if ($last_update && $last_update->created_at) {
                $startTime = Carbon::parse($last_update->created_at)->format('H:i');
            } else {
                $startTime = '07:30';
            }
            foreach ($dataNew as $row) {
                $dataMachine['operation_time'] = 0;
                // $dataMachine['qty_actual'] = 0;
                $dataMachine['current_gsph'] = 0;
                $dataMachine['current_gsph_persen'] = 0;
                $dataMachine['condition_id'] = 0;
                // $dataMachine['job_num'] = null;
                $dataMachine['qty_plan'] = 0;
                $dataMachine['customer'] = '';
                $dataMachine['employee_id'] = '';
                $dataMachine['employee_name'] = '';
                $dataMachine['part_no'] = '';
                $dataMachine['data_chart_gsph'] = 0;
                $dataMachine['data_chart_downtime'] = 0;
                $dataMachine['ct_log_detail'] = 0;
                $dataMachine['average_ct'] = 0;
                $dataMachine['bar_progress'] = 0;
                $dataMachine['oee_quality'] = 0;
                $dataMachine['oee_availability'] = 0;
                $dataMachine['oee_performance'] = 0;
                $dataMachine['machine_id'] = $id;
            }
            $client = new Client("ws://127.0.0.1:8080");
            $data = [
                'action' => 'trigger',
                'channel' => 'machine',
                'event' => 'finish-machine',
                'data' => [
                    'message' => $dataMachine
                ]
            ];
            $client->send(json_encode($data));
            $client->close();
            return response()->json(
                [
                    'machine_id' => $id,
                    'machine' => LogMachine::find($id),
                    'status' => true,
                    'start_time' => $startTime,
                    'qty_actual' => $dataNew[0]->qty_actual ?? 0,
                    'message' => 'Berhasil update',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'machine_id' => $id,
                    'status' => false,
                    'message' => 'Gagal update',
                ],
                404,
            );
        }
    }
    public function StartDowntime(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_time_sql = date('Y-m-d H:i:s');
        $job_num = $request->job_num ?? null;
        $shift = $request->shift ?? null;
        $machine_id = $request->machineId;
        $downtime_id = $request->downTimeId;
        $production_date = $request->production_date ?? date('Y-m-d');
        try {
            $production_date = Carbon::parse($production_date)->format('Y-m-d');
        } catch (\Exception $e) {
            $production_date = date('Y-m-d');
        }
        $downtime_list = DB::table('downtime_list')
            ->where('id', $downtime_id)
            ->first();
        $dataDT = [
            'machine_id' => $machine_id,
            'job_num' => $job_num,
            'shift' => $shift,
            'downtime_id' => $downtime_id,
            'started_at' => $date_time_sql,
            'finished_at' => $date_time_sql,
            'downtime' => 0,
            'production_date' => $production_date,
            'is_active' => true,
            'trial_qty' => $request->downtime_qty
        ];
        $dbDowntime = $this->LogMachine->insertDT($dataDT);
        $dataAct = [
            'machine_id' => $machine_id,
            'activity' => $downtime_list->name,
            'job_num' => $job_num,
            'shift' => $shift,
            'downtime_seq_id' => $dbDowntime,
            'start_date' => $date_time_sql,
            'production_date' => $production_date,
            'note' => $request->note
        ];
        $this->LogMachine->insertAct($dataAct);
        $allDowntime = collect();
        $downtimeChartLabels = [];
        $downtimeChartValues = [];
        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine_id)
            ->where('d.shift', $shift)
            ->whereDate('d.production_date', $production_date)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->get();
        $allDowntime = $allDowntime->merge($downtime);
        $groupedDowntime = $allDowntime
            ->groupBy('name')
            ->map(function ($items) {
                return $items->sum('total_downtime');
            })
            ->sortDesc();
        $topFive = $groupedDowntime->take(4);
        $othersTotal = $groupedDowntime->slice(4)->sum();

        if ($othersTotal > 0) {
            $topFive = $topFive->put('Others', $othersTotal);
        }
        $downtimeChartLabels = $topFive->keys()->toArray();
        $downtimeChartValues = $topFive->values()->toArray();
        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', date('Y-m-d'))
            ->where('shift', $shift)
            ->limit(5)
            ->get();
        $data['log_activity'] = $log_activity;
        $data['machine_id'] = $machine_id;
        // $data['status_action'] = 1;
        $data['downtime'] = $dbDowntime;
        $data['downtimeChartLabels'] = $downtimeChartLabels;
        $data['downtimeChartValues'] = $downtimeChartValues;
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'start-downtime',
            'data' => [
                'message' => $data
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
//         $machine_data = $this->config->machine_data($machine_id);
//         $machine = NULL;
//         if (str_contains(strtoupper($machine_data->category_line_id), 'ASSY')) {
//             $line = 'ASSY';
//             if (str_contains(strtoupper($machine_data->machine_id), 'RSW')) {
//                 $machine = 'RSW';
//             }
//             if (str_contains(strtoupper($machine_data->machine_id), 'SSW-B')) {
//                 $machine = 'SSW-B';
//             }
//         } else {
//             $line = 'STP';
//             if (str_contains(strtoupper($machine_data->machine_id), 'A6')) {
//                 $machine = 'A6';
//             }
//         }
//         $employee_data = $this->config->employee_data($line, $machine, $downtime_list->type, 1);
//         foreach ($employee_data as $data) {
//             Http::acceptJson()
//                 ->post(config('services.ems_wa.url'), [
//                     'phone' => $data->Telp,
//                     'message' => "*[ESCALATION DOWNTIME]*

// Dear Bapak/Ibu,
// Downtime sedang berlangsung dan memerlukan perhatian segera.
// Machine  : {$machine_id}
// Downtime : {$downtime_list->name}
// Start    : " . now()->format('Y-m-d H:i:s') . "

// Mohon segera dilakukan pengecekan dan tindak lanjut untuk meminimalisir impact terhadap produksi.

// Terima kasih."
//                 ]);
//         }
        return response()->json([
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'status_action' => 1,
            'machine_id' => $machine_id,
            'job_num' => $job_num,
            'status' => true,
            'message' => 'Berhasil update',
        ]);

    }
    public function FinishDowntime(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_time_sql = date('Y-m-d H:i:s');
        $job_num = $request->job_num;
        $shift = $request->shift;
        $production_date = $request->production_date;
        $machine_id = $request->machine_id;
        $logDT = DB::table('log_downtime')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->whereNull('tool_id')
            ->orderByDesc('started_at')
            ->first();
        if ($logDT->downtime_id == 62 || $logDT->downtime_id == '62') {
            $dtQty = $request->downtime_qty - $logDT->trial_qty;
            DB::table('log_downtime')
                ->where('machine_id', $machine_id)
                ->where('job_num', $job_num)
                ->where('shift', $shift)
                ->where('production_date', $production_date)
                ->whereNull('tool_id')
                ->orderByDesc('started_at')
                ->update([
                    'trial_qty' => $dtQty
                ]);
        }
        $startDateTime = new DateTime($logDT->started_at);
        $finishDateTime = new DateTime($date_time_sql);
        $interval = $startDateTime->diff($finishDateTime);
        $minutesDiff = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
        $downtimeDuration = ($minutesDiff > 0 ? $minutesDiff / 60 : 0);
        $dbDowntime = DB::table('log_downtime')
            ->where('seq_id', $logDT->seq_id)
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->where('is_active', 1)
            ->whereNull('tool_id')
            ->update([
                'finished_at' => $date_time_sql,
                'downtime' => $downtimeDuration,
                'is_active' => false
            ]);
        $logActivity = DB::table('log_activity')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->whereNull('tool_id')
            ->orderByDesc('start_date')
            ->first();
        DB::table('log_activity')
            ->where('seq_id', $logActivity->seq_id)
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->whereNull('tool_id')
            ->update([
                'end_date' => $date_time_sql
            ]);
        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', date('Y-m-d'))
            ->where('shift', $shift)
            ->limit(5)
            ->get();
        $allDowntime = collect();
        $downtimeChartLabels = [];
        $downtimeChartValues = [];
        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine_id)
            ->where('d.shift', $shift)
            ->whereDate('d.production_date', $production_date)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->get();
        $allDowntime = $allDowntime->merge($downtime);
        $groupedDowntime = $allDowntime
            ->groupBy('name')
            ->map(function ($items) {
                return $items->sum('total_downtime');
            })
            ->sortDesc();
        $topFive = $groupedDowntime->take(4);
        $othersTotal = $groupedDowntime->slice(4)->sum();

        if ($othersTotal > 0) {
            $topFive = $topFive->put('Others', $othersTotal);
        }
        $downtimeChartLabels = $topFive->keys()->toArray();
        $downtimeChartValues = $topFive->values()->toArray();
        $data['log_activity'] = $log_activity;
        $data['machine_id'] = $machine_id;
        // $data['status_action'] = 2;
        $data['downtime'] = $dbDowntime;
        $data['downtimeChartLabels'] = $downtimeChartLabels;
        $data['downtimeChartValues'] = $downtimeChartValues;
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'finish-machine',
            'data' => [
                'message' => $data
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return response()->json([
            'dt_data' => $dbDowntime,
            'status_action' => 1,
            'machine_id' => $machine_id,
            'job_num' => $job_num,
            'shit' => $shift,
            'production_date' => $production_date,
            'status' => true,
            'message' => 'Berhasil update',
        ]);
    }
    #Special Setup
    public function special_setup_category(Request $request)
    {
        $machineID = DB::table('log_header_machine as h')
            ->join('log_machine_tool as t', 'h.machine_id', '=', 't.machine_id')
            ->where('h.category_line_id', 'like', "%{$request->category}%")
            ->select('t.machine_id')
            ->distinct()
            ->get();
        $id = 'ASSY';
        $shift = $this->LogMachine->getCategory($id);
        return response()->json([
            'machine' => $machineID,
            'shift' => $shift
        ]);
    }
    public function special_setup_machine(Request $request)
    {
        $machineID = $request->machine;
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $data = DB::table('log_header_machine_summary as a')
            ->join('oee_log_machine AS b', function ($join) {
                $join->on('a.job_num', '=', 'b.job_num');
                $join->on('a.machine_id', 'b.machine_id');
                $join->on('a.production_date', 'a.production_date');
                $join->on('a.shift', 'a.shift');
            })
            ->where('a.machine_id', $machineID)
            ->where('a.is_active', 1)
            ->select('b.available_time', 'a.production_date', 'a.standard_sph')
            ->get();
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $available_time = $row->available_time;
            }
        } else {
            $weekday = date('N', strtotime($date));
            if ($weekday == 5) {
                $available_time = 7;
            } else {
                $available_time = 8.25;
            }
        }
        $shift = $request->input('shift');
        if ($shift == 'SHIFT 1') {
            $availData = [8];
        } else {
            $availData = [8.25, 7];
        }
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $production_date = $row->production_date;
                $status = true;
            }
            ;
        } else {
            $production_date = $date;
            $status = false;
        }
        return response()->json([
            'availData' => $availData,
            'production_date' => $production_date,
            'status' => $status
        ]);
    }
    public function special_setup_work_time(Request $request)
    {
        $machine_id = $request->machineID;
        $shift = $request->shift;
        $data = $this->LogMachine->WorkTime($machine_id);
        return response()->json([
            'data' => $data
        ]);
    }
    public function special_setup_start(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machine_id = $request->machineID;
        // dd($machine_id);
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
            // $conflict = logMachine::where('machine_id', $machine_id)
            //     ->where('job_num', $job_num)
            //     ->exists();
            // if ($conflict) {
            //     continue;
            // }
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
    public function setDowntimeJig(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machineID = $request->machineID;
        $jobNum = $request->jobNum;
        $downtimeID = $request->downtimeID;
        $toolID = $request->toolID;
        $note = $request->note;
        $productionDate = $request->production_date ?? date('Y-m-d');
        try {
            $productionDate = Carbon::parse($productionDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $productionDate = date('Y-m-d');
        }
        $machineD = DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate);
        // ->first();
        $machineD->update([
            'trail_mode' => true
        ]);
        $machineData = $machineD->first();
        DB::table('log_downtime')
            ->insert([
                'machine_id' => $machineID,
                'job_num' => $jobNum,
                'shift' => $machineData->shift ?? null,
                'downtime_id' => $downtimeID,
                'production_date' => $productionDate,
                'started_at' => $time,
                'finished_at' => $time,
                'is_active' => true,
                'tool_id' => $toolID
            ]);
        $nameActivity = DB::table('downtime_list')->where('id', $downtimeID)->value('name');
        DB::table('log_activity')
            ->insert([
                'activity' => $nameActivity,
                'machine_id' => $machineID,
                'job_num' => $jobNum,
                'start_date' => $time,
                'shift' => $machineData->shift ?? NULL,
                'production_date' => $productionDate,
                'note' => $note,
                'tool_id' => $toolID
            ]);
        $allDowntime = collect();
        $downtimeChartLabels = [];
        $downtimeChartValues = [];
        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machineID)
            ->where('d.shift', $machineData->shift)
            ->whereDate('d.production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->get();
        $allDowntime = $allDowntime->merge($downtime);
        $groupedDowntime = $allDowntime
            ->groupBy('name')
            ->map(function ($items) {
                return $items->sum('total_downtime');
            })
            ->sortDesc();
        $topFive = $groupedDowntime->take(4);
        $othersTotal = $groupedDowntime->slice(4)->sum();

        if ($othersTotal > 0) {
            $topFive = $topFive->put('Others', $othersTotal);
        }
        $downtimeChartLabels = $topFive->keys()->toArray();
        $downtimeChartValues = $topFive->values()->toArray();
        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machineID)
            ->where('job_num', $machineData->job_num)
            ->where('production_date', $productionDate)
            ->where('shift', $machineData->shift)
            ->where('tool_id', $toolID)
            ->limit(5)
            ->get();
        $data['log_activity'] = $log_activity;
        $data['machine_id'] = $machineID;
        $data['status_action'] = 1;
        $data['tool_id'] = $toolID;
        $data['downtimeChartLabels'] = $downtimeChartLabels;
        $data['downtimeChartValues'] = $downtimeChartValues;
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'start-downtime-tool',
            'data' => [
                'message' => $data
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return response()->json([
            'message' => 'Downtime berhasil di set',
            'status' => true
        ]);
    }
    public function finishDowntimeTool(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machineID = $request->machineID;
        $jobNum = $request->jobNum;
        $productionDate = $request->productionDate;
        $toolID = $request->toolID;
        $machineData = DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->first();
        $downtimeLog = DB::table('log_downtime')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->where('is_active', 1)
            ->where('tool_id', $toolID)
            ->orderByDesc('started_at')
            ->first();
        $startDateTime = new DateTime($downtimeLog->started_at);
        $finishDateTime = new DateTime($time);
        $interval = $startDateTime->diff($finishDateTime);
        $minutesDiff = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
        $downtimeDuration = ($minutesDiff > 0 ? $minutesDiff / 60 : 0);
        DB::table('log_downtime')
            ->where('seq_id', $downtimeLog->seq_id)
            ->where('machine_id', $machineID)
            ->where('shift', $downtimeLog->shift)
            ->where('tool_id', $toolID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->update([
                'finished_at' => $time,
                'is_active' => false,
                'downtime' => $downtimeDuration
            ]);
        $logActivity = DB::table('log_activity')
            ->where('machine_id', $machineID)
            ->where('job_num', $jobNum)
            ->where('shift', $downtimeLog->shift)
            ->where('production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->orderByDesc('start_date')
            ->first();
        DB::table('log_activity')
            ->where('seq_id', $logActivity->seq_id)
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->update([
                'end_date' => $time
            ]);
        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machineID)
            ->where('job_num', $jobNum)
            ->where('production_date', date('Y-m-d'))
            ->where('shift', $machineData->shift)
            ->where('tool_id', $toolID)
            ->limit(5)
            ->get();
        $allDowntime = collect();
        $downtimeChartLabels = [];
        $downtimeChartValues = [];
        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machineID)
            ->where('d.shift', $machineData->shift)
            ->whereDate('d.production_date', date('Y-m-d'))
            ->where('tool_id', $toolID)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->get();
        $allDowntime = $allDowntime->merge($downtime);
        $groupedDowntime = $allDowntime
            ->groupBy('name')
            ->map(function ($items) {
                return $items->sum('total_downtime');
            })
            ->sortDesc();
        $topFive = $groupedDowntime->take(4);
        $othersTotal = $groupedDowntime->slice(4)->sum();

        if ($othersTotal > 0) {
            $topFive = $topFive->put('Others', $othersTotal);
        }
        $downtimeChartLabels = $topFive->keys()->toArray();
        $downtimeChartValues = $topFive->values()->toArray();
        $data['log_activity'] = $log_activity;
        $data['machine_id'] = $machineID;
        $data['status_action'] = 2;
        $data['tool_id'] = $toolID;
        $data['downtimeChartLabels'] = $downtimeChartLabels;
        $data['downtimeChartValues'] = $downtimeChartValues;
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'finish-downtime-tool',
            'data' => [
                'message' => $data
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return response()->json([
            'message' => 'Downtime Finish',
            'status' => true
        ]);
    }
    public function setFinishTool(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $machineID = $request->machineID;
        $toolID = $request->toolID;
        $productionDate = $request->production_date;
        $jobNum = $request->jobNum;
        $machineData = DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->first();
        // dd($machineData);
        $summaryData = [
            'qty_actual' => $machineData->qty_actual ?? 0,
            'qty_ng' => $machineData->qty_ng ?? 0,
            'operation_time' => $machineData->operation_time ?? 0,
            'finished_at' => $time,
            'is_active' => false
        ];
        $this->LogMachine->updateHeaderSummary($machineID, $toolID, $productionDate, $jobNum, $summaryData);
        $oeeData = [
            'total_qty' => $machineData->qty_actual ?? 0,
            'total_ng' => $machineData->qty_ng ?? 0,
            'operation_time' => $machineData->operation_time ?? 0
        ];
        $this->LogMachine->updateOeeHeader($machineID, $toolID, $productionDate, $jobNum, $oeeData);
        $dataHistory = [
            'machine_id' => $machineID,
            'job_num' => $jobNum,
            'part_no' => $machineData->part_no ?? null,
            'qty_plan' => $machineData->qty_plan ?? 0,
            'qty_actual' => $machineData->qty_actual ?? 0,
            'qty_ng' => $machineData->qty_ng ?? 0,
            'shift' => $machineData->shift ?? null,
            'standard_sph' => $machineData->standard_sph ?? null,
            'current_gsph' => $machineData->current_gsph ?? null,
            'category_line_id' => $machineData->category_line,
            'started_at' => $machineData->started_at,
            'finished_at' => $time,
            'operation_time' => $machineData->operation_time ?? 0,
            'production_date' => $machineData->production_date,
            'customer' => $machineData->customer,
            'employee_id' => $machineData->employee_id,
            'employee_name' => $machineData->employee_name,
            'tool_id' => $machineData->tool_id
        ];
        $this->LogMachine->insertHistory($dataHistory);
        $dataTool = [
            'finished_at' => $time,
            'job_num' => null,
            'production_date' => null,
            'shift' => null,
            'part_no' => null,
            'condition_id' => false,
            'qty_plan' => 0,
            'qty_actual' => 0,
            'qty_ng' => 0,
            'started_at' => null,
            'status_finish' => true,
            'model' => null
        ];
        $this->LogMachine->updateTool($machineID, $toolID, $productionDate, $jobNum, $dataTool);
        $logMachine = DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('job_num', $jobNum)
            ->where('production_date', $productionDate)
            ->where('tool_id', $toolID)
            ->first();
        // dd($machineID);
        $message = [
            'machine_id' => $machineID,
            'tool_id' => $toolID,
            // 'qty_actual' => $logMachine->qty_actual,
            // 'started_at' => $logMachine->started_at,
            // 'finished_at' => $logMachine->finished_at,
            // 'operation_time' => $logMachine->operation_time,
            'topic' => 'set_finish_tool'
        ];
        $client = new Client("ws://127.0.0.1:8080");
        $data = [
            'action' => 'trigger',
            'channel' => 'machine',
            'event' => 'finish-tool',
            'data' => [
                'message' => $message
            ]
        ];
        $client->send(json_encode($data));
        $client->close();
        return response()->json([
            'message' => 'Update Successfully',
            'status' => true,
            'machine' => $machineData,
            'tool' => $toolID,
            'production_date' => $productionDate,
            'jo' => $jobNum
        ]);
    }
    #End Special Setup
    public function list_log_machine()
    {
        $data = $this->LogMachine->listLogmachine();
        return response()->json([
            'message' => 'Berhasil mengambil data',
            'data' => $data,
            'status' => true
        ]);
    }
    public function dashboardSummaryMachine($id)
    {
        $now = Carbon::now('Asia/Jakarta');
        if ($now->hour >= 7 && $now->hour < 17) {
            $shift = 'SHIFT 2';
        } else {
            $shift = 'SHIFT 1';
        }

        $machine = $this->LogMachine->firstHistoryMachine($id, $now->format('Y-m-d'), $shift);

        if (!$machine) {
            $machine = LogMachine::find($id);
        }

        $sumDowntime = $this->LogMachine->sumDowntime($id, $now->format('Y-m-d'), $shift);

        if ($machine) {
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;

            $started_at = new DateTime($machine->started_at);
            $finished_at = new DateTime(date('Y-m-d H:i:s'));
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();

            if ($operation_time <= 0) {
                $oee_performance = 0;
                $oee_availability = 0;
            } else {
                $potongIstirahat = 0;
                $started_at = Carbon::parse($machine->started_at);
                $finished_at = Carbon::parse(date('Y-m-d H:i:s'));

                $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
                $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);

                if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                    $potongIstirahat += 2700;
                }

                $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
                $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);

                if ($finished_at->lessThan($started_at)) {
                    $finished_at->addDay();
                }

                if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                    $istirahatMalamMulai->addDay();
                    $istirahatMalamSelesai->addDay();
                }

                if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                    $potongIstirahat += 2700;
                }
                $operation_time -= $potongIstirahat;
                $operasi_time = $operation_time / 60;
                $waktuOperasi = $operasi_time - $sumDowntime;
                // $standardCT = 60 / $machine->standard_sph;
                // $actualCT = $waktuOperasi / $machine->qty_actual;
                $standardCT = $machine->standard_sph > 0
                    ? 60 / $machine->standard_sph
                    : 0;

                $actualCT = $machine->qty_actual > 0
                    ? $waktuOperasi / $machine->qty_actual
                    : 0;
                $stdQTY_Actual = $standardCT * $waktuOperasi;
                // $oee_performance = $standardCT * $machine->qty_actual / $waktuOperasi * 100;
                // $oee_availability = $waktuOperasi / $operasi_time * 100;
                $oee_performance = ($waktuOperasi > 0 && $machine->qty_actual > 0)
                    ? ($standardCT * $machine->qty_actual / $waktuOperasi) * 100
                    : 0;

                $oee_availability = $operasi_time > 0
                    ? ($waktuOperasi / $operasi_time) * 100
                    : 0;
            }
        } else {
            $oee_quality = 100;
            $oee_availability = 0;
            $oee_performance = 0;
        }

        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $id)
            ->where('d.production_date', $now->format('Y-m-d'))
            ->where('shift', $shift)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        $downtimeFive = $downtime->take(5);
        $downtimeOthers = $downtime->slice(5)->sum('total_downtime');

        if ($downtimeOthers > 0) {
            $downtimeFive->push((object) [
                'name' => 'Others',
                'total_downtime' => $downtimeOthers
            ]);
        }

        $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
        $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();

        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("RIGHT('0' + CAST(DATEPART(HOUR, cut_off_time) AS VARCHAR), 2) + ':30' as hour_label, SUM(gsph) as gsph")
            ->where('machine_id', $id)
            ->where('shift', $shift)
            ->where('cut_off', $now->format('Y-m-d'))
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();

        $gsphLabel = $gsphRecord->pluck('hour_label');
        $gsphValues = $gsphRecord->pluck('gsph');

        return response()->json([
            'id' => $id,
            'qty_actual' => $machine->qty_actual ?? 0,
            'qty_plan' => $machine->qty_plan ?? 0,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabel' => $gsphLabel,
            'gsphValues' => $gsphValues,
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'opr_time' => $operasi_time,
            'dtDuration' => $sumDowntime,
            'oprDT' => $waktuOperasi,
            'ct' => $standardCT
        ]);
    }
    public function dashboardSummaryMachineTool($id)
    {
        $now = Carbon::now('Asia/Jakarta');
        $shift = ($now->hour >= 7 && $now->hour < 17) ? 'SHIFT 2' : 'SHIFT 1';

        $machines = DB::table('history_header_machine')
            ->where('machine_id', $id)
            ->where('production_date', $now->format('Y-m-d'))
            ->where('shift', $shift)
            ->whereNotNull('tool_id')
            ->get();

        if ($machines->isEmpty()) {
            $machines = DB::table('log_machine_tool')
                ->where('machine_id', $id)
                ->where('condition_id', true)
                ->whereNotNull('tool_id')
                ->get();
        }

        $oee_quality = 0;
        $oee_availability = 0;
        $oee_performance = 0;
        $downtimeChartLabels = [];
        $downtimeChartValues = [];
        $gsphLabel = [];
        $gsphValues = [];
        foreach ($machines as $row) {
            $sumDowntime = DB::table('log_downtime')
                ->where('machine_id', $id)
                ->where('production_date', $now->format('Y-m-d'))
                ->where('shift', $shift)
                ->where('tool_id', $row->tool_id)
                ->sum('downtime');

            $oee_quality = ($row->qty_actual > 0 && $row->qty_ng > 0)
                ? 100 - ceil(($row->qty_ng / $row->qty_actual) * 100)
                : 100;

            $started_at = new DateTime($row->started_at);
            $finished_at = new DateTime(date('Y-m-d H:i:s'));

            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();

            if ($operation_time <= 0) {
                $oee_performance = 0;
                $oee_availability = 0;
            } else {
                $potongIstirahat = 0;
                $started_at = Carbon::parse($row->started_at);
                $finished_at = Carbon::parse(date('Y-m-d H:i:s'));

                $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
                $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
                if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                    $potongIstirahat += 2700;
                }

                $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
                $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
                if ($finished_at->lessThan($started_at)) {
                    $finished_at->addDay();
                }
                if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                    $istirahatMalamMulai->addDay();
                    $istirahatMalamSelesai->addDay();
                }
                if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                    $potongIstirahat += 2700;
                }

                $operation_time -= $potongIstirahat;

                $operasi_time = $operation_time / 60;
                $waktuOperasi = $operasi_time - $sumDowntime;

                $standardCT = 60 / $row->standard_sph;
                $actualCT = $waktuOperasi / $row->qty_actual;

                $oee_performance = ($waktuOperasi > 0 && $row->qty_actual > 0)
                    ? $standardCT * $row->qty_actual / $waktuOperasi * 100
                    : 0;

                $oee_availability = ($operasi_time > 0)
                    ? $waktuOperasi / $operasi_time * 100
                    : 0;
            }
            $downtime = DB::table('log_downtime as d')
                ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
                ->where('d.machine_id', $id)
                ->where('d.production_date', $now->format('Y-m-d'))
                ->where('shift', $shift)
                ->where('tool_id', $row->tool_id)
                ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
                ->groupBy('l.name')
                ->orderBy('l.name')
                ->get();
            $downtimeFive = $downtime->take(5);
            $downtimeOthers = $downtime->slice(5)->sum('total_downtime');
            if ($downtimeOthers > 0) {
                $downtimeFive->push((object) [
                    'name' => 'Others',
                    'total_downtime' => $downtimeOthers
                ]);
            }

            $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
            $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();
            $gsphRecord = DB::table('gsph_record')
                ->selectRaw("RIGHT('0' + CAST(DATEPART(HOUR, cut_off_time) AS VARCHAR), 2) + ':30' as hour_label, SUM(gsph) as gsph")
                ->where('machine_id', $id)
                ->where('shift', $shift)
                ->where('cut_off', $now->format('Y-m-d'))
                ->where('tool_id', $row->tool_id)
                ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
                ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
                ->get();

            $gsphLabel = $gsphRecord->pluck('hour_label');
            $gsphValues = $gsphRecord->pluck('gsph');

            break;
        }
        return response()->json([
            'id' => $id,
            'qty_actual' => $machines->sum('qty_actual'),
            'qty_plan' => $machines->sum('qty_plan'),
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabel' => $gsphLabel,
            'gsphValues' => $gsphValues,
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'operation_time' => $operation_time,
            'opr_time' => $operasi_time,
            'dtDuration' => $sumDowntime,
            'oprDT' => $waktuOperasi,
            'ct' => $standardCT
        ]);
    }
    public function dashboard_tool($id, $tool)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $machine = DB::table('log_machine_tool')
            ->where('machine_id', $id)
            ->where('tool_id', $tool)
            ->first();
        $oee = DB::table('oee_log_machine')
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->get();
        $sumDowntime = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->sum('downtime');
        if ($machine) {
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;
            $started_at = new DateTime($machine->started_at);
            $finished_at = new DateTime(date('Y-m-d H:i:s'));
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();
            if ($operation_time <= 0) {
                $oee_performance = 0;
                $oee_availability = 0;
            } else {
                $potongIstirahat = 0;
                $started_at = Carbon::parse($machine->started_at);
                $finished_at = Carbon::parse($machine->finished_at);
                $istirahatSiangMulai = (clone $started_at)->setTime(11, 30);
                $istirahatSiangSelesai = (clone $started_at)->setTime(12, 15);
                if ($finished_at->greaterThanOrEqualTo($istirahatSiangMulai) && $started_at->lessThanOrEqualTo($istirahatSiangSelesai)) {
                    $potongIstirahat += 2700;
                }
                $istirahatMalamMulai = (clone $started_at)->setTime(2, 0);
                $istirahatMalamSelesai = (clone $started_at)->setTime(2, 45);
                if ($finished_at->lessThan($started_at)) {
                    $finished_at->addDay();
                }
                if ($finished_at->greaterThan($istirahatMalamSelesai)) {
                    $istirahatMalamMulai->addDay();
                    $istirahatMalamSelesai->addDay();
                }
                if ($finished_at->greaterThanOrEqualTo($istirahatMalamMulai) && $started_at->lessThanOrEqualTo($istirahatMalamSelesai)) {
                    $potongIstirahat += 2700; // 45 menit
                }
                $operation_time -= $potongIstirahat;
                $standard_cycle_time_seconds = 3600 / $machine->standard_sph;
                $actual_cycle_time_seconds = $operation_time / $machine->qty_actual;
                $ideal_time = $standard_cycle_time_seconds * $machine->qty_actual;
                $oee_performance = ($actual_cycle_time_seconds > 0)
                    ? ($ideal_time / $operation_time) * 100
                    : 0;
                $oee_performance = ceil($oee_performance);
                $planned_time = $operation_time;
                $downtime = $sumDowntime;
                $available_time = $planned_time - $downtime;
                $oee_availability = ($planned_time > 0)
                    ? round(($available_time / $planned_time) * 100, 2)
                    : 0;
            }
        } else {
            $oee_quality = 100;
            $oee_availability = 0;
            $oee_performance = 0;
        }

        $downtime = DB::table('log_downtime as d')
            ->join('downtime_list as l', 'd.downtime_id', '=', 'l.id')
            ->where('d.machine_id', $machine->machine_id)
            ->where('d.job_num', $machine->job_num)
            ->where('d.production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->select('l.name', DB::raw('SUM(d.downtime) as total_downtime'))
            ->groupBy('l.name')
            ->orderBy('l.name')
            ->get();

        $downtimeFive = $downtime->take(5);
        $downtimeOthers = $downtime->slice(5)->sum('total_downtime');

        if ($downtimeOthers > 0) {
            $downtimeFive->push((object) [
                'name' => 'Others',
                'total_downtime' => $downtimeOthers
            ]);
        }

        $downtimeChartLabels = $downtimeFive->pluck('name')->toArray();
        $downtimeChartValues = $downtimeFive->pluck('total_downtime')->toArray();

        $log_activity = DB::table("log_activity")
            ->where('machine_id', $machine->machine_id)
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->where('shift', $machine->shift)
            ->limit(5)
            ->get();
        $pagi = Carbon::createFromTime(7, 30);
        $sore = Carbon::createFromTime(16, 30);

        if (Carbon::now('Asia/Jakarta')->between($pagi, $sore)) {
            $start = Carbon::createFromTime(7, 30);
            $end = Carbon::createFromTime(16, 30);
        } else {
            $start = Carbon::createFromTime(16, 30);
            $end = Carbon::createFromTime(7, 0)->addDay();
        }
        $gsphRecord = DB::table('gsph_record')
            ->selectRaw("RIGHT('0' + CAST(DATEPART(HOUR, cut_off_time) AS VARCHAR), 2) + ':30' as hour_label, MAX(gsph) as gsph")
            ->where('machine_id', $machine->machine_id)
            ->where('tool_id', $machine->tool_id)
            ->where('job_num', $machine->job_num)
            ->where('shift', $machine->shift)
            ->where('cut_off', $date_sql)
            ->whereTime('cut_off_time', '>=', $start)
            ->whereTime('cut_off_time', '<=', $end)
            ->groupBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->orderBy(DB::raw("DATEPART(HOUR, cut_off_time)"))
            ->get();
        $gsphLabel = $gsphRecord->pluck('hour_label');
        $gsphValues = $gsphRecord->pluck('gsph');
        if ($oee_performance >= 100) {
            $oee_performance = 100;
        }
        if ($oee_availability >= 100) {
            $oee_availability = 100;
        }
        if ($oee_quality >= 100) {
            $oee_quality = 100;
        }
        return response()->json([
            'machine' => $machine,
            'downtimeChartLabels' => $downtimeChartLabels,
            'downtimeChartValues' => $downtimeChartValues,
            'gsphLabels' => $gsphLabel,
            'gsphValues' => $gsphValues,
            'oee' => $oee->first(),
            'oee_quality' => $oee_quality,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'activity' => $log_activity
        ]);
    }
    public function timeEntryV2(Request $request)
    {
        $machineID = $request->machineID;
        $data = LogMachine::find($machineID);
        $cekLabor = DB::connection('sqlsrv4')
            ->select('SELECT TOP 5 JobNum FROM [Erp].[LaborDtl]');
        //Default SHIFT
        if ($data->category_line_id == 'ASSY-002') {
            $shiftOptions = [
                ['id' => '1', 'text' => 'SHIFT 1'],
                ['id' => '6', 'text' => 'SHIFT 2 (2 SHIFT G1)'],
                ['id' => '7', 'text' => 'SHIFT 2 (2 SHIFT G1) JUMAT'],
                ['id' => '11', 'text' => 'SHIFT 2 (OT WEEK END)'],
                ['id' => '12', 'text' => 'SHIFT 1 (OT WEEK END)'],
                ['id' => '13', 'text' => 'SHIFT 1 (OT WEEK END V2)'],
            ];
        } else {
            $shiftOptions = [
                ['id' => '1', 'text' => 'SHIFT 1'],
                ['id' => '2', 'text' => 'SHIFT 2 (2 SHIFT G2)'],
                ['id' => '3', 'SHIFT 2 (2 SHIFT G2) JUMAT'],
            ];
        }
        if ($data) {
            return response()->json([
                'machine' => $data,
                'labor' => $cekLabor,
                'shift_options' => $shiftOptions
            ]);
        } else {
            return response()->json([
                'message' => 'Mesin tidak ditemukan'
            ], 404);
        }
    }
    public function timeEntryToolV2(Request $request)
    {
        $machineID = $request->machineID;
        $toolID = $request->toolID;
        $data = DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->first();
        $cekLabor = DB::connection('sqlsrv4')
            ->select('SELECT TOP 5 JobNum FROM [Erp].[LaborDtl]');
        //Default SHIFT

        $shiftOptions = [
            ['id' => '1', 'text' => 'SHIFT 1'],
            ['id' => '6', 'text' => 'SHIFT 2 (2 SHIFT G1)'],
            ['id' => '7', 'text' => 'SHIFT 2 (2 SHIFT G1) JUMAT'],
            ['id' => '11', 'text' => 'SHIFT 2 (OT WEEK END)'],
            ['id' => '12', 'text' => 'SHIFT 1 (OT WEEK END)'],
            ['id' => '13', 'text' => 'SHIFT 1 (OT WEEK END V2)'],
        ];
        if ($data) {
            return response()->json([
                'machine' => $data,
                'labor' => $cekLabor,
                'shift_options' => $shiftOptions
            ]);
        } else {
            return response()->json([
                'message' => 'Mesin tidak ditemukan'
            ], 404);
        }
    }
    public function createNewHeaderV2(Request $request)
    {
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/CreateNew', [
                    'employeeNum' => $request->employee,
                    'startDate' => $request->productionDate,
                    'nik' => $request->nik,
                    'password' => $request->password,
                ]);
        return response()->json($response->json());
    }
    public function changeShiftV2(Request $request)
    {
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/ChangeShift', [
                    'laborHedSeq' => $request->laborHedSeq,
                    'shift' => $request->shift,
                    'nik' => $request->nik,
                    'password' => $request->password
                ]);
        return response()->json($response->json());
    }
    public function updateHeaderV2(Request $request)
    {
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/UpdateHeader', [
                    'workDate' => $request->workDate,
                    'laborHedSeq' => $request->laborHedSeq,
                    'shift' => $request->shift,
                    'payHours' => $request->payHours,
                    'clockInDate' => $request->clockInDate,
                    'actualClockinDate' => $request->actualClockinDate,
                    'clockInTime' => $request->clockInTime,
                    'actualClockInTime' => $request->actualClockInTime,
                    'clockOutTime' => $request->clockOutTime,
                    'actualClockOutTime' => $request->actualClockOutTime,
                    'lunchOutTime' => $request->lunchOutTime,
                    'actLunchOutTime' => $request->actualLunchOutTime,
                    'lunchInTime' => $request->lunchInTime,
                    'actLunchInTime' => $request->actualLunchInTime,
                    'nik' => $request->nik,
                    'password' => $request->password
                ]);
        return response()->json($response->json());
    }
    public function getOpSeqV2(Request $request)
    {
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/GetOprSeq', [
                    'jobNum' => $request->jobNum,
                    'nik' => $request->nik,
                    'password' => $request->password
                ]);
        return response()->json($response->json());
    }
    public function getNewtLaborDtlV2(Request $request)
    {
        $data = [
            'laborTypePseudo' => $request->input('laborTypePseudo'),
            'laborHedSeq' => $request->input('laborHedSeq'),
            'jobNum' => $request->input('jobNum'),
            'opSeq' => $request->input('opSeq'),
            'date' => $request->input('date'),
            'nik' => $request->input('nik'),
            'password' => $request->input('password'),
            'resourceGrpID' => "",
            'resourceID' => "",
            'indirectCode' => "",
            'indirectDescription' => ""
        ];
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/GeNewtLaborDtl', $data);
        return response()->json($response->json());
    }
    public function changeLaborTimeV2(Request $request)
    {
        $data = [
            'laborHedSeq' => $request->laborHedSeq,
            'laborDtlSeq' => $request->laborDtlSeq,
            'shift' => $request->shift,
            'shiftDescription' => "",
            'clockinTime' => $request->clockinTime,
            'clockOutTime' => $request->clockOutTime,
            'nik' => $request->nik,
            'password' => $request->password
        ];
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/ChangeLaborTime', $data);
        return response()->json($response->json());
    }
    public function updateDtlV2(Request $request)
    {
        $laborQty = $request->laborQty;
        $laborQty = is_numeric($laborQty) ? (int) $laborQty : 0;
        if ($request->resourceGrpID == '5J45' || $request->resourceGrpID == '5H45') {
            $resourceGrp = 'RBT-' . $request->resourceGrpID;
        } else {
            $resourceGrp = $request->resourceGrpID;
        }
        $data = [
            'laborHedSeq' => (int) $request->laborHedSeq,
            'laborDtlSeq' => (int) $request->laborDtlSeq,
            'date' => (string) $request->date,
            'clockInDate' => (string) $request->clockInDate,
            'clockinTime' => (string) $request->clockinTime,
            'clockOutTime' => (string) $request->clockOutTime,
            'laborHrs' => (float) $request->laborHrs,
            'burdenHrs' => (float) $request->burdenHrs,
            // 'laborQty' => (float) $request->laborQty,
            'laborQty' => $laborQty,
            'scrapQty' => (float) $request->scrapQty,
            'discrepQty' => (float) $request->discrepQty,
            'discrpRsnCode' => (string) $request->discrpRsnCode,
            'scrapReasonCode' => (string) $request->scrapReasonCode,
            'resourceGrpID' => $resourceGrp,
            'resourceID' => (string) $request->resourceID,
            'resourceGrpDescription' => (string) $request->resourceGrpDescription,
            'indirectCode' => (string) $request->indirectCode,
            'laborNote' => (string) $request->laborNote,
            'rowMod' => (string) $request->rowMod,
            'nik' => (string) $request->nik,
            'password' => (string) $request->password
        ];
        // Log::info($data);
        // dd($data);
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/UpdateDtl', $data);
        return response()->json($response->json());
    }
    public function submitTimeEntryV2(Request $request)
    {
        $url = config('services.epicor_app.url');
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($url . '/Labor/Submit', [
                    'laborHedSeq' => $request->laborHedSeq,
                    'laborDtlSeq' => $request->laborDtlSeq,
                    'nik' => $request->nik,
                    'password' => $request->password
                ]);
        return response()->json($response->json());
    }
    public function show_sop(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of($this->LogMachine->show_sop())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $dtl = Crypt::encryptString($row->ID . '~' . $row->PartNum);
                    return '
                    <div class="flex justify-arround">
                    <button class="btn btn-primary btn-sm" onclick="ViewBtn(\'' . $dtl . '\')">View</button>
                    </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function showPartNum(Request $request)
    {
        $search = $request->input('search', '');
        $page = (int) $request->input('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $rows = DB::connection('sqlsrv4')->select("
        SELECT
            CAST(PartNum AS NVARCHAR(100)) AS id,
            CAST(PartNum AS NVARCHAR(100)) + ' - ' +
            CAST(PartDescription AS NVARCHAR(255)) AS text
        FROM Erp.Part
        WHERE PartNum LIKE ?
        ORDER BY PartNum
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
    ", ["%{$search}%", $offset, $limit + 1]);

        $more = count($rows) > $limit;

        if ($more) {
            array_pop($rows);
        }

        return response()->json([
            'data' => $rows,
            'pagination' => [
                'more' => $more
            ]
        ]);
    }
    public function store(Request $request)
    {
        $Part = $request->partNum;
        $Title = $request->title;
        $Step = $request->step;
        $Desc = $request->description;
        $checkStep = $this->LogMachine->checkStep($Part, $Step);
        if ($checkStep) {
            return response()->json([
                'status' => 'error',
                'message' => 'Step ini sudah ada'
            ]);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = strtolower(str_replace(' ', '', $originalName));
            $extension = $file->getClientOriginalExtension();
            $filename = $cleanName . '_' . time() . '.' . $extension;
            $file->move(public_path('menu_sop'), $filename);
            $image = $filename;
        } else {
            $image = null;
        }
        $this->LogMachine->StoreSop([
            'PartNum' => $Part,
            'Step' => $Step,
            'Title' => $Title,
            'Image' => $image,
            'Description' => $Desc,
            'CreatedAt' => now('Asia/Jakarta')
        ]);
        return response()->json(['status' => 'success', 'message' => 'Data berhasil dibuat']);
    }
    public function delete_all(Request $request)
    {
        $Part = $request->Part;
        $check = $this->LogMachine->CheckPart($Part);
        if (!$check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
        if ($check->Image && file_exists(public_path('menu_sop/' . $check->Image))) {
            unlink(public_path('menu_sop/' . $check->Image));
        }
        $this->LogMachine->DeleteAll($Part);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhaasil di hapus'
        ]);
    }
    public function edit_show(Request $request)
    {
        $id = $request->id;
        $data = $this->LogMachine->FindID($id);
        return response()->json(['data' => $data]);
    }
    public function updateSop(Request $request)
    {
        $id = $request->id;
        $Part = $request->partNum;
        $Title = $request->title;
        $Desc = $request->description;
        $check = $this->LogMachine->FindID($id);
        if (!$check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = strtolower(str_replace(' ', '', $originalName));
            $extension = $file->getClientOriginalExtension();
            $filename = $cleanName . '_' . time() . '.' . $extension;
            if ($check->Image && file_exists(public_path('menu_sop/' . $check->Image))) {
                unlink(public_path('menu_sop/' . $check->Image));
            }
            $file->move(public_path('menu_sop'), $filename);
            $image = $filename;
        } else {
            $image = $check->Image;
        }
        $this->LogMachine->updateSop($id, [
            'Title' => $Title,
            'Description' => $Desc,
            'Image' => $image
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil di ubah'
        ]);
    }
    public function deleteSop(Request $request)
    {
        $id = $request->id;
        $check = $this->LogMachine->FindID($id);
        if (!$check) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
        if ($check->Image && file_exists(public_path('menu_sop/' . $check->Image))) {
            unlink(public_path('menu_sop/' . $check->Image));
        }
        $this->LogMachine->deleteFind($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil di hapus'
        ]);
    }
}
