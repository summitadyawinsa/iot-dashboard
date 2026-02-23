<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use DateTime;
use DataTables;

class PPICController extends Controller
{

    public function get_ppic_monitoring_Monthly($year)
    {
        $str_year = explode('~', $year);
        $Year = (int) $str_year[0];

        $results = DB::connection('sqlsrv5')->table('JOMonitoringPerMonths')
            ->select('Months', 'TotalJob', 'TotalJobClose')
            ->where('Years', $Year)
            ->orderBy('Months', 'asc')
            ->get();

        $jobData = array_fill(0, 12, 0);
        $jobCloseData = array_fill(0, 12, 0);

        foreach ($results as $row) {
            $monthIndex = (int) $row->Months - 1;
            $jobData[$monthIndex] = (int) $row->TotalJob;
            $jobCloseData[$monthIndex] = (int) $row->TotalJobClose;
        }

        return response()->json([
            'data_chart_open_month' => $jobData,
            'data_chart_close_month' => $jobCloseData
        ]);
    }

    //DAYS

    public function get_ppic_monitoring_Days($date_job)
    {
        $jobs = explode('~', $date_job);
        $date_job = $jobs[0];

        $date = explode('-', $date_job);
        $Year = (int) $date[0];
        $month = (int) $date[1];

        $resultsX = DB::connection('sqlsrv5')->table('JOMonitoringPerDays as a')
            ->select('Years', 'Months', 'Days', 'TotalJob', 'TotalJobClose')
            ->where('Years', $Year)
            ->where('Months', $month)
            ->orderBy('Days', 'asc')
            ->get();

        if ($resultsX->isEmpty()) {
            return response()->json([
                'data_open_days' => '',
                'data_close_days' => '',
                'data_val_date' => ''
            ]);
        }

        return response()->json([
            'data_open_days' => $resultsX->pluck('TotalJob')->implode(','),
            'data_close_days' => $resultsX->pluck('TotalJobClose')->implode(','),
            'data_val_date' => $resultsX->pluck('Days')->implode(',')
        ]);
    }

    public function get_ppic_monitoring_Table(Request $request)
    {
        $dateJob = explode('-', $request->year);
        $year = (int) $dateJob[0];
        $month = (int) $dateJob[1];
        $day = (int) $dateJob[2];

        $query = DB::connection('sqlsrv5')->table('JOMonitoringPerJob as a')
            ->select('a.JoNumber', 'a.Plan', 'a.Actual', 'a.Received', 'a.Issue', 'a.Prod', 'a.Receipt', 'a.JobClosed')
            ->where('a.Years', $year)
            ->where('a.Months', $month)
            ->where('a.Days', $day)
            ->where('a.TotalJob', '!=', '')
            ->orderBy('a.TotalJobClose', 'asc');

        // Add department filter if department is selected
        if (isset($request->department) && !empty($request->department)) {
            $department = $request->department;
            // Use LIKE query to filter JoNumber by department code
            $query->where('a.JoNumber', 'LIKE', "%{$department}%");
        }

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'JoNumber',
            1 => 'Plan',
            2 => 'Actual',
            3 => 'Received',
            4 => 'Issue',
            5 => 'Prod',
            6 => 'Receipt',
            7 => 'JobClosed'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'JoNumber';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('JoNumber', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%")
                    ->orWhere('Received', 'LIKE', "%{$search}%")
                    ->orWhere('Issue', 'LIKE', "%{$search}%")
                    ->orWhere('Prod', 'LIKE', "%{$search}%")
                    ->orWhere('Receipt', 'LIKE', "%{$search}%")
                    ->orWhere('JobClosed', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $data = $query->offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                'JoNumber' => $row->JoNumber,
                'Plan' => number_format($row->Plan, 0),
                'Actual' => number_format($row->Actual, 0),
                'Received' => number_format($row->Received, 0),
                'Issue' => $row->Issue,
                'Prod' => $row->Prod,
                'Receipt' => $row->Receipt,
                'JobClosed' => $row->JobClosed,
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $formattedData
        ]);
    }

