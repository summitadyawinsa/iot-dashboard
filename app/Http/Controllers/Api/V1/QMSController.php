<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class QMSController extends Controller

{
    public function get_all_dept_open_overdue($yearMonth)
    {
        [$year, $month] = explode('-', $yearMonth);
        $year = (int) $year;
        $month = (int) $month;

        $departments = [
            'PUR',
            'HRGA',
            'QC',
            'TMF',
            'SLS',
            'FA',
            'TMC',
            'NPC',
            'PPIC',
            'DPC',
            'ICT',
            'STP',
            'ASSY',
            'MTC'
        ];

        // 1. Ambil department dari DB (Filter Deleted juga agar konsisten)
        $deptFromDb = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g') // Alias g
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID') // Join tabel header
            ->distinct()
            ->whereNotNull('g.asign_to_dept') // g.asign_to_dept
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->pluck('g.asign_to_dept')
            ->toArray();

        $allDepartments = array_unique(array_merge($departments, $deptFromDb));

        // 2. Query Open & Close (Filter Deleted)
        $results = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw("
                SUM(CASE WHEN g.verification_result IS NULL
                         AND CAST(g.due_date AS DATE) >= CAST(GETDATE() AS DATE)
                         THEN 1 ELSE 0 END) AS TotalOpen
            "),
                DB::raw("
                SUM(CASE WHEN g.verification_result = 1
                         THEN 1 ELSE 0 END) AS TotalClose
            ")
            )
            // Filter Data Delete
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->whereYear('g.created_at', $year)
            ->whereMonth('g.created_at', $month)
            ->whereNotNull('g.asign_to_dept')
            ->groupBy('g.asign_to_dept')
            ->get()
            ->keyBy('asign_to_dept');

        // 3. Query Overdue (Tambahkan Join & Filter Deleted)
        $overdueResults = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID') // Join ditambahkan
            ->select(
                'g.asign_to_dept',
                DB::raw("
                SUM(CASE WHEN g.verification_result IS NULL
                         AND CAST(g.due_date AS DATE) < CAST(GETDATE() AS DATE)
                         THEN 1 ELSE 0 END) AS TotalOverdue
            ")
            )
            // Filter Data Delete
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->whereNotNull('g.asign_to_dept')
            ->groupBy('g.asign_to_dept')
            ->get()
            ->keyBy('asign_to_dept');

        $data = [];
        foreach ($allDepartments as $dept) {
            $open = $results[$dept]->TotalOpen ?? 0;
            $close = $results[$dept]->TotalClose ?? 0;
            $overdue = $overdueResults[$dept]->TotalOverdue ?? 0;

            $deptName = $dept;
            if ($deptName === 'TS') $deptName = 'Mtc';

            $data[] = [
                'name' => $deptName,
                'open' => (int) $open,
                'close' => (int) $close,
                'overdue' => (int) $overdue,
            ];
        }

        usort($data, function ($a, $b) {
            return $b['close'] <=> $a['close'];
        });

        return response()->json([
            'data_total_open' => array_column($data, 'open'),
            'data_total_close' => array_column($data, 'close'),
            'data_total_overdue' => array_column($data, 'overdue'),
            'data_name_dept' => array_column($data, 'name'),
        ]);
    }


    public function findings_detail_table(Request $request)
    {
        $date = $request->input('date', date('Y-m'));

        [$year, $month] = explode('-', $date);
        $year = (int) $year;
        $month = (int) $month;

        $query = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as a')
            ->join('GenbaProcAudit as b', 'a.genba_id', '=', 'b.SysID')
            ->select(
                'a.findings',
                'a.asign_to_name',
                'a.asign_to_dept',
                DB::raw("CAST(a.area_detail AS NVARCHAR(MAX)) as area_detail"),
                'a.complete_date',
                'a.due_date',
                DB::raw("CONVERT(VARCHAR(10), a.created_at, 23) AS created_at"),
                DB::raw("CASE WHEN a.verification_result = 1 THEN 'Close' ELSE 'Open' END AS status"),
                DB::raw("FORMAT(b.Date, 'ddMMyy') + '-' + CAST(a.SysID AS VARCHAR(20)) as DocNum")
            )

            ->whereNotNull('findings')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->whereYear('a.created_at', $year)
            ->whereMonth('a.created_at', $month);

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('a.findings', 'LIKE', "%{$search}%")
                    ->orWhere('a.asign_to_name', 'LIKE', "%{$search}%")
                    ->orWhere('a.asign_to_dept', 'LIKE', "%{$search}%")
                    ->orWhere('a.area_detail', 'LIKE', "%{$search}%")
                    ->orWhere('a.complete_date', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw("CASE WHEN a.verification_result = 1 THEN 'Close' ELSE 'Open' END"), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->count();

        $columns = [
            'DocNum',
            'findings',
            'asign_to_name',
            'asign_to_dept',
            'area_detail',
            'created_at',
            'due_date',
            'complete_date',
            'verification_result'
        ];

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'due_date';

        $query->orderBy($orderColumn, $orderDirection);

        $limit = $request->input('length', 5);
        $start = $request->input('start', 0);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        foreach ($data as $row) {
            if ($row->asign_to_dept === 'NSTP') {
                $row->asign_to_dept = 'ASSY';
            }
            if ($row->asign_to_dept === 'TS') {
                $row->asign_to_dept = 'Mtc';
            }
            if ($row->asign_to_dept === 'QUA') {
                $row->asign_to_dept = 'QC';
            }
        }

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }




    public function get_all_dept_open_remain($yearMonth)
    {
        [$year, $month] = explode('-', $yearMonth);
        $year = (int) $year;
        $month = (int) $month;

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        $weeks = [
            'W1' => [$startOfMonth->copy()->day(1), $startOfMonth->copy()->day(7)],
            'W2' => [$startOfMonth->copy()->day(8), $startOfMonth->copy()->day(14)],
            'W3' => [$startOfMonth->copy()->day(15), $startOfMonth->copy()->day(21)],
            'W4' => [$startOfMonth->copy()->day(22), $endOfMonth],
        ];

        $data_week = [];
        $data_total_open = [];
        $data_total_close = [];

        foreach ($weeks as $label => [$start, $end]) {
            $results = DB::connection('sqlsrv2')
                ->table('GenbaProcAuditDtl as g')
                ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
                ->select(
                    DB::raw("SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen"),
                    DB::raw("SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose")
                )
                ->whereNotNull('g.asign_to_dept')

                ->where(function ($q) {
                    $q->where('b.IsDelete', '!=', 1)
                        ->orWhereNull('b.IsDelete');
                })

                ->whereBetween(DB::raw('CAST(g.created_at AS DATE)'), [$start->toDateString(), $end->toDateString()])
                ->first();

            $open = (int) ($results->TotalOpen ?? 0);
            $close = (int) ($results->TotalClose ?? 0);

            $data_week[] = $label;
            $data_total_open[] = $open;
            $data_total_close[] = $close;
        }

        return response()->json([
            'data_week' => $data_week,
            'data_total_open' => $data_total_open,
            'data_total_close' => $data_total_close,
        ]);
    }


    public function get_all_dept_open($year)
    {
        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',

            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'name_dept'    => $data->pluck('asign_to_dept'),
            'total_open'    => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'PPIC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_dpc($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'DPC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_assy($year)
    {


        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'ASSY')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_qua($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'QC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_pur($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'PUR')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_tmc($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'TMC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_hrga($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'HRGA')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_ict($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'ICT')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }
    public function get_total_genba_dept_tmf($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'TMF')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_npc($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'NPC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_stp($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'STP')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }

    public function get_total_genba_dept_mtc($year)
    {

        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        $data = DB::connection('sqlsrv2')
            ->table('GenbaProcAuditDtl as g')
            ->join('GenbaProcAudit as b', 'g.genba_id', '=', 'b.SysID')
            ->select(
                'g.asign_to_dept',
                DB::raw('SUM(CASE WHEN g.verification_result IS NULL THEN 1 ELSE 0 END) AS TotalOpen'),
                DB::raw('SUM(CASE WHEN g.verification_result = 1 THEN 1 ELSE 0 END) AS TotalClose'),
                DB::raw("
                SUM(
                    CASE
                        WHEN g.verification_result IS NULL
                         AND CAST(due_date AS DATE) < CAST(GETDATE() AS DATE)
                        THEN 1 ELSE 0
                    END
                ) AS TotalDueDate
            ")
            )
            ->whereNotNull('g.asign_to_dept')
            ->where(function ($q) {
                $q->where('b.IsDelete', '!=', 1)
                    ->orWhereNull('b.IsDelete');
            })
            ->where('g.asign_to_dept', 'MTC')
            ->whereRaw('YEAR(g.created_at) = ?', [$year])
            ->whereRaw('MONTH(g.created_at) = ?', [$month])
            ->groupBy('g.asign_to_dept')
            ->orderBy('g.asign_to_dept')
            ->get();

        return response()->json([
            'total_open'     => $data->pluck('TotalOpen')->map(fn($v) => (int)$v),
            'total_close'    => $data->pluck('TotalClose')->map(fn($v) => (int)$v),
            'total_due_date' => $data->pluck('TotalDueDate')->map(fn($v) => (int)$v),
        ]);
    }
}
