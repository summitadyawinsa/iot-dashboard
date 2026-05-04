<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogMachine extends Model
{
    use HasFactory;
    protected $table = 'log_header_machine';
    public $incrementing = false;
    protected $primaryKey = 'machine_id';
    protected $keyType = 'string';
    protected $guarded = [];
    public function listDowntime($machine_id, $category_id)
    {
        return DB::table('downtime_list as a')
            ->leftJoin('log_downtime as b', function ($join) use ($machine_id) {
                $join->on('a.id', 'b.downtime_id');
                $join->on(DB::raw(1), 'b.is_active');
                $join->on(DB::raw("$machine_id"), 'b.machine_id');
            })
            ->where('a.category_id', 'LIKE', '%' . $category_id . '%')
            ->select('a.id', 'a.code', 'b.downtime_id', 'b.seq_id')
            ->groupBy('a.id', 'a.code', 'b.downtime_id', 'b.seq_id')
            ->get();
    }
    public function schProduction($id, $date_sql)
    {
        return DB::table('v_sch_production')
            ->where('id', $id)->where('doc_date', "$date_sql")
            ->limit(4)->orderBy('qty_actual', 'asc')->get();
    }
    public function lastUpdateDtlMachine($id)
    {
        return DB::table('log_detail_machine')
            ->where('machine_id', $id)
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'asc')
            ->first();
    }
    public function availTime($machine_id)
    {
        return DB::table("log_header_machine_summary as a")
            ->join('oee_log_machine AS b', function ($join) {
                $join->on('a.machine_id', 'b.machine_id');
                $join->on('a.production_date', 'a.production_date');
                $join->on('a.shift', 'a.shift');
            })
            ->where("a.machine_id", "$machine_id")
            ->where("a.is_active", 1)
            ->select('b.available_time')
            ->get();
    }
    public function queryTablePage($id, $line_id, $machineIds)
    {
        if ($id === 'RBT-5H45') {
            $query = DB::table('log_header_machine as header')
                ->leftJoin('log_machine_tool as tool', 'tool.machine_id', '=', 'header.machine_id')
                ->where('header.category_line_id', $line_id)
                ->whereIn('header.machine_id', $machineIds)
                ->where(function ($q) {
                    $q->whereNotExists(function ($sub) {
                        $sub->select(DB::raw(1))
                            ->from('log_machine_tool as t2')
                            ->whereColumn('t2.machine_id', 'header.machine_id')
                            ->whereNotNull('t2.tool_id');
                    })
                        ->orWhereNotNull('tool.tool_id');
                })
                ->select([
                    DB::raw("
                CASE
                    WHEN tool.tool_id IS NOT NULL AND tool.tool_id != ''
                        THEN CONCAT(header.machine_id, '/', tool.tool_id)
                    ELSE header.machine_id
                END as machine_id
            "),
                    DB::raw('COALESCE(tool.job_num, header.job_num) as job_num'),
                    DB::raw('COALESCE(tool.model, header.model) as model'),
                    DB::raw('COALESCE(tool.part_no, header.part_no) as part_no'),
                    DB::raw('COALESCE(tool.qty_plan, header.qty_plan) as qty_plan'),
                    DB::raw('COALESCE(tool.qty_actual, header.qty_actual) as qty_actual'),
                    DB::raw('COALESCE(tool.qty_ok, header.qty_ok) as qty_ok'),
                    DB::raw('COALESCE(tool.started_at, header.started_at) as started_at'),
                    DB::raw('COALESCE(tool.finished_at, header.finished_at) as finished_at'),
                    DB::raw('COALESCE(tool.operation_time, header.operation_time) as operation_time'),

                ]);
        } else {
            $query = DB::table('log_header_machine')
                ->where('category_line_id', $line_id)
                ->where('is_active', true)
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
        return $query;
    }
    public function queryTablePending()
    {
        $sql = "
    SELECT DISTINCT
        T1.DueDate, T1.IUM, T1.ProdQty, T1.ReqDueDate, T1.StartDate, T1.AnalysisCode,
        T2.AssemblySeq, T2.BomSequence,
        T2.Company, T2.[Description], T2.DrawNum, T2.IUM as JobAsmbl_IUM, T2.JobNum, T2.OverRunQty,
        T2.PartNum, T2.PullQty, T2.RequiredQty, T2.RevisionNum,
        T3.CommentText, T3.Instructions, T3.DaysOut, T3.DueDate as JobOper_DueDate, T3.EstProdHours,
        T3.EstSetHours, T3.Machines, T3.OpCode, T3.OpDesc, T3.OprSeq, T3.PrimaryProdOpDtl, T3.PrimarySetupOpDtl,
        T3.ProdStandard, T3.RunQty, T3.StartDate as JobOper_StartDate, T3.StdFormat,
        T4.CapabilityID, T4.ConcurrentCapacity, T4.DailyProdRate, T4.OpDtlSeq, T4.ProdCrewSize, T4.ResourceGrpID,
        T4.ResourceID, T4.SetUpCrewSize, T4.SetupOrProd,
        T5.ResourceGrpID as ResourceTimeUsed_ResourceGrpID, T5.ResourceID as ResourceTimeUsed_ResourceID, T5.WhatIf,
        T1.JobCode, T1.ProdCode
    FROM [App].Erp.JobHead T1
    LEFT OUTER JOIN [App].Erp.JobAsmbl T2
        ON T1.Company = T2.Company AND T1.JobNum = T2.JobNum
    LEFT OUTER JOIN [App].Erp.JobOper T3
        ON T2.Company = T3.Company AND T2.JobNum = T3.JobNum AND T2.AssemblySeq = T3.AssemblySeq
    LEFT OUTER JOIN [App].Erp.JobOpDtl T4
        ON T3.Company = T4.Company AND T3.JobNum = T4.JobNum AND T3.AssemblySeq = T4.AssemblySeq AND T3.OprSeq = T4.OprSeq
    LEFT OUTER JOIN [App].Erp.ResourceTimeUsed T5
        ON T4.Company = T5.Company AND T4.JobNum = T5.JobNum AND T4.AssemblySeq = T5.AssemblySeq AND T4.OprSeq = T5.OprSeq AND T4.OpDtlSeq = T5.OpDtlSeq
    WHERE T3.LaborEntryMethod = 'T'
      AND T1.JobReleased = 1
      AND T1.DueDate = FORMAT(GETDATE(), 'yyyy-MM-dd')
      AND T5.ResourceGrpID = ?
    ";
        return $sql;
    }
    public function getCategory($id)
    {
        // return DB::table('log_header_machine_summary as summary')
        //     ->join('log_header_machine as machine', 'summary.machine_id', '=', 'machine.machine_id')
        //     ->where('machine.category_line_id', 'LIKE', '%' . $id . '%')
        //     ->selectRaw('LTRIM(RTRIM(summary.shift)) as shift')
        //     ->groupBy(DB::raw('LTRIM(RTRIM(summary.shift))'))
        //     ->orderBy(DB::raw('LTRIM(RTRIM(summary.shift))'), 'asc')
        //     ->get();
        if ($id == 'ASSY') {
            $where = [1, 6, 7, 11, 12, 13];
        } else {
            $where = [1, 2, 3, 4, 5, 8, 9, 10];
        }
        return DB::connection('sqlsrv4')
            ->table('Erp.JCShift')
            ->whereIn('Shift', $where)
            ->select('Shift', 'Description', 'StartTime', 'EndTime', 'LunchStart', 'LunchEnd')
            ->selectRaw("
                ROUND(
                    (
                        CASE 
                            WHEN EndTime < StartTime 
                                THEN (EndTime + 24 - StartTime)
                            ELSE (EndTime - StartTime)
                        END
                    )
                    -
                    (
                        CASE 
                            WHEN LunchEnd < LunchStart 
                                THEN (LunchEnd + 24 - LunchStart)
                            ELSE (LunchEnd - LunchStart)
                        END
                    )
                , 2) as total_hours
            ")
            ->get();
    }
    public function shiftJo($shift)
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.JobHead')
            ->where('JobCode', strtoupper($shift))
            ->get();
    }
    public function getRunningMachine($line_id)
    {
        if ($line_id === 'ASSY-002') {
            $machineData = LogMachine::whereIn('machine_id', ['RSW-5H45-07', 'RSW-5H45-08', 'RSW-5H45-11', 'RSW-5H45-12'])->get();
        } else {
            $machineData = LogMachine::where('category_line_id', $line_id)->get();
        }
        return $machineData;
    }
    public function latestDowntime()
    {
        return DB::table('log_downtime as dt1')
            ->select('dt1.machine_id', 'dt1.tool_id', 'dt1.production_date', 'dt1.is_active', 'dt1.finished_at', 'dt1.started_at')
            ->where('dt1.is_active', 1)
            ->whereRaw('dt1.started_at = (
        select max(dt2.started_at)
        from log_downtime dt2
        where dt2.machine_id = dt1.machine_id
          and dt2.tool_id = dt1.tool_id
          and dt2.production_date = dt1.production_date
          and dt2.is_active = 1
    )');
    }
    public function toolMachineData($latestDowntime)
    {
        return DB::table('log_machine_tool as tool')
            ->leftJoinSub($latestDowntime, 'dt', function ($join) {
                $join->on('tool.machine_id', '=', 'dt.machine_id')
                    ->on('tool.tool_id', '=', 'dt.tool_id')
                    ->on('tool.production_date', '=', 'dt.production_date');
            })
            ->whereIn('tool.machine_id', ['RSW-5H45-09', 'RSW-5H45-10'])
            ->whereIn('tool.tool_id', [1, 2, 3, 4])
            ->select('tool.*', 'dt.is_active as dt_active', 'dt.finished_at as dt_finish')
            ->get();
    }
    public function allJO($search)
    {
        return DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->select('JobNum', 'ProdCode')
            ->when($search, function ($q) use ($search) {
                $q->where('JobNum', 'LIKE', '%' . $search . '%');
            });
    }
    public function joList($start, $finish, $shift)
    {
        return DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->select('JobNum', 'ProdCode')
            ->whereBetween('ReqDueDate', [$start, $finish])
            // ->where('JobCode', $shift)
            ->orderBy('ReqDueDate', 'desc');
    }
    public function insertDT($dataDT)
    {
        return DB::table('log_downtime')->insertGetId($dataDT);
    }
    public function insertAct($dataAct)
    {
        return DB::table('log_activity')->insert($dataAct);
    }
    public function WorkTIme($machine_id)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machine_id)
            ->whereNotNull('tool_id')
            ->select('machine_id', 'tool_id', 'standard_sph')
            ->get();
    }
    public function updateHeaderSummary($machineID, $toolID, $productionDate, $jobNum, $summaryData)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('production_date', $productionDate)
            ->where('job_num', $jobNum)
            ->update($summaryData);
    }
    public function updateOeeHeader($machineID, $toolID, $productionDate, $jobNum, $oeeData)
    {
        return DB::table('oee_log_machine')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('production_date', $productionDate)
            ->where('job_num', $jobNum)
            ->update($oeeData);
    }
    public function insertHistory($dataHistory)
    {
        return DB::table('history_header_machine')
            ->insert($dataHistory);
    }
    public function updateTool($machineID, $toolID, $productionDate, $jobNum, $dataTool)
    {
        return DB::table('log_machine_tool')
            ->where('machine_id', $machineID)
            ->where('tool_id', $toolID)
            ->where('production_date', $productionDate)
            ->where('job_num', $jobNum)
            ->update($dataTool);
    }
    public function listLogMachine()
    {
        return DB::table('log_header_machine as header')
            // ->where('header.is_active', 1)
            ->leftJoin('log_machine_tool as tool', function ($join) {
                $join->on('tool.machine_id', '=', 'header.machine_id');
            })
            ->select('header.*', 'tool.tool_id', 'tool.job_num as tool_job_num', 'tool.qty_plan as tool_qty_plan', 'tool.qty_actual as tool_qty_actual', 'tool.qty_ng as tool_qty_ng', 'tool.condition_id as tool_condition_id')
            ->orderBy('header.machine_id', 'asc')
            ->orderBy('tool.tool_id', 'asc')
            ->get();
    }
    public function updateMachine($row, $dataMachine)
    {
        return LogMachine::where('machine_id', $row)
            ->where('is_active', 1)
            ->update($dataMachine);
    }
    public function insertGsphRecord($dataGsph)
    {
        return DB::table('gsph_record')
            ->insert($dataGsph);
    }

    public function revisionModel($job_num)
    {
        return DB::connection('sqlsrv4')
            ->table(DB::raw('[Erp].[JobHead]'))
            ->select('RevisionNum')
            ->where('JobNum', $job_num)
            ->first();
    }
    public function nextJob($row, $job_num)
    {
        return DB::table('history_header_machine')
            ->where('machine_id', (string) $row)
            ->where('job_num', (string) $job_num)
            ->whereDate('production_date', date('Y-m-d'))
            ->first();
    }
    public function updateSummary($machineId, $shift, $job_num, $production_date, $qty_plan)
    {
        return DB::table('log_header_machine_summary')
            ->whereIn('machine_id', $machineId)
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
            ->whereIn('machine_id', $machineId)
            ->having('job_num', '=', "$job_num")
            ->having('shift', '=', "$shift")
            ->having('production_date', '=', "$production_date" . ' 00:00:00')
            ->get();
    }
    public function updateLogheaderSummary($row, $shift, $job_num, $production_date, $qty_plan, $qty_actual, $standardSph)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', $row)
            ->where('shift', $shift)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('qty_plan', $qty_plan)
            ->where('qty_actual', 0)
            ->update([
                'shift' => "$shift",
                'job_num' => "$job_num",
                'production_date' => "$production_date",
                'qty_plan' => $qty_plan,
                'qty_actual' => $qty_actual,
                'average_ct' => 0,
                'standard_sph' => $standardSph,
                'is_active' => 1
            ]);
    }
    public function getSummaryId($machineId, $shift, $job_num, $production_date, $qty_plan)
    {
        return DB::table('log_header_machine_summary')
            ->whereIn('machine_id', $machineId)
            ->where('shift', $shift)
            ->where('job_num', $job_num)
            ->where('production_date', $production_date)
            ->where('qty_plan', $qty_plan)
            ->where('qty_actual', 0)
            ->orderBy('seq_id', 'desc')
            ->get();
    }
    public function insertHeaderSummary($machine, $shift, $job_num, $production_date, $qty_plan, $qty_actual, $standardSph)
    {
        return DB::table('log_header_machine_summary')->insert([
            'machine_id' => $machine,
            'shift' => "$shift",
            'job_num' => "$job_num",
            'production_date' => "$production_date",
            'qty_plan' => $qty_plan,
            'qty_actual' => $qty_actual,
            'average_ct' => 0,
            'standard_sph' => $standardSph,
            'is_active' => 1,
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
    public function logDetailMachine($job_num, $dataShift, $machineId)
    {
        return DB::table('log_detail_machine')
            ->where('job_num', "$job_num")
            ->where('shift', $dataShift)
            ->whereIn('machine_id', $machineId)
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();
    }
    public function getLogOee($machineId, $dataShift, $production_date)
    {
        return DB::table('oee_log_machine')
            ->whereIn('machine_id', $machineId)
            ->where('shift', $dataShift)
            ->where('production_date', $production_date)
            ->select('*')
            ->get();
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
    public function updateOeeLogMachine($job_num, $machine_id, $shift, $operation_time, $qty_actual, $standard_sph, $qty_ng)
    {
        return DB::table('oee_log_machine')
            ->where('job_num', $job_num)
            ->where('machine_id', $machine_id)
            ->where('shift', $shift)
            ->update([
                'operation_time' => $operation_time,
                'operation_time_standard' => $qty_actual / $standard_sph,
                'total_qty' => $qty_actual,
                'total_ng' => $qty_ng
            ]);
    }
    public function logHeaderMachineSummary($id)
    {
        return DB::table('log_header_machine_summary')
            ->where('machine_id', "$id")
            ->update(['is_active' => 0]);
    }
    public function firstHistoryMachine($id, $now, $shift)
    {
        return DB::table('history_header_machine')
            ->where('machine_id', $id)
            ->where('production_date', $now)
            ->where('shift', $shift)
            ->first();
    }
    public function sumDowntime($id, $now, $shift)
    {
        return DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('production_date', $now)
            ->where('shift', $shift)
            ->sum('downtime');
    }
    public function show_sop()
    {
        return DB::table('SOP')
            ->selectRaw('
            MAX(ID) as ID,
            PartNum,
            MAX(Title) as Title,
            COUNT(Step) as Step
        ')
            ->groupBy('PartNum');
    }
    public function showPartNum()
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.Part')
            ->select('PartNum')
            ->get();
    }
    public function checkStep($Part, $Step)
    {
        return DB::table('SOP')
            ->where('PartNum', $Part)
            ->where('Step', $Step)
            ->first();
    }
    public function StoreSop($data)
    {
        return DB::table('SOP')
            ->insert($data);
    }
    public function CheckPart($part)
    {
        return DB::table('SOP')
            ->where('PartNum', $part)
            ->first();
    }
    public function DeleteAll($part)
    {
        return DB::table('SOP')
            ->where('PartNum', $part)
            ->delete();
    }
    public function FindID($id)
    {
        return DB::table('SOP')
            ->where('id', $id)
            ->first();
    }
    public function updateSop($id, $data)
    {
        return DB::table('SOP')
            ->where('id', $id)
            ->update($data);
    }
    public function deleteFind($id)
    {
        return DB::table('SOP')
            ->where('id', $id)
            ->delete();
    }
}