    // Modify this endpoint as well to support department filtering in downloads
    public function get_ppic_monitoring_Table_All(Request $request)
    {
        $selectedDate = $request->input('date');
        if (!$selectedDate) {
            return response()->json(['error' => 'No date selected'], 400);
        }

        $dateJob = explode('-', $selectedDate);
        $year = (int) $dateJob[0];
        $month = (int) $dateJob[1];
        $day = (int) $dateJob[2];

        $query = DB::connection('sqlsrv5')->table('JOMonitoringPerJob as a')
            ->select('a.JoNumber', 'a.Plan', 'a.Actual', 'a.Received', 'a.Issue', 'a.Prod', 'a.Receipt', 'a.JobClosed')
            ->where('a.Years', $year)
            ->where('a.Months', $month)
            ->where('a.Days', $day)
            ->where('a.TotalJob', '!=', '')
            ->orderBy('a.TotalJobClose', 'asc');

        // Apply department filter for downloads too
        if (isset($request->department) && !empty($request->department)) {
            $department = $request->department;
            $query->where('a.JoNumber', 'LIKE', "%{$department}%");
        }

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                'JoNumber' => $row->JoNumber,
                'Plan' => number_format($row->Plan, 0),
                'Actual' => number_format($row->Actual, 0),
                'Received' => number_format($row->Received, 0),
                'Issue' => $row->Issue,
                'Prod' => $row->Prod,
                'Receipt' => $row->Receipt,
                'JobClosed' => $row->JobClosed,
            ];
        }

        return response()->json($formattedData);
    }

    public function get_ppic_monitoring_table_close_open($date_job)
    {
        $date_parts = explode('-', $date_job);
        if (count($date_parts) < 2) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        $year = (int) $date_parts[0];
        $month = (int) $date_parts[1];
        $day = isset($date_parts[2]) ? (int) $date_parts[2] : null;

        $department = request()->input('department');
        $displayName = request()->input('displayName', $department);

        $bindings = [
            'year' => $year,
            'month' => $month,
        ];

        $deptMapping = [
            'ASY' => 'ASSY',
            'STP' => 'STAMPING',
            'SBC' => 'SUBCONT',
            'RPC' => 'REPACKING'
        ];

        $joDeptValue = "'ALL'";

        if (!empty($department) && isset($deptMapping[$department])) {
            $joDeptValue = "'" . $department . "'";
        } elseif (!empty($displayName)) {
            $joDeptValue = "'" . $displayName . "'";
        }

        $sql = "
    
      SELECT
    $joDeptValue AS JoDept,
    Years,
    Months,
    Days,
    COUNT(JoNumber) AS TotalJo,
    SUM(CAST(TotalJob AS INT)) AS TotalJob,
    SUM(CAST(TotalJob AS INT)) - SUM(CAST(TotalJobClose AS INT)) AS JobOpen,
    SUM(CAST(TotalJobClose AS INT)) AS JobClose
        FROM JOMonitoringPerJob
        WHERE Years = :year
        AND Months = :month

    ";


        if (!empty($department)) {
            $sql .= " AND JoNumber LIKE :department";
            $bindings['department'] = '%' . $department . '%';
        }

        if (!is_null($day)) {
            $sql .= " AND Days = :day";
            $bindings['day'] = $day;
        }

        $sql .= " GROUP BY Years, Months, Days";

        $result = DB::connection('sqlsrv5')->select($sql, $bindings);

        $collection = collect($result);

        return response()->json([
            'data_jo_number' => $collection->pluck('TotalJo')->toArray(),
            'data_job_open' => $collection->pluck('JobOpen')->toArray(),
            'data_job_close' => $collection->pluck('JobClose')->toArray(),
            'department' => $displayName ?: 'All'
        ]);
    }



    public function get_stock_monitoring($warehouseName)
    {
        $warehouseSelect = [
            "After Nut",
            "CKD",
            "Inspection",
            "Main",
            "Outgoing Area",
            "PC Store 1",
            "PC Store 2",
            "Production Coridor AB",
            "Production Coridor BC",
            "Production Engineering",
        ];

        if ($warehouseName) {
            if (!in_array($warehouseName, $warehouseSelect)) {
                return response()->json([
                    'error' => 'Gudang tidak ditemukan'
                ], 400);
            }
            $warehouseFilter = [$warehouseName];
        } else {
            $warehouseFilter = $warehouseSelect;
        }

        $results = DB::connection('sqlsrv5')->table('ViewStockMonitoring')
            ->selectRaw("
            Warehse_Description,
            COUNT(CASE WHEN Calculated_OnHandBin < PartPlant_MinimumQty THEN 1 END) AS [Kritis],
            COUNT(CASE WHEN Calculated_OnHandBin > PartPlant_MaximumQty THEN 1 END) AS [Over],
            COUNT(CASE WHEN Calculated_OnHandBin BETWEEN PartPlant_MinimumQty AND PartPlant_MaximumQty THEN 1 END) AS [Oke]
        ")
            ->where('Warehse_Description', $warehouseFilter)
            ->groupBy('Warehse_Description')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_kritis' => 0,
                'data_over' => 0,
                'data_oke' => 0
            ]);
        }

        $data = $results->map(function ($item) {
            return [
                'warehouse' => $item->Warehse_Description,
                'data_kritis' => (int) $item->Kritis,
                'data_over' => (int) $item->Over,
                'data_oke' => (int) $item->Oke
            ];
        });

        return response()->json($data);
    }
    public function get_stock_monitoring_warehouse()
    {
        $warehouseSelect = [
            "After Nut",
            "CKD",
            "Inspection",
            "Main",
            "Outgoing Area",
            "PC Store 1",
            "PC Store 2",
            "Production Coridor AB",
            "Production Coridor BC",
            "Production Engineering",
        ];

        $results = DB::connection('sqlsrv5')->table('ViewStockMonitoring')
            ->selectRaw("
            Warehse_Description,
            COUNT(CASE WHEN Calculated_OnHandBin < PartPlant_MinimumQty THEN 1 END) AS [Kritis],
            COUNT(CASE WHEN Calculated_OnHandBin > PartPlant_MaximumQty THEN 1 END) AS [Over],
            COUNT(CASE WHEN Calculated_OnHandBin BETWEEN PartPlant_MinimumQty AND PartPlant_MaximumQty THEN 1 END) AS [Oke]
        ")
            ->whereIn('Warehse_Description', $warehouseSelect)
            ->groupBy('Warehse_Description')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_kritis' => [],
                'data_over' => [],
                'data_oke' => []
            ]);
        }

        $data_kritis = [];
        $data_over = [];
        $data_oke = [];

        foreach ($results as $row) {
            $data_kritis[] = (int) $row->Kritis;
            $data_over[] = (int) $row->Over;
            $data_oke[] = (int) $row->Oke;
        }

        return response()->json([
            'data_kritis_warehouse' => $data_kritis,
            'data_over_warehouse' => $data_over,
            'data_oke_warehouse' => $data_oke
        ]);
    }

    public function get_stock_monitoring_table(Request $request)
    {
        $whereWarehouse = $request->input('warehouse', 'After Nut');
        $query = DB::connection('sqlsrv5')->select("
        WITH StockStatus AS (
            SELECT 
            Part_PartNum AS PartNumber,
	        Calculated_OnHandBin AS Onhand,
	        CASE
            WHEN Calculated_OnHandBin < PartPlant_MinimumQty THEN 'KRITIS'
            WHEN Calculated_OnHandBin > PartPlant_MaximumQty THEN 'OVER'
            ELSE 'OKE'
            END AS Status
            FROM ViewStockMonitoring
            WHERE Warehse_Description = ?)
            SELECT PartNumber, Onhand, Status FROM StockStatus", [$whereWarehouse]);

        $query = collect($query);

        $query = $query->reject(function ($item) {
            return is_null($item->Onhand) || $item->Onhand == 0;
        });

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query = $query->filter(function ($item) use ($search) {
                return stripos($item->PartNumber, $search) !== false ||
                    stripos($item->Onhand, $search) !== false ||
                    stripos($item->Status, $search) !== false;
            });
        }

        $totalFiltered = $query->count();

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns =
            [
                'PartNumber',
                'Onhand',
                'Status'
            ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'PartNumber';

        $query = $query->sortBy($orderColumn, SORT_REGULAR, $orderDirection === 'desc');

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $data = $query->slice($start, $limit)->values();

        $formattedData = $data->map(function ($row) {
            return [
                'PartNumber' => $row->PartNumber,
                'Onhand' => number_format($row->Onhand, 0),
                'Status' => $row->Status,
            ];
        });

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $query->count(),
            "recordsFiltered" => $totalFiltered,
            "data" => $formattedData
        ]);
    }

    public function get_data_month_departement($yearMonth)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");
        [$year, $month] = explode('-', $yearMonth);

        $query = "
            SELECT 
        Years,
        Months,
        COUNT(JoNumber) AS TotalJo,
        SUM(CAST(TotalJob AS INT)) AS JobOpen,
        SUM(CAST(TotalJobClose AS INT)) AS JobClose,
        CASE
            WHEN JoNumber LIKE '%ASY%' THEN 'ASSY'
            WHEN JoNumber LIKE '%STP%' THEN 'STAMPING'
            WHEN JoNumber LIKE '%SBC%' THEN 'SUBCONT'
            WHEN JoNumber LIKE '%RPC%' THEN 'REPACKING'
        
        END AS Dept
    FROM JOMonitoringPerJob
    WHERE 
        Years = '$year' AND
        Months = '$month' AND
        JoNumber IS NOT NULL AND
        LTRIM(RTRIM(JoNumber)) <> '' AND
        (
            JoNumber LIKE '%ASY%' OR
            JoNumber LIKE '%STP%' OR
            JoNumber LIKE '%SBC%' OR
            JoNumber LIKE '%RPC%'

        )
    GROUP BY 
        Years, 
        Months,
        CASE
            WHEN JoNumber LIKE '%ASY%' THEN 'ASSY'
            WHEN JoNumber LIKE '%STP%' THEN 'STAMPING'
            WHEN JoNumber LIKE '%SBC%' THEN 'SUBCONT'
            WHEN JoNumber LIKE '%RPC%' THEN 'REPACKING'
        END;

        ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_dept' => [],
                'data_open_job' => [],
                'data_close_job' => []
            ]);
        }

        foreach ($results as $row) {
            $dataDept[] = $row->Dept;
            $dataOpen[] = (int) $row->JobOpen;
            $dataClose[] = (int) $row->JobClose;
        }

        return response()->json([
            'data_dept' => $dataDept,
            'data_open_job' => $dataOpen,
            'data_close_job' => $dataClose
        ]);
    }
    public function get_data_day_departement($yearMonth)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");
        [$year, $month, $day] = explode('-', $yearMonth);

        $query = "
            SELECT 
        Years,
        Months,
        Days,
        COUNT(JoNumber) AS TotalJo,
        SUM(CAST(TotalJob AS INT)) AS JobOpen,
        SUM(CAST(TotalJobClose AS INT)) AS JobClose,
        CASE
            WHEN JoNumber LIKE '%ASY%' THEN 'ASSY'
            WHEN JoNumber LIKE '%STP%' THEN 'STAMPING'
            WHEN JoNumber LIKE '%SBC%' THEN 'SUBCONT'
            WHEN JoNumber LIKE '%RPC%' THEN 'REPACKING'
        
        END AS Dept
    FROM JOMonitoringPerJob
    WHERE 
        Years = '$year' AND
        Months = '$month' AND
        Days = '$day' AND
        JoNumber IS NOT NULL AND
        LTRIM(RTRIM(JoNumber)) <> '' AND
        (
            JoNumber LIKE '%ASY%' OR
            JoNumber LIKE '%STP%' OR
            JoNumber LIKE '%SBC%' OR
            JoNumber LIKE '%RPC%'

        )
    GROUP BY 
        Years, 
        Months,
        Days,
        CASE
            WHEN JoNumber LIKE '%ASY%' THEN 'ASSY'
            WHEN JoNumber LIKE '%STP%' THEN 'STAMPING'
            WHEN JoNumber LIKE '%SBC%' THEN 'SUBCONT'
            WHEN JoNumber LIKE '%RPC%' THEN 'REPACKING'
        END;

        ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_dept_date' => [],
                'data_open_job_date' => [],
                'data_close_job_date' => []
            ]);
        }

        foreach ($results as $row) {
            $dataDept[] = $row->Dept;
            $dataOpen[] = (int) $row->JobOpen;
            $dataClose[] = (int) $row->JobClose;
        }

        return response()->json([
            'data_dept_date' => $dataDept,
            'data_open_job_date' => $dataOpen,
            'data_close_job_date' => $dataClose
        ]);
    }
}
