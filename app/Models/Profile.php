<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    public function main_dashboard_by_emp($emp_id)
    {
        $data = DB::table('users as a')
            ->leftJoin('log_header_machine as b', 'a.employee_id', '=', 'b.employee_id')
            ->leftJoin('log_downtime as c', function ($join) {
                $join->on('b.machine_id', '=', 'c.machine_id')
                    ->on('b.production_date', '=', 'c.production_date')
                    ->where('c.is_active', 1);
            })
            ->leftJoin('downtime_list as d', 'c.downtime_id', '=', 'd.id')
            ->select('a.name', 'a.username', 'a.avatar', 'b.*', 'c.started_at as start_dt', 'd.name as nama_dt', 'd.type as dt_type')
            ->where('a.id', $emp_id)
            ->first();
        if ($data->machine_id == null) {
            $data = DB::table('users as a')
                ->leftJoin('log_machine_tool as b', 'a.employee_id', '=', 'b.employee_id')
                ->leftJoin('log_downtime as c', function ($join) {
                    $join->on('b.machine_id', '=', 'c.machine_id')
                        ->on('b.production_date', '=', 'c.production_date')
                        ->where('c.is_active', 1);
                })
                ->leftJoin('downtime_list as d', 'c.downtime_id', '=', 'd.id')
                ->select('a.name', 'a.username', 'a.avatar', 'b.*', 'c.started_at as start_dt', 'd.name as nama_dt', 'd.type as dt_type')
                ->where('a.id', $emp_id)
                ->first();
        }
        return $data;
    }
    public function main_dashboard_by_machine($machineID)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $data = DB::table('log_machine_tool as b')
                ->leftJoin('users as a', 'a.employee_id', '=', 'b.employee_id')
                ->leftJoin('log_downtime as c', function ($join) {
                    $join->on('b.machine_id', '=', 'c.machine_id')
                        ->on('b.production_date', '=', 'c.production_date')
                        ->where('c.is_active', 1);
                })
                ->leftJoin('downtime_list as d', 'c.downtime_id', '=', 'd.id')
                ->select('a.name', 'a.username', 'a.avatar', 'b.*', 'c.started_at as start_dt', 'd.name as nama_dt', 'd.type as dt_type')
                ->where('b.machine_id', (string) $machine_id[0])
                ->where('b.tool_id', $machine_id[1])
                ->first();
        } else {
            $data = DB::table('log_header_machine as b')
                ->leftJoin('users as a', 'a.employee_id', '=', 'b.employee_id')
                ->leftJoin('log_downtime as c', function ($join) {
                    $join->on('b.machine_id', '=', 'c.machine_id')
                        ->on('b.production_date', '=', 'c.production_date')
                        ->where('c.is_active', 1);
                })
                ->leftJoin('downtime_list as d', 'c.downtime_id', '=', 'd.id')
                ->select('a.name', 'a.username', 'a.avatar', 'b.*', 'c.started_at as start_dt', 'd.name as nama_dt', 'd.type as dt_type')
                ->where('b.machine_id', (string) $machineID)
                ->first();
        }
        return $data;
    }
    public function option_tool($emp_id)
    {
        return DB::table('users as a')
            ->leftJoin('log_machine_tool as b', 'a.employee_id', '=', 'b.employee_id')
            ->select('b.job_num')
            ->where('a.id', $emp_id)
            ->get();
    }
    public function activity($emp_id)
    {
        $machine = DB::table('log_header_machine as a')
            ->leftJoin('users as b', 'b.employee_id', '=', 'a.employee_id')
            ->where('b.id', $emp_id)
            ->where('a.is_active', 1)
            ->select('a.machine_id', 'a.job_num', 'a.production_date')
            ->first();
        if (!$machine) {
            $machine = DB::table('log_machine_tool as a')
                ->leftJoin('users as b', 'b.employee_id', '=', 'a.employee_id')
                ->where('b.id', $emp_id)
                ->where('a.is_active', 1)
                ->select('a.machine_id', 'a.job_num', 'a.production_date', 'a.tool_id')
                ->first();
            if (!$machine) {
                return [];
            }
            return DB::table('log_activity')
                ->where('machine_id', $machine->machine_id)
                ->where('job_num', $machine->job_num)
                ->where('production_date', $machine->production_date)
                ->where('tool_id', $machine->tool_id)
                ->get();
        }
        return DB::table('log_activity')
            ->where('machine_id', $machine->machine_id)
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->get();
    }
    public function activity_by_machine($machineID)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $machine = DB::table('log_machine_tool as a')
                ->leftJoin('users as b', 'b.employee_id', '=', 'a.employee_id')
                ->where('a.machine_id', $machine_id[0])
                ->where('a.tool_id', $machine_id[1])
                ->where('a.is_active', 1)
                ->select('a.machine_id', 'a.job_num', 'a.production_date', 'a.tool_id')
                ->first();
            $log = DB::table('log_activity')
                ->where('machine_id', $machine->machine_id)
                ->where('job_num', $machine->job_num)
                ->where('production_date', $machine->production_date)
                ->where('tool_id', $machine->tool_id)
                ->get();
        } else {
            $machine = DB::table('log_header_machine as a')
                ->leftJoin('users as b', 'b.employee_id', '=', 'a.employee_id')
                ->where('a.machine_id', $machineID)
                ->where('a.is_active', 1)
                ->select('a.machine_id', 'a.job_num', 'a.production_date')
                ->first();
            $log = DB::table('log_activity')
                ->where('machine_id', $machine->machine_id)
                ->where('job_num', $machine->job_num)
                ->where('production_date', $machine->production_date)
                ->get();
        }
        return $log;
    }
    public function act_summary($emp_id, $production_date)
    {
        $users = DB::table('users')
            ->where('id', $emp_id)
            ->first();
        $machine = DB::table('log_header_machine')
            ->where('employee_id', $users->employee_id)
            ->where('production_date', $production_date)
            ->first();
        if ($machine) {
            $data = DB::table('log_header_machine as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->leftJoin('oee_log_machine as c', function ($join) {
                    $join->on('a.machine_id', '=', 'c.machine_id')
                        ->on('a.job_num', '=', 'c.job_num')
                        ->on('a.production_date', '=', 'c.production_date');
                })
                ->where('a.employee_id', $users->employee_id)
                ->where('a.production_date', $production_date)
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->first();
        } else {
            $data = DB::table('log_machine_tool as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->leftJoin('oee_log_machine as c', function ($join) {
                    $join->on('a.machine_id', '=', 'c.machine_id')
                        ->on('a.job_num', '=', 'c.job_num')
                        ->on('a.production_date', '=', 'c.production_date');
                })
                ->where('a.employee_id', $users->employee_id)
                ->where('a.production_date', $production_date)
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->first();
        }
        if (!$data)
            return null;
        $totalDowntimeSeconds = $data->total_downtime ?? 0;
        $totalDowntimeMinutes = $totalDowntimeSeconds / 60;
        if ($totalDowntimeMinutes >= 60) {
            $downtimeDisplay = round($totalDowntimeMinutes / 60, 2) . ' Jam';
        } else {
            $downtimeDisplay = round($totalDowntimeMinutes, 2) . ' Menit';
        }
        $operation_time = $data->total_operation_time ?? 0;
        $operasi_time = $operation_time / 60;
        $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
        $standard_sph = $data->total_standard_sph ?? 0;
        $qty_actual = $data->total_qty_actual ?? 0;
        $qty_ng = $data->total_qty_ng ?? 0;
        $oee_quality = ($qty_actual > 0 && $qty_ng > 0)
            ? 100 - ceil(($qty_ng / $qty_actual) * 100)
            : 0;
        if ($standard_sph > 0 && $qty_actual > 0 && $waktuOperasi > 0) {
            $standardCT = 60 / $standard_sph;
            $oee_performance = ($standardCT * $qty_actual / $waktuOperasi) * 100;
        } else {
            $oee_performance = 0;
        }
        $oee_availability = ($operasi_time > 0)
            ? ($waktuOperasi / $operasi_time) * 100
            : 0;

        return [
            'total_qty_plan' => $data->total_qty_plan ?? 0,
            'total_qty_actual' => $data->total_qty_actual ?? 0,
            'total_downtime' => $downtimeDisplay,
            'availability' => round($oee_availability, 2),
            'performance' => round($oee_performance, 2),
            'quality' => round($oee_quality, 2),
        ];
    }
    public function act_summary_by_machine($machineID, $production_date)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $data = DB::table('log_machine_tool as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->leftJoin('oee_log_machine as c', function ($join) {
                    $join->on('a.machine_id', '=', 'c.machine_id')
                        ->on('a.job_num', '=', 'c.job_num')
                        ->on('a.production_date', '=', 'c.production_date');
                })
                ->where('a.machine_id', $machine_id[0])
                ->where('a.tool_id', $machine_id[1])
                ->where('a.production_date', $production_date)
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->first();
        } else {
            $data = DB::table('log_header_machine as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->leftJoin('oee_log_machine as c', function ($join) {
                    $join->on('a.machine_id', '=', 'c.machine_id')
                        ->on('a.job_num', '=', 'c.job_num')
                        ->on('a.production_date', '=', 'c.production_date');
                })
                ->where('a.machine_id', $machineID)
                ->where('a.production_date', $production_date)
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->first();
        }
        if (!$data)
            return null;
        $totalDowntimeSeconds = $data->total_downtime ?? 0;
        $totalDowntimeMinutes = $totalDowntimeSeconds / 60;
        if ($totalDowntimeMinutes >= 60) {
            $downtimeDisplay = round($totalDowntimeMinutes / 60, 2) . ' Jam';
        } else {
            $downtimeDisplay = round($totalDowntimeMinutes, 2) . ' Menit';
        }
        $operation_time = $data->total_operation_time ?? 0;
        $operasi_time = $operation_time / 60;
        $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
        $standard_sph = $data->total_standard_sph ?? 0;
        $qty_actual = $data->total_qty_actual ?? 0;
        $qty_ng = $data->total_qty_ng ?? 0;
        $oee_quality = ($qty_actual > 0 && $qty_ng > 0)
            ? 100 - ceil(($qty_ng / $qty_actual) * 100)
            : 0;
        if ($standard_sph > 0 && $qty_actual > 0 && $waktuOperasi > 0) {
            $standardCT = 60 / $standard_sph;
            $oee_performance = ($standardCT * $qty_actual / $waktuOperasi) * 100;
        } else {
            $oee_performance = 0;
        }
        $oee_availability = ($operasi_time > 0)
            ? ($waktuOperasi / $operasi_time) * 100
            : 0;

        return [
            'total_qty_plan' => $data->total_qty_plan ?? 0,
            'total_qty_actual' => $data->total_qty_actual ?? 0,
            'total_downtime' => $downtimeDisplay,
            'availability' => round($oee_availability, 2),
            'performance' => round($oee_performance, 2),
            'quality' => round($oee_quality, 2),
        ];
    }
    public function oee_current($emp_id)
    {
        $machine = DB::table('log_header_machine')
            ->where('employee_id', $emp_id)
            ->first();
        if ($machine) {
            $data = DB::table('log_header_machine as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->where('a.employee_id', $emp_id)
                ->first();
        } else {
            $data = DB::table('log_machine_tool as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->where('a.employee_id', $emp_id)
                ->first();
        }
        if (!$data)
            return null;
        $totalDowntimeSeconds = $data->downtime ?? 0;
        $totalDowntimeMinutes = $totalDowntimeSeconds / 60;
        if ($totalDowntimeMinutes >= 60) {
            $downtimeDisplay = round($totalDowntimeMinutes / 60, 2) . ' Jam';
        } else {
            $downtimeDisplay = round($totalDowntimeMinutes, 2) . ' Menit';
        }
        $operation_time = $data->operation_time ?? 0;
        $operasi_time = $operation_time / 60;
        $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
        $standard_sph = $data->standard_sph ?? 0;
        $qty_actual = $data->qty_actual ?? 0;
        $qty_ng = $data->qty_ng ?? 0;
        $oee_quality = ($qty_actual > 0 && $qty_ng > 0)
            ? 100 - ceil(($qty_ng / $qty_actual) * 100)
            : 0;
        if ($standard_sph > 0 && $qty_actual > 0 && $waktuOperasi > 0) {
            $standardCT = 60 / $standard_sph;
            $oee_performance = ($standardCT * $qty_actual / $waktuOperasi) * 100;
        } else {
            $oee_performance = 0;
        }
        $oee_availability = ($operasi_time > 0)
            ? ($waktuOperasi / $operasi_time) * 100
            : 0;

        return [
            'total_qty_plan' => $data->qty_plan ?? 0,
            'total_qty_actual' => $data->qty_actual ?? 0,
            'total_downtime' => $downtimeDisplay,
            'availability' => round($oee_availability, 2),
            'performance' => round($oee_performance, 2),
            'quality' => round($oee_quality, 2),
        ];
    }
    public function oee_current_by_machine($machineID)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $data = DB::table('log_machine_tool as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->where('a.machine_id', $machine_id[0])
                ->where('a.tool_id', $machine_id[1])
                ->first();
        } else {
            $data = DB::table('log_header_machine as a')
                ->leftJoin('log_downtime as b', function ($join) {
                    $join->on('a.machine_id', '=', 'b.machine_id')
                        ->on('a.job_num', '=', 'b.job_num')
                        ->on('a.production_date', '=', 'b.production_date');
                })
                ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
                ->where('a.machine_id', $machineID)
                ->first();
        }
        if (!$data)
            return null;
        $totalDowntimeSeconds = $data->downtime ?? 0;
        $totalDowntimeMinutes = $totalDowntimeSeconds / 60;
        if ($totalDowntimeMinutes >= 60) {
            $downtimeDisplay = round($totalDowntimeMinutes / 60, 2) . ' Jam';
        } else {
            $downtimeDisplay = round($totalDowntimeMinutes, 2) . ' Menit';
        }
        $operation_time = $data->operation_time ?? 0;
        $operasi_time = $operation_time / 60;
        $waktuOperasi = $operasi_time - $totalDowntimeMinutes;
        $standard_sph = $data->standard_sph ?? 0;
        $qty_actual = $data->qty_actual ?? 0;
        $qty_ng = $data->qty_ng ?? 0;
        $oee_quality = ($qty_actual > 0 && $qty_ng > 0)
            ? 100 - ceil(($qty_ng / $qty_actual) * 100)
            : 0;
        if ($standard_sph > 0 && $qty_actual > 0 && $waktuOperasi > 0) {
            $standardCT = 60 / $standard_sph;
            $oee_performance = ($standardCT * $qty_actual / $waktuOperasi) * 100;
        } else {
            $oee_performance = 0;
        }
        $oee_availability = ($operasi_time > 0)
            ? ($waktuOperasi / $operasi_time) * 100
            : 0;

        return [
            'total_qty_plan' => $data->qty_plan ?? 0,
            'total_qty_actual' => $data->qty_actual ?? 0,
            'total_downtime' => $downtimeDisplay,
            'availability' => round($oee_availability, 2),
            'performance' => round($oee_performance, 2),
            'quality' => round($oee_quality, 2),
        ];
    }
    public function dt_log($machine_id, $tool_id, $job_num, $shift, $production_date)
    {
        return DB::table('log_downtime as a')
            ->leftJoin('downtime_list as b', 'b.id', '=', 'a.downtime_id')
            ->select('b.name', 'a.downtime')
            ->where('a.machine_id', $machine_id)
            ->where('a.job_num', $job_num)
            ->where('a.shift', $shift)
            ->where('a.production_date', $production_date)
            ->where('a.tool_id', $tool_id)
            ->limit(5)
            ->get();
    }
    public function machine_data_by_emp($empID)
    {
        $data = DB::table('users as a')
            ->leftJoin('log_header_machine as b', 'a.employee_id', '=', 'b.employee_id')
            ->select('b.qty_plan as target', 'b.qty_actual as actual')
            ->where('a.id', $empID)
            ->first();
        if ($data->target == null) {
            $data = DB::table('users as a')
                ->leftJoin('log_machine_tool as b', 'a.employee_id', '=', 'b.employee_id')
                ->select('b.qty_plan as target', 'b.qty_actual as actual')
                ->where('a.id', $empID)
                ->first();
        }
        return $data;
    }
    public function machine_data_by_machine($machineID)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $data = DB::table('log_machine_tool as b')
                ->select('b.qty_plan as target', 'b.qty_actual as actual')
                ->where('b.machine_id', $machine_id[0])
                ->where('b.tool_id', $machine_id[1])
                ->first();
        } else {
            $data = DB::table('log_header_machine as b')
                ->select('b.qty_plan as target', 'b.qty_actual as actual')
                ->where('b.machine_id', $machineID)
                ->first();
        }
        return $data;
    }
    public function main_gsph_by_employee($employee_id)
    {
        $emp_id = DB::table('users')
            ->where('id', $employee_id)
            ->value('employee_id');
        $data_machine = DB::table('log_header_machine')
            ->where('employee_id', $emp_id)
            ->where('is_active', 1)
            ->first();
        if (!$data_machine) {
            $data_machine = DB::table('log_machine_tool')
                ->where('employee_id', $emp_id)
                ->where('is_active', 1)
                ->first();
        }
        return DB::table('gsph_record')
            ->where('machine_id', $data_machine->machine_id)
            ->where('cut_off', Carbon::today())
            ->where('shift', $data_machine->shift)
            ->where('job_num', $data_machine->job_num)
            ->select('machine_id', 'gsph', DB::raw("FORMAT(cut_off_time,'HH:mm') as cut_off_time"))
            ->get();
    }
    public function main_gsph_by_machine($machineID)
    {
        if ($machineID == 'RSW-5H45-10~4' || $machineID == 'RSW-5H45-10~3' || $machineID == 'RSW-5H45-10~2' || $machineID == 'RSW-5H45-10~1' || $machineID == 'RSW-5H45-09~1' || $machineID == 'RSW-5H45-09~2') {
            $machine_id = explode('~', $machineID);
            $data_machine = DB::table('log_machine_tool')
                ->where('machine_id', $machine_id[0])
                ->where('tool_id', $machine_id[1])
                ->where('is_active', 1)
                ->first();
            $gsph = DB::table('gsph_record')
                ->where('machine_id', $data_machine->machine_id)
                ->where('tool_id', $data_machine->tool_id)
                ->where('cut_off', Carbon::today())
                ->where('shift', $data_machine->shift)
                ->where('job_num', $data_machine->job_num)
                ->select('machine_id', 'gsph', DB::raw("FORMAT(cut_off_time,'HH:mm') as cut_off_time"))
                ->get();
        } else {
            $data_machine = DB::table('log_header_machine')
                ->where('machine_id', $machineID)
                ->where('is_active', 1)
                ->first();
            $gsph = DB::table('gsph_record')
                ->where('machine_id', $data_machine->machine_id)
                ->where('cut_off', Carbon::today())
                ->where('shift', $data_machine->shift)
                ->where('job_num', $data_machine->job_num)
                ->select('machine_id', 'gsph', DB::raw("FORMAT(cut_off_time,'HH:mm') as cut_off_time"))
                ->get();
        }
        return $gsph;
    }
    public function show_jo($machine, $date)
    {
        return DB::table('log_machine_tool')
            ->select('job_num')
            ->where('machine_id', $machine)
            ->where('production_date', $date)
            ->get();
    }
    public function change_jo($jo)
    {
        return DB::table('log_machine_tool')
            ->where('job_num', $jo)
            ->first();
    }
    public function queryTablePage($line_id)
    {
        return DB::table('log_header_machine')
            ->where('category_line_id', $line_id)
            ->where('is_active', true)
            ->whereNotNull('started_at')
            ->where('status_time_entry', false)
            ->select([
                'machine_id',
                'job_num',
                'part_no',
                'qty_plan',
                'qty_actual',
                'qty_ok',
                'started_at',
                'finished_at',
                'operation_time'
            ]);
    }
    public function dashboard_machine($machine_id, $tool_id)
    {
        if ($tool_id == null) {
            $data = DB::table('log_header_machine')
                ->where('machine_id', $machine_id)
                ->where('is_active', 1)
                ->where('condition_id', 1)
                ->first();
        } else {
            $data = DB::table('log_machine_tool')
                ->where('machine_id', $machine_id)
                ->where('tool_id', $tool_id)
                ->where('is_active', 1)
                ->where('condition_id', 1)
                ->first();
        }
        return $data;
    }
    public function dashboard_gsph_by_machine($machine_id, $job_num, $tool_id)
    {
        $query = DB::table('gsph_record')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num);
        if ($tool_id != null) {
            $query->where('tool_id', $tool_id);
        }
        $data = $query
            ->select('cut_off_time', 'qty_actual')
            ->orderBy('cut_off_time', 'asc')
            ->skip(1)
            ->get();

        return $data;
    }
    public function dashboard_oee_by_machine($machine_id, $job_num, $tool_id)
    {
        $query = DB::table('oee_log_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num);
        if ($tool_id != null) {
            $query->where('tool_id', $tool_id);
        }
        return $query->first();
    }
    public function dashboard_activity_by_machine($machine_id, $job_num, $tool_id)
    {
        $query = DB::table('log_activity')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num);
        if ($tool_id != null) {
            $query->where('tool_id', $tool_id);
        }
        return $query->orderBy('start_date', 'asc')->limit(3)->get();
    }
    public function dashboard_next_schedule($machine_id)
    {
        $sql = "
        SELECT DISTINCT TOP 2
    T1.DueDate,
    T1.IUM,
    T1.ProdQty,
    T1.ReqDueDate,
    T1.StartDate,
    T1.AnalysisCode,

    T2.AssemblySeq,
    T2.BomSequence,
    T2.Company,
    T2.[Description],
    T2.DrawNum,
    T2.IUM AS JobAsmbl_IUM,
    T2.JobNum,
    T2.OverRunQty,
    T2.PartNum,
    T2.PullQty,
    T2.RequiredQty,
    T2.RevisionNum,

    T3.CommentText,
    T3.Instructions,
    T3.DaysOut,
    T3.DueDate AS JobOper_DueDate,
    T3.EstProdHours,
    T3.EstSetHours,
    T3.Machines,
    T3.OpCode,
    T3.OpDesc,
    T3.OprSeq,
    T3.PrimaryProdOpDtl,
    T3.PrimarySetupOpDtl,
    T3.ProdStandard,
    T3.RunQty,
    T3.StartDate AS JobOper_StartDate,
    T3.StdFormat,

    T4.CapabilityID,
    T4.ConcurrentCapacity,
    T4.DailyProdRate,
    T4.OpDtlSeq,
    T4.ProdCrewSize,
    T4.ResourceGrpID,
    T4.ResourceID,
    T4.SetUpCrewSize,
    T4.SetupOrProd,

    T5.ResourceGrpID AS ResourceTimeUsed_ResourceGrpID,
    T5.ResourceID AS ResourceTimeUsed_ResourceID,
    T5.WhatIf,

    T1.JobCode,
    T1.ProdCode

FROM [App].Erp.JobHead T1

LEFT OUTER JOIN [App].Erp.JobAsmbl T2
    ON T1.Company = T2.Company
    AND T1.JobNum = T2.JobNum

LEFT OUTER JOIN [App].Erp.JobOper T3
    ON T2.Company = T3.Company
    AND T2.JobNum = T3.JobNum
    AND T2.AssemblySeq = T3.AssemblySeq

LEFT OUTER JOIN [App].Erp.JobOpDtl T4
    ON T3.Company = T4.Company
    AND T3.JobNum = T4.JobNum
    AND T3.AssemblySeq = T4.AssemblySeq
    AND T3.OprSeq = T4.OprSeq

LEFT OUTER JOIN [App].Erp.ResourceTimeUsed T5
    ON T4.Company = T5.Company
    AND T4.JobNum = T5.JobNum
    AND T4.AssemblySeq = T5.AssemblySeq
    AND T4.OprSeq = T5.OprSeq
    AND T4.OpDtlSeq = T5.OpDtlSeq

WHERE T3.LaborEntryMethod = 'T'
    AND T1.JobReleased = 1
    AND CAST(T1.DueDate AS DATE) = CAST(GETDATE() AS DATE)
	AND T5.ResourceID = ?
        ";
        return DB::connection('sqlsrv4')->select($sql, [$machine_id]);
    }
}
