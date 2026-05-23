<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Config extends Model
{
    public function shift_get_all($category)
    {
        $query = DB::connection('sqlsrv4')
            ->table('Erp.JCShift')
            ->select('Shift', 'Description');
        if ($category == 'assy') {
            $query->whereIn('Shift', [1, 6, 7, 11, 12, 13]);
        } else {
            $query->whereIn('Shift', [1, 2, 3, 4, 5, 8, 9, 10]);
        }
        return $query->get();
    }
    public function get_job_all($search, $category)
    {
        if ($category == 'assy') {
            $category = 'ASY';
        } else {
            $category = 'STP';
        }
        $query = DB::connection('sqlsrv4')
            ->table('Erp.JobHead')
            ->select(
                DB::raw('JobNum AS id'),
                DB::raw('JobNum AS text'),
                DB::raw('ProdCode')
            )
            ->whereBetween('DueDate', [
                Carbon::now()->subDays(30)->startOfDay(),
                Carbon::now()->addDay()->endOfDay()
            ])
            ->where('JobNum', 'LIKE', '%' . $category . '%')
            ->where('JobClosed', 0);
        if (!empty($search)) {
            $query->where('JobNum', 'LIKE', "%{$search}%");
        }

        return $query
            ->orderBy('JobNum')
            ->get();
    }
    public function get_job_all_count($search, $category)
    {
        $query = DB::connection('sqlsrv4')
            ->table('Erp.JobHead')
            ->whereBetween('DueDate', [
                Carbon::now()->subDays(30)->startOfDay(),
                Carbon::now()->addDay()->endOfDay()
            ])
            ->where('JobNum', 'LIKE', '%' . $category . '%')
            ->where('JobClosed', 0);

        if (!empty($search)) {
            $query->where('JobNum', 'LIKE', "%{$search}%");
        }

        return $query->count();
    }
    public function get_downtime($search, $dept, $offset, $limit)
    {
        $category = explode('-', $dept);
        $query = DB::table('downtime_list')
            ->where('category_id', $category[0])
            ->select('id', 'name as text');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }
        return $query->orderBy('name')->offset($offset)->limit($limit)->get();
    }
    public function get_downtime_all_count($search, $dept)
    {
        $category = explode('-', $dept);
        $query = DB::table('downtime_list')
            ->where('category_id', $category[0])
            ->select('id', 'name as text');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('code', 'LIKE', '%' . $search . '%');
            });
        }
        return $query->count();
    }
    public function machine_data($machine_id)
    {
        return DB::table('log_header_machine')
            ->where('machine_id', $machine_id)
            ->first();
    }
    public function machine_data_tool($machine_id, $tool_id)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machine_id)
            ->where('tool_id', $tool_id)
            ->first();
    }
    public function downtime_log($machine_id, $job_num, $tool_id = null)
    {
        return DB::table('log_downtime')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->when($tool_id, function ($query, $tool_id) {
                return $query->where('tool_id', $tool_id);
            })
            ->whereDate('production_date', date('Y-m-d'))
            ->where('is_active', 1)
            ->first();
    }
    public function get_employee($search, $offset, $limit)
    {
        $query = DB::connection('sqlsrv4')
            ->table('Erp.EmpBasic')
            ->select(
                DB::raw("EmpID + '~' + Name AS id"),
                DB::raw('Name AS text'),
            );
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', '%' . $search . '%');
            });
        }
        return $query->offset($offset)->limit($limit)->get();
    }
    public function get_employee_count($search)
    {
        $query = DB::connection('sqlsrv4')
            ->table('Erp.EmpBasic')
            ->select(
                DB::raw('EmpID AS id'),
                DB::raw('Name AS text'),
            );
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'LIKE', '%' . $search . '%');
            });
        }
        return $query->count();
    }
    public function update_confirm($machine, $data)
    {
        DB::table('log_machine_confirm')->updateOrInsert(
            ['machine_id' => $machine], // kondisi cek
            $data // data yang diupdate / insert
        );
    }
    public function update_setup($machine, $data)
    {
        return DB::table('log_header_machine')
            ->where('machine_id', $machine)
            ->update($data);
    }
    public function update_setup_tool($machine, $data, $tool_id)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machine)
            ->where('tool_id', $tool_id)
            ->update($data);
    }
    public function setup_jo_lanjutan($machineId, $job_num)
    {
        return DB::table('history_header_machine')
            ->select('qty_actual')
            ->where('machine_id', $machineId)
            ->where('job_num', $job_num)
            ->whereDate('production_date', date('Y-m-d'))
            ->first();
    }
    public function downtime_list_id($id)
    {
        return DB::table('downtime_list')
            ->where('id', $id)
            ->first();
    }
    public function insert_downtime($data)
    {
        return DB::table('log_downtime')
            ->insertGetId($data);
    }
    public function insert_activity($data)
    {
        return DB::table('log_activity')
            ->insertGetId($data);
    }
    public function downtime_update($machine, $job_num, $production_date)
    {
        $query = DB::table('log_downtime')
            ->where('machine_id', $machine)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date);
        $log_dt = $query->first();
        $finish = now('Asia/Jakarta');
        $start = Carbon::parse($log_dt->started_at, 'Asia/Jakarta');
        $diffSeconds = $start->diffInSeconds($finish);
        $downtimeFormatted = $diffSeconds / 3600;
        return $query->update([
            'finished_at' => $finish,
            'downtime' => $downtimeFormatted,
            'is_active' => 0
        ]);
    }
    public function activity_update($machine, $job_num, $production_date)
    {
        return DB::table('log_activity')
            ->where('machine_id', $machine)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->update([
                'end_date' => now('Asia/Jakarta')
            ]);
    }
    public function current_machine($job_num)
    {
        return DB::table('log_header_machine')
            // ->where('machine_id', $machine)
            ->where('job_num', $job_num)
            ->orderByDesc('started_at')
            ->first();
    }
    public function current_machine_tool($job_num)
    {
        return DB::table('log_machine_tool')
            // ->where('machine_id', $machine)
            ->where('job_num', $job_num)
            ->orderByDesc('started_at')
            ->first();
    }
    public function WorkTIme($machine_id)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machine_id)
            ->whereNotNull('tool_id')
            ->select('machine_id', 'tool_id', 'standard_sph')
            ->get();
    }
    public function revisionModel($job_num)
    {
        return DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->select('RevisionNum')
            ->where('JobNum', $job_num)
            ->first();
    }
    public function getSummaryIdTool($machine_id, $job_num, $date, $shift, $tool_id)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('production_date', $date)
            ->where('shift', $shift)
            ->where('tool_id', $tool_id)
            ->first();
    }
    public function updateMachineTool($machine_id, $tool_id, $seq_id)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machine_id)
            ->where('tool_id', $tool_id)
            ->where('is_active', true)
            ->update(['summary_id' => $seq_id]);
    }
    public function logOeeTool($machine_id, $job_num, $tool_id, $date, $shift)
    {
        return DB::table('oee_log_machine')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('tool_id', $tool_id)
            ->where('production_date', $date)
            ->where('shift', $shift)
            ->first();
    }
    public function aggregateSummaryTool($machine_id, $job_num, $tool_id, $date, $shift)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', $machine_id)
            ->where('job_num', $job_num)
            ->where('tool_id', $tool_id)
            ->where('production_date', $date)
            ->where('shift', $shift)
            ->selectRaw('
                SUM(qty_actual) as qty_actual,
                SUM(qty_ng) as qty_ng,
                SUM(downtime) as downtime,
                SUM(operation_time) as operation_time
            ')
            ->first();
    }
    public function insertGsphRecordTool($machine_id, $date, $time, $tool_id, $job_num, $shift)
    {
        return DB::table('gsph_record')
            ->insert([
                'machine_id' => $machine_id,
                'gsph' => 0,
                'cut_off' => $date,
                'cut_off_time' => $time,
                'tool_id' => $tool_id,
                'job_num' => $job_num,
                'shift' => $shift,
                'qty_actual' => 0
            ]);
    }
    public function insertGsphRecord($dataGsph)
    {
        return DB::table('gsph_record')
            ->insert($dataGsph);
    }
    public function updateSummary($machineId, $shift, $job_num, $production_date, $qty_plan)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', $machineId)
            ->where('shift', $shift)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('qty_plan', $qty_plan)
            ->where('qty_actual', 0)
            ->exists();
    }
    public function resultOee($machineId, $job_num, $shift, $production_date)
    {
        return DB::table('log_header_machine_summary')
            ->select('machine_id', 'shift', 'job_num', 'production_date', DB::raw('SUM(qty_actual) AS qty_actual'), DB::raw('SUM(qty_ng) AS qty_ng'), DB::raw('SUM(downtime) AS downtime'), DB::raw('SUM(operation_time) AS operation_time'))
            ->groupBy('machine_id', 'shift', 'job_num', 'production_date')
            ->where('machine_id', $machineId)
            ->having('job_num', '=', "$job_num")
            ->having('shift', '=', "$shift")
            ->having('production_date', '=', "$production_date" . ' 00:00:00')
            ->get();
    }
    public function updateOee($machineId, $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng)
    {
        return DB::table('oee_log_machine')
            ->where('machine_id', $machineId)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->update([
                'job_num' => $job_num,
                'shift' => $shift,
                'production_date' => $production_date,
                'available_time' => $avail_time,
                'operation_time' => $operation_time,
                'downtime' => $downtime,
                'total_qty' => $qty_actual,
                'total_ng' => $qty_ng,
            ]);
    }
    public function insertOee($raw, $job_num, $shift, $production_date, $avail_time, $operation_time, $downtime, $qty_actual, $qty_ng)
    {
        return DB::table('oee_log_machine')->insert([
            'machine_id' => $raw,
            'job_num' => $job_num,
            'shift' => $shift,
            'production_date' => $production_date,
            'available_time' => $avail_time,
            'operation_time' => $operation_time,
            'downtime' => $downtime,
            'total_qty' => $qty_actual,
            'total_ng' => $qty_ng,
        ]);
    }
    public function countDbOee($machineId, $job_num, $shift, $production_date)
    {
        return DB::table('oee_log_machine')
            ->where('machine_id', $machineId)
            ->where('job_num', $job_num)
            ->where('shift', $shift)
            ->where('production_date', $production_date)
            ->count();
    }
    public function insert_history_machine($data)
    {
        return DB::table('history_header_machine')
            ->insert($data);
    }
    public function employee_data($line, $machine, $downtimeType = null, $position)
    {
        return DB::table('Employee')
            ->where('Position', $position)
            ->where(function ($query) use ($line, $machine, $downtimeType) {
                $query->where(function ($q) use ($line, $machine) {

                    $q->where(function ($x) use ($line) {

                        $x->where('Line', 'ALL')
                            ->orWhere('Line', $line);
                    });

                    $q->where(function ($x) use ($machine) {

                        $x->where('Machine', 'ALL')
                            ->orWhere('Machine', $machine);
                    });
                    $q->whereNull('Type');
                });

                if ($downtimeType) {
                    $query->orWhere(function ($q) use ($line, $downtimeType) {

                        $q->where('Type', $downtimeType);

                        $q->where(function ($x) use ($line) {

                            $x->where('Line', 'ALL')
                                ->orWhere('Line', $line);
                        });
                    });
                }
            })
            ->distinct()
            ->get();
    }
}
