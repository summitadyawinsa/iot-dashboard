<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    public function main_dashboard($emp_id)
    {
        return DB::table('users as a')
            ->leftJoin('log_header_machine as b', 'a.employee_id', '=', 'b.employee_id')
            ->leftJoin('log_downtime as c', function ($join) {
                $join->on('b.machine_id', '=', 'c.machine_id')
                    ->on('b.production_date', '=', 'c.production_date')
                    ->where('c.is_active', 1);
            })
            ->leftJoin('oee_log_machine as e', function ($join) {
                $join->on('b.machine_id', '=', 'e.machine_id')
                    ->on('b.job_num', '=', 'e.job_num')
                    ->on('b.production_date', '=', 'e.production_date');
            })
            ->leftJoin('downtime_list as d', 'c.downtime_id', '=', 'd.id')
            ->select('a.name', 'a.username', 'b.*', 'c.started_at as start_dt', 'd.name as nama_dt')
            ->where('a.id', $emp_id)
            ->first();
    }
    public function activity($emp_id)
    {
        $machine = DB::table('log_header_machine as a')
            ->leftJoin('users as b', 'b.employee_id', '=', 'a.employee_id')
            ->where('b.id', $emp_id)
            ->where('is_active', 1)
            ->select('a.machine_id', 'a.job_num', 'a.production_date')
            ->first();
        // dd($machine);
        return DB::table('log_activity')
            ->where('machine_id', $machine->machine_id)
            ->where('job_num', $machine->job_num)
            ->where('production_date', $machine->production_date)
            ->get();
    }
    public function act_summary($emp_id)
    {
        $data = DB::table('history_header_machine as a')
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
            ->where('a.employee_id', $emp_id)
            ->selectRaw('
            SUM(a.qty_plan) as total_qty_plan,
            SUM(a.qty_actual) as total_qty_actual,
            SUM(a.qty_ng) as total_qty_ng,
            SUM(a.operation_time) as total_operation_time,
            SUM(a.standard_sph) as total_standard_sph,
            SUM(b.downtime) as total_downtime
        ')
            ->first();

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
}
