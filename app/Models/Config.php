<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            ->where('JobNum', 'LIKE', '%' . $category . '%');
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
            ->where('JobNum', 'LIKE', '%' . $category . '%');

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
    public function update_setup($machine, $data)
    {
        return DB::table('log_header_machine')
            ->where('machine_id', $machine)
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
}
