<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobNum extends Model
{
    public function get_all($data_shift, $start_date, $finish_date)
    {
        return DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->select('JobNum', 'ProdCode')
            ->whereBetween('ReqDueDate', [$start_date, $finish_date])
            // ->where('JobNum', 'LIKE', $category . '%')
            ->where('JobCode', $data_shift)
            ->orderBy('ReqDueDate', 'desc')
            ->get();
    }
    public function get_customer($JobNum)
    {
        $data = DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->where('JobNum', $JobNum)
            ->value('ProdCode');
        return $data ?? null;
    }
    public function category_dept($id)
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.EmpBasic')
            ->where('EmpID', $id)
            ->value('JCDept');
    }
    public function get_shift($production_date, $category)
    {
        $category_val = explode('-', $category)[0];
        $date = Carbon::parse($production_date);
        $friday = $date->isFriday();
        $query = DB::connection('sqlsrv4')
            ->table('Erp.JCShift');
        if ($category_val == 'STP') {
            if ($friday) {
                $query->where('shift', 3);
            } else {
                # code...
            }

        } else {
            if ($friday) {
                $query->where('shift', 7);
            } else {
                # code...
            }

        }
    }
}
