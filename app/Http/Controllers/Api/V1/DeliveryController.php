<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;
use WebSocket\Client;

class DeliveryController extends Controller
{
    protected $Delivery;
    public function __construct(Delivery $Delivery)
    {
        $this->Delivery = $Delivery;
    }
    public function get_forecast_order_Monhtly($year)
    {

        $str_year = explode('~', $year);
        $Year = (int) $str_year[0];
        $ShipCust = isset($str_year[1]) ? trim($str_year[1]) : '';

        $results = DB::connection('sqlsrv5')->table('ShipOrderByMonths')
            ->select('ShipMonths', 'OrderQty', 'ShipQty')
            ->where('ShipCust', $ShipCust)
            ->where('ShipYears', $Year)
            ->orderBy('ShipMonths', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_chart_order_month' => '',
                'data_chart_ship_month' => ''
            ]);
        }

        return response()->json([
            'data_chart_order_month' => $results->pluck('OrderQty')->toArray(),
            'data_chart_ship_month' => $results->pluck('ShipQty')->toArray()
        ]);
    }


    // DAYS
    public function get_forecast_order_Daily($date_cust)
    {
        $parts = explode('~', $date_cust);
        $date_part = $parts[0];
        $ShipCust = $parts[1] ?? '';

        $date = explode('-', $date_part);
        $Year = (int) $date[0];
        $month = (int) $date[1];

        $resultsX = DB::connection('sqlsrv5')->table('ShipOrderByDays as a')
            ->select('ShipYears', 'ShipMonths', 'ShipDays', 'OrderQty', 'ShipQty')
            ->where('ShipCust', $ShipCust)
            ->where('ShipYears', $Year)
            ->where('ShipMonths', $month)
            ->orderBy('ShipDays', 'asc')
            ->get();

        if ($resultsX->isEmpty()) {
            return response()->json([
                'data_ship_days' => '',
                'data_order_days' => '',
                'data_val_date' => ''
            ]);
        }

        return response()->json([
            'data_ship_days' => $resultsX->pluck('ShipQty')->implode(','),
            'data_order_days' => $resultsX->pluck('OrderQty')->implode(','),
            'data_val_date' => $resultsX->pluck('ShipDays')->implode(',')
        ]);
    }


    // TABLE
    public function get_delivery_Table(Request $request)
    {
        $dateParts = explode('-', $request->year);
        $year = (int) $dateParts[0];
        $month = (int) $dateParts[1];
        $day = (int) $dateParts[2];

        $query = DB::connection('sqlsrv5')->table('ShipOrderPartByDays as a')
            ->select('a.PartNum', 'a.ShipQty', 'a.OrderQty')
            ->where('a.ShipYears', $year)
            ->where('a.ShipMonths', $month)
            ->where('a.ShipDays', $day)
            ->where('a.PartNum', '!=', '')
            ->orderBy('a.ShipDays', 'asc');

        if ($day !== null) {
            $query->where('a.ShipDays', $day);
        }

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PartNum',
            1 => 'ShipQty',
            2 => 'OrderQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'PartNum';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('PartNum', 'LIKE', "%{$search}%")
                    ->orWhere('ShipQty', 'LIKE', "%{$search}%")
                    ->orWhere('OrderQty', 'LIKE', "%{$search}%");
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
                'PartNum' => $row->PartNum,
                'ShipQty' => number_format($row->ShipQty, 0),
                'OrderQty' => number_format($row->OrderQty, 0),
            ];
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $formattedData
        ]);
    }

    public function get_data_job_daily()
    {
        DB::connection('sqlsrv5')->statement("use [app]");

        $query = "
        WITH JobSummary AS (
            SELECT
                FORMAT(b.ReqDueDate, 'dd-MMM') AS ReqDate,
                a.JobNum,
                b.JobComplete,
                CASE
                    WHEN SUM(a.QtyCompleted) = SUM(a.ReceivedQty) THEN 'CLOSED'
                    ELSE 'OPEN'
                END AS Status,
                b.JobClosed
            FROM erp.JobPart a
            JOIN erp.JobHead b ON b.JobNum = a.JobNum
            INNER JOIN erp.Part c ON a.PartNum = c.PartNum
            WHERE c.ClassID = 'FG'
              AND CAST(b.ReqDueDate AS DATE) BETWEEN CAST(DATEADD(DAY, -10, GETDATE()) AS DATE) AND CAST(GETDATE() AS DATE)
              AND b.JobReleased = 1
              AND b.JobNum NOT LIKE 'SBC-%'
            GROUP BY b.ReqDueDate, a.JobNum, b.JobComplete, b.JobClosed
        )
        SELECT
            a.ReqDate,
            COUNT(a.JobComplete) AS JobComplete,
            SUM(CASE WHEN Status = 'CLOSED' THEN 1 ELSE 0 END) AS ReceivedJob
        FROM JobSummary a
        GROUP BY a.ReqDate
        ORDER BY MIN(CONVERT(DATE, a.ReqDate + '-2025', 106))
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_job_date' => '',
                'data_job_complate' => '',
                'data_received_job' => ''
            ]);
        }

        $jobDateData = [];
        $jobComplateData = [];
        $receivedJobData = [];

        foreach ($results as $row) {
            $jobDateData[] = $row->ReqDate;
            $jobComplateData[] = (int) $row->JobComplete;
            $receivedJobData[] = (int) $row->ReceivedJob;
        }

        return response()->json([
            'data_job_date' => implode(', ', $jobDateData),
            'data_job_complate' => implode(', ', $jobComplateData),
            'data_received_job' => implode(', ', $receivedJobData)
        ]);
    }


    public function get_data_job_monthly($kodeBulan)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        if (empty($kodeBulan)) {
            return response()->json([
                'data_month_open_job' => '',
                'data_month_close_job' => ''
            ]);
        }

        $year = date('Y', strtotime($kodeBulan));
        $month = date('m', strtotime($kodeBulan));

        $query = "
        WITH UniqueJobs AS (
            SELECT
                [JobHead].[JobNum],
                FORMAT([JobHead].[ReqDueDate], 'dd MMM yy') AS [Tanggal],
                FORMAT([JobHead].[ReqDueDate], 'MMM') AS [Month],
                CASE
                    WHEN [JobHead].[JobClosed] = 1 THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [JobClosed],
                [JobPart].[PartNum],
                [JobHead].[ProdQty],
                [JobPart].[QtyCompleted],
                [JobPart].[ReceivedQty],
                CASE
                    WHEN [JobMtl].[IssuedComplete] = 0 THEN 'OPEN'
                    ELSE 'CLOSE'
                END AS [Issue],
                CASE
                    WHEN [JobHead].[ProdQty] = [JobPart].[QtyCompleted] THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [Prod],
                CASE
                    WHEN [JobPart].[QtyCompleted] = [JobPart].[ReceivedQty] THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [Receipt],
                ROW_NUMBER() OVER (PARTITION BY [JobHead].[JobNum] ORDER BY [JobHead].[ReqDueDate] DESC) AS RowNum
            FROM
                Erp.JobHead AS [JobHead]
            INNER JOIN
                Erp.JobPart AS [JobPart] ON JobHead.JobNum = JobPart.JobNum
            INNER JOIN
                Erp.JobMtl AS [JobMtl] ON JobPart.JobNum = JobMtl.JobNum
            INNER JOIN
                Erp.JobOper AS [JobOper] ON JobMtl.JobNum = JobOper.JobNum
                AND JobMtl.AssemblySeq = JobOper.AssemblySeq
                AND JobMtl.RelatedOperation = JobOper.OprSeq
            LEFT OUTER JOIN
                Erp.Warehse AS [Warehse1] ON JobMtl.WarehouseCode = Warehse1.WarehouseCode
            LEFT OUTER JOIN
                Erp.PartCost AS [PartCost] ON JobMtl.PartNum = PartCost.PartNum
            INNER JOIN
                Erp.Part ON [JobPart].[PartNum] = Part.PartNum
            WHERE
                [JobOper].[SubContract] = 0
                AND MONTH([JobHead].[ReqDueDate]) = $month
                AND YEAR([JobHead].[ReqDueDate]) = $year
                AND Part.ClassID = 'FG'
        )
        SELECT
            COUNT(DISTINCT CASE WHEN [JobClosed] = 'CLOSE' THEN [JobNum] END) AS [ClosedJob],
            COUNT(DISTINCT CASE WHEN [JobClosed] = 'OPEN' THEN [JobNum] END) AS [OpenJob],
            MAX([Month]) AS [Month]
        FROM UniqueJobs;
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_month_open_job' => '',
                'data_month_close_job' => ''
            ]);
        }

        $dataopen = [];
        $dataclose = [];

        foreach ($results as $row) {
            $dataopen[] = (int) $row->OpenJob;
            $dataclose[] = (int) $row->ClosedJob;
        }

        return response()->json([
            'data_month_open_job' => implode(', ', $dataopen),
            'data_month_close_job' => implode(', ', $dataclose)
        ]);
    }


    public function get_data_job_daily_select($kodeHari)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        if (empty($kodeHari)) {
            return response()->json([
                'data_month_open_job' => '',
                'data_month_close_job' => ''
            ]);
        }

        $year = date('Y', strtotime($kodeHari));
        $month = date('m', strtotime($kodeHari));
        $day = date('d', strtotime($kodeHari));

        $query = "
    WITH JobSummary AS (
    SELECT
        b.ReqDueDate,
        a.JobNum,
        b.JobComplete,
        CASE
            WHEN SUM(a.QtyCompleted) = SUM(a.ReceivedQty) THEN 'CLOSED'
            ELSE 'OPEN'
        END AS Status,
        b.JobClosed
    FROM erp.JobPart a
    JOIN erp.JobHead b ON b.JobNum = a.JobNum
    INNER JOIN erp.Part c ON a.PartNum = c.PartNum
    WHERE
        c.ClassID = 'FG'
        AND DAY(b.ReqDueDate) = $day
        AND MONTH(b.ReqDueDate) = $month
        AND YEAR(b.ReqDueDate) = $year
        AND b.JobReleased = 1
        AND b.JobNum NOT LIKE 'SBC-%'
    GROUP BY b.ReqDueDate, a.JobNum, b.JobComplete, b.JobClosed
)
SELECT
    DAY(A.ReqDueDate) AS [Day],
    MONTH(A.ReqDueDate) AS [Month],
    YEAR(A.ReqDueDate) AS [Year],
    COUNT(A.JobComplete) AS JobComplete,
    SUM(CASE WHEN Status = 'CLOSED' THEN 1 ELSE 0 END) AS ReceivedJob
FROM JobSummary A
GROUP BY A.ReqDueDate;


    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_day_open_job' => '',
                'data_day_close_job' => ''
            ]);
        }

        $dataopen = [];
        $dataclose = [];

        foreach ($results as $row) {
            $dataopen[] = (int) $row->JobComplete;
            $dataclose[] = (int) $row->ReceivedJob;
        }

        return response()->json([
            'data_day_job_complate' => implode(', ', $dataopen),
            'data_day_received_job' => implode(', ', $dataclose)
        ]);
    }



    public function get_delivery_job_monitoring_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("use [app]");


        $dateParts = explode('-', $request->year);
        $year = (int) $dateParts[0];
        $month = (int) $dateParts[1];
        $day = (int) $dateParts[2];


        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Days',
            1 => 'Months',
            2 => 'Years',
            3 => 'JobNumber',
            4 => 'JobStatus',
            5 => 'PartNumber',
            6 => 'Plan',
            7 => 'Actual',
            8 => 'Received',
            9 => 'Issue',
            10 => 'Prod',
            11 => 'Receipt',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Days';

        $whereClauses = [];
        if ($year) {
            $whereClauses[] = "YEAR([JobHead].[ReqDueDate]) = $year";
        }
        if ($month) {
            $whereClauses[] = "MONTH([JobHead].[ReqDueDate]) = $month";
        }
        if ($day) {
            $whereClauses[] = "DAY([JobHead].[ReqDueDate]) = $day";
        }

        $whereSql = implode(' AND ', $whereClauses);

        $subQuery = "
        WITH UniqueJobs AS (
            SELECT
                [JobHead].[JobNum] AS [JobNumber],
                DAY([JobHead].[ReqDueDate]) AS [Days],
                DATENAME(MONTH, [JobHead].[ReqDueDate]) AS [Months],
                YEAR([JobHead].[ReqDueDate]) AS [Years],
                CASE
                    WHEN [JobHead].[JobClosed] = 1 THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [JobStatus],
                [JobPart].[PartNum] AS [PartNumber],
                [JobHead].[ProdQty] AS [Plan],
                [JobPart].[QtyCompleted] AS [Actual],
                [JobPart].[ReceivedQty] AS [Received],
                CASE
                    WHEN [JobMtl].[IssuedComplete] = 0 THEN 'OPEN'
                    ELSE 'CLOSE'
                END AS [Issue],
                CASE
                    WHEN [JobHead].[ProdQty] = [JobPart].[QtyCompleted] THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [Prod],
                CASE
                    WHEN [JobPart].[QtyCompleted] = [JobPart].[ReceivedQty] THEN 'CLOSE'
                    ELSE 'OPEN'
                END AS [Receipt],
                ROW_NUMBER() OVER (PARTITION BY [JobHead].[JobNum] ORDER BY [JobHead].[ReqDueDate] DESC) AS RowNum
            FROM
                Erp.JobHead AS [JobHead]
            INNER JOIN
                Erp.JobPart AS [JobPart] ON JobHead.JobNum = JobPart.JobNum
            INNER JOIN
                Erp.JobMtl AS [JobMtl] ON JobPart.JobNum = JobMtl.JobNum
            INNER JOIN
                Erp.JobOper AS [JobOper] ON JobMtl.JobNum = JobOper.JobNum
                AND JobMtl.AssemblySeq = JobOper.AssemblySeq
                AND JobMtl.RelatedOperation = JobOper.OprSeq
            LEFT OUTER JOIN
                Erp.Warehse AS [Warehse1] ON JobMtl.WarehouseCode = Warehse1.WarehouseCode
            LEFT OUTER JOIN
                Erp.PartCost AS [PartCost] ON JobMtl.PartNum = PartCost.PartNum
            INNER JOIN
                Erp.Part ON [JobPart].[PartNum] = Part.PartNum
            WHERE
                [JobOper].[SubContract] = 0
                AND Part.ClassID = 'FG'
                " . ($whereSql ? "AND $whereSql" : "") . "
        )
        SELECT
            [Days],
            [Months],
            [Years],
            [JobNumber],
            [JobStatus],
            [PartNumber],
            CAST([Plan] AS INT) AS [Plan],
            CAST([Actual] AS INT) AS [Actual],
            CAST([Received] AS INT) AS [Received],
            [Issue],
            [Prod],
            [Receipt]
        FROM UniqueJobs
        WHERE RowNum = 1
        ORDER BY [Years], [Months], [Days];
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->JobNumber), strtolower($search)) ||
                    str_contains(strtolower($item->PartNumber), strtolower($search)) ||
                    str_contains(strtolower($item->JobStatus), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Days' => $row->Days,
                'Months' => $row->Months,
                'Years' => $row->Years,
                'JobNumber' => $row->JobNumber,
                'JobStatus' => $row->JobStatus,
                'PartNumber' => $row->PartNumber,
                'Plan' => (int) $row->Plan,
                'Actual' => (int) $row->Actual,
                'Received' => (int) $row->Received,
                'Issue' => $row->Issue,
                'Prod' => $row->Prod,
                'Receipt' => $row->Receipt,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function det_moitoring_control_delivery($customer)
    {
        DB::connection('sqlsrv5')->statement("use [app]");

        $kodeCustomer = $customer;

        if (empty($kodeCustomer)) {
            return response()->json([
                'data_po' => '',
                'data_act_del' => '',
                'data_cycle' => ''
            ]);
        }

        $query = "
        WITH StatusSummary AS (
            SELECT
                od.ProdCode,
                od.PartNum,
                od.LineDesc AS PartDesc,
                oh.ShipByTime AS CycleTime,
                orl.Reference AS Cycle,
                SUM(orl.SellingReqQty) AS Total_SO_Qty,
                SUM(ISNULL(sub.Calculated_ShipQty, 0)) AS Total_Shipment_Qty,
                CASE
                    WHEN SUM(ISNULL(sub.Calculated_ShipQty, 0)) = SUM(orl.SellingReqQty) THEN 'Close'
                    ELSE 'Open'
                END AS Status
            FROM Erp.OrderHed oh
            INNER JOIN Erp.OrderDtl od
                ON oh.Company = od.Company AND oh.OrderNum = od.OrderNum
            INNER JOIN Erp.OrderRel orl
                ON od.Company = orl.Company
                AND od.OrderNum = orl.OrderNum
                AND od.OrderLine = orl.OrderLine
            LEFT OUTER JOIN (
                SELECT
                    sd1.OrderNum,
                    sd1.OrderRelNum,
                    sd1.OrderLine,
                    SUM(sd1.OurInventoryShipQty) AS Calculated_ShipQty
                FROM Erp.ShipDtl sd1
                GROUP BY sd1.OrderNum, sd1.OrderRelNum, sd1.OrderLine
            ) sub
                ON orl.OrderNum = sub.OrderNum
                AND orl.OrderRelNum = sub.OrderRelNum
                AND orl.OrderLine = sub.OrderLine
            WHERE CAST(oh.RequestDate AS DATE) = CAST(GETDATE() AS DATE)
            GROUP BY
                od.ProdCode,
                od.PartNum,
                od.LineDesc,
                orl.Reference,
                oh.ShipByTime
        )
        SELECT
            CONCAT('Cycle ', TRY_CAST(Cycle AS INT)) AS CycleCount,
            SUM(Total_SO_Qty) AS PO,
            SUM(Total_Shipment_Qty) AS ActDel
        FROM StatusSummary
        WHERE ProdCode = '$kodeCustomer'
        GROUP BY ProdCode, Cycle
        ORDER BY TRY_CAST(Cycle AS INT) ASC
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_po' => '',
                'data_act_del' => '',
                'data_cycle' => ''
            ]);
        }

        $poData = [];
        $actDelData = [];
        $cycle = [];

        foreach ($results as $row) {
            $poData[] = (int) $row->PO;
            $actDelData[] = (int) $row->ActDel;
            $cycle[] = $row->CycleCount;
        }

        return response()->json([
            'data_po' => implode(', ', $poData),
            'data_act_del' => implode(', ', $actDelData),
            'data_cycle' => implode(', ', $cycle)
        ]);
    }

    public function get_stok_monitoring()
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        $sql = "
        SELECT
            COUNT(CASE WHEN Calculated_OnHandBin < PartPlant_MinimumQty THEN 1 END) AS [CRITICAL],
            COUNT(CASE WHEN Calculated_OnHandBin > PartPlant_MaximumQty THEN 1 END) AS [OVER],
            COUNT(CASE WHEN Calculated_OnHandBin BETWEEN PartPlant_MinimumQty AND PartPlant_MaximumQty THEN 1 END) AS [SAFE]
        FROM
            sai_db.dbo.ViewStockMonitoring
        WHERE
            Warehse_Description = 'Warehouse FG';
    ";

        $results = DB::connection('sqlsrv5')->select($sql);

        if (empty($results)) {
            return response()->json([
                'data_critikal' => 0,
                'data_over' => 0,
                'data_save' => 0
            ]);
        }

        $item = $results[0];
        return response()->json([
            'data_critikal' => (int) $item->CRITICAL,
            'data_over' => (int) $item->OVER,
            'data_save' => (int) $item->SAFE
        ]);
    }


    public function get_control_delivery_table(Request $request, $customer)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        $parts = explode('~', $customer);
        $kodeCustomer = isset($parts[0]) ? addslashes($parts[0]) : '';


        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Customer',
            1 => 'PartNum',
            2 => 'PartDesc',
            3 => 'OnHand',
            4 => 'PO',
            5 => 'ActDel',
            6 => 'Cycle',
            7 => 'Status',
            8 => 'Time',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Cycle';

        $subQuery = "
        WITH StatusSummary AS (
            SELECT
                OrderDtl.ProdCode,
                OrderDtl.PartNum,
                OrderDtl.LineDesc AS PartDesc,
                [OrderRel].Reference AS Cycle,
                CAST(SUM(PartWhse.OnHandQty) AS INT) AS OnHand,
                CAST(SUM([OrderRel].[SellingReqQty]) AS INT) AS Total_SO_Qty,
                CAST(SUM(ISNULL([SubQuery2].[Calculated_ShipQty], 0)) AS INT) AS Total_Shipment_Qty,
                OrderHed.ShipByTime,
                CASE
                    WHEN SUM(ISNULL([SubQuery2].[Calculated_ShipQty], 0)) >= SUM([OrderRel].[SellingReqQty])
                        THEN 'Close'
                    ELSE 'Open'
                END AS Status
            FROM Erp.OrderHed AS OrderHed
            INNER JOIN Erp.OrderDtl AS OrderDtl
                ON OrderHed.Company = OrderDtl.Company
                AND OrderHed.OrderNum = OrderDtl.OrderNum
            INNER JOIN Erp.OrderRel AS OrderRel
                ON OrderDtl.Company = OrderRel.Company
                AND OrderDtl.OrderNum = OrderRel.OrderNum
                AND OrderDtl.OrderLine = OrderRel.OrderLine
            LEFT JOIN (
                SELECT
                    ShipDtl1.OrderNum,
                    ShipDtl1.OrderRelNum,
                    ShipDtl1.OrderLine,
                    SUM(ShipDtl1.OurInventoryShipQty) AS Calculated_ShipQty
                FROM Erp.ShipDtl AS ShipDtl1
                GROUP BY
                    ShipDtl1.OrderNum,
                    ShipDtl1.OrderRelNum,
                    ShipDtl1.OrderLine
            ) AS SubQuery2
                ON OrderRel.OrderNum = SubQuery2.OrderNum
                AND OrderRel.OrderRelNum = SubQuery2.OrderRelNum
                AND OrderRel.OrderLine = SubQuery2.OrderLine
            INNER JOIN Erp.PartWhse AS PartWhse
                ON OrderRel.PartNum = PartWhse.PartNum
                AND PartWhse.WarehouseCode = '05-03-01'
            WHERE CAST(
                    CASE
                        WHEN [OrderRel].Reference > '12'
                            THEN DATEADD(DAY, 1, OrderHed.RequestDate)
                        ELSE OrderHed.RequestDate
                    END AS DATE
                ) = CAST(GETDATE() AS DATE)
            GROUP BY
                OrderDtl.ProdCode,
                OrderDtl.PartNum,
                OrderDtl.LineDesc,
                OrderRel.Reference,
                OrderHed.ShipByTime
        )
        SELECT
            a.ProdCode AS Customer,
            a.PartNum,
            a.PartDesc,
            a.OnHand,
            a.Total_SO_Qty AS PO,
            a.Total_Shipment_Qty AS ActDel,
            a.Cycle,
            a.Status,
            CAST(a.ShipByTime / 3600 AS VARCHAR(2)) + ':00' AS Times
        FROM StatusSummary a
        WHERE
            a.Status != 'Close'
            AND a.ProdCode = '$kodeCustomer'
            AND a.ShipByTime < (DATEPART(HOUR, CAST(GETDATE() AS TIME)) * 3600)
        ORDER BY TRY_CAST(a.Cycle AS INT)
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$kodeCustomer]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Customer), strtolower($search)) ||
                    str_contains(strtolower($item->PartNum), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search)) ||
                    str_contains(strtolower($item->ActDel), strtolower($search)) ||
                    str_contains(strtolower($item->PartDesc), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Customer' => $row->Customer,
                'PartNum' => $row->PartNum,
                'PartDesc' => $row->PartDesc,
                'OnHand' => (int) $row->OnHand,
                'PO' => (int) $row->PO,
                'ActDel' => (int) $row->ActDel,
                'Cycle' => $row->Cycle,
                'Status' => $row->Status,
                'Time' => $row->Times,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_data_finish_good()
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        $query = "
        SELECT TOP(50) Customer, CriticalStock, SafeStock, OverStock FROM sai_db.dbo.VDPC_StockStatusDashboard
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_customer' => '',
                'data_critical_stock' => '',
                'data_safe_stock' => '',
                'data_over_stock' => ''
            ]);
        }

        $dataCustomer = [];
        $dataCritical = [];
        $dataSafe = [];
        $dataOver = [];

        foreach ($results as $row) {
            $dataCustomer[] = $row->Customer;
            $dataCritical[] = (int) $row->CriticalStock;
            $dataSafe[] = (int) $row->SafeStock;
            $dataOver[] = (int) $row->OverStock;
        }

        return response()->json([
            'data_customer' => implode(', ', $dataCustomer),
            'data_critical_stock' => implode(', ', $dataCritical),
            'data_safe_stock' => implode(', ', $dataSafe),
            'data_over_stock' => implode(', ', $dataOver)
        ]);
    }
    public function get_stock_monitoring_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");



        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Customer',
            1 => 'CriticalStock',
            2 => 'SafeStock',
            3 => 'OverStock',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
        SELECT TOP(50) Customer, CriticalStock, SafeStock, OverStock FROM sai_db.dbo.VDPC_StockStatusDashboard

    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Customer), strtolower($search)) ||
                    str_contains(strtolower($item->CriticalStock), strtolower($search)) ||
                    str_contains(strtolower($item->SafeStock), strtolower($search)) ||
                    str_contains(strtolower($item->OverStock), strtolower($search));
            });
        }


        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Customer' => $row->Customer,
                'CriticalStock' => (int) $row->CriticalStock,
                'SafeStock' => (int) $row->SafeStock,
                'OverStock' => (int) $row->OverStock,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_control_delivery_table_summary(Request $request, $customer)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        $parts = explode('~', $customer);
        $kodeCustomer = isset($parts[0]) ? addslashes($parts[0]) : '';

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Customer',
            1 => 'PartNum',
            2 => 'PartDesc',
            3 => 'PO',
            4 => 'ActDel',
            5 => 'Cycle',
            6 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Cycle';

        $subQuery = "
        WITH StatusSummary AS (
            SELECT
                OrderDtl.ProdCode,
                OrderDtl.PartNum,
                OrderDtl.LineDesc AS PartDesc,
                [OrderRel].Reference AS Cycle,
                SUM([OrderRel].[SellingReqQty]) AS [Total_SO_Qty],
                SUM(ISNULL([SubQuery2].[Calculated_ShipQty], 0)) AS [Total_Shipment_Qty],
                CASE
                    WHEN SUM(ISNULL([SubQuery2].[Calculated_ShipQty], 0)) = SUM([OrderRel].[SellingReqQty]) THEN 'Close'
                    ELSE 'Open'
                END AS [Status]
            FROM Erp.OrderHed AS [OrderHed]
            INNER JOIN Erp.OrderDtl AS [OrderDtl] ON
                OrderHed.Company = OrderDtl.Company
                AND OrderHed.OrderNum = OrderDtl.OrderNum
            INNER JOIN Erp.OrderRel AS [OrderRel] ON
                OrderDtl.Company = OrderRel.Company
                AND OrderDtl.OrderNum = OrderRel.OrderNum
                AND OrderDtl.OrderLine = OrderRel.OrderLine
            LEFT OUTER JOIN (
                SELECT
                    [ShipDtl1].[OrderNum],
                    [ShipDtl1].[OrderRelNum],
                    [ShipDtl1].[OrderLine],
                    SUM(ShipDtl1.OurInventoryShipQty) AS [Calculated_ShipQty]
                FROM Erp.ShipDtl AS [ShipDtl1]
                GROUP BY
                    [ShipDtl1].[OrderNum],
                    [ShipDtl1].[OrderRelNum],
                    [ShipDtl1].[OrderLine]
            ) AS [SubQuery2] ON
                OrderRel.OrderNum = SubQuery2.OrderNum
                AND OrderRel.OrderRelNum = SubQuery2.OrderRelNum
                AND OrderRel.OrderLine = SubQuery2.OrderLine
            WHERE CAST(
                CASE
                    WHEN [OrderRel].Reference > 12 THEN DATEADD(DAY, 1, OrderHed.RequestDate)
                    ELSE OrderHed.RequestDate
                END AS DATE
            ) = CAST(GETDATE() AS DATE)
            GROUP BY
                OrderDtl.ProdCode,
                OrderDtl.PartNum,
                OrderDtl.LineDesc,
                [OrderRel].Reference
        )
        SELECT
            ProdCode AS [Customer],
            PartNum,
            PartDesc,
            [Total_SO_Qty] AS PO,
            [Total_Shipment_Qty] AS ActDel,
            Cycle,
            [Status]
        FROM StatusSummary
        WHERE Status != 'Open' AND ProdCode = '$kodeCustomer'
        GROUP BY
            PartNum,
            PartDesc,
            [Total_SO_Qty],
            [Total_Shipment_Qty],
            [Status],
            ProdCode,
            Cycle;
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$kodeCustomer]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Customer), strtolower($search)) ||
                    str_contains(strtolower($item->PartNum), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search)) ||
                    str_contains(strtolower($item->ActDel), strtolower($search)) ||
                    str_contains(strtolower($item->PartDesc), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Customer' => $row->Customer,
                'PartNum' => $row->PartNum,
                'PartDesc' => $row->PartDesc,
                'PO' => (int) $row->PO,
                'ActDel' => (int) $row->ActDel,
                'Cycle' => $row->Cycle,
                'Status' => $row->Status,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_control_delivery_min_max_summary(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Customer',
            1 => 'PartNum',
            2 => 'PartName',
            3 => 'OnHand',
            4 => 'Min',
            5 => 'Max',
            6 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Cycle';

        $subQuery = "
        WITH MinMaxSummary AS (
            SELECT
                [Part].[ProdCode] AS [Part_ProdCode],
                [PartWhse].[PartNum] AS [PartWhse_PartNum],
                [Part].[PartDescription] AS [Part_PartDescription],
                [PartBin].[BinNum] AS [PartBin_BinNum],
                [PartBin].[LotNum] AS [PartBin_LotNum],
                CAST([PartBin].[OnhandQty] AS INT) AS [PartBin_OnhandQty],
                CAST([PartPlant].[MinimumQty] AS INT) AS [PartPlant_MinimumQty],
                CAST([PartPlant].[MaximumQty] AS INT) AS [PartPlant_MaximumQty]
            FROM Erp.PartWhse AS PartWhse
            INNER JOIN Erp.PartBin AS PartBin ON
                PartWhse.Company = PartBin.Company
                AND PartWhse.PartNum = PartBin.PartNum
                AND PartWhse.WarehouseCode = PartBin.WarehouseCode
                AND (NOT PartBin.LotNum LIKE '%SAI/SJ/%')
            INNER JOIN Part AS Part ON
                PartBin.Company = Part.Company
                AND PartBin.PartNum = Part.PartNum
            INNER JOIN Erp.PartPlant AS PartPlant ON
                Part.Company = PartPlant.Company
                AND Part.PartNum = PartPlant.PartNum
            INNER JOIN Erp.Warehse AS Warehse ON
                PartWhse.Company = Warehse.Company
                AND PartWhse.WarehouseCode = Warehse.WarehouseCode
            WHERE PartWhse.WarehouseCode = '05-03-01'
        )
        SELECT
            a.[Part_ProdCode] AS Cust,
            a.[PartWhse_PartNum] AS PartNum,
            a.[Part_PartDescription] AS PartName,
            a.[PartBin_OnhandQty] AS OnHand,
            PartPlant_MinimumQty AS [Min],
            PartPlant_MaximumQty AS [Max],
            CASE
                WHEN PartBin_OnhandQty > PartPlant_MaximumQty THEN 'Over'
                WHEN PartBin_OnhandQty < PartPlant_MinimumQty THEN 'Critical'
                WHEN PartBin_OnhandQty >= PartPlant_MinimumQty
                     AND PartBin_OnhandQty <= PartPlant_MaximumQty THEN 'Safe'
                ELSE ''
            END AS Status
        FROM MinMaxSummary a
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Customer), strtolower($search)) ||
                    str_contains(strtolower($item->PartNum), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search)) ||
                    str_contains(strtolower($item->ActDel), strtolower($search)) ||
                    str_contains(strtolower($item->PartDesc), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Customer' => $row->Cust,
                'PartNum' => $row->PartNum,
                'PartName' => $row->PartName,
                'OnHand' => (int) $row->OnHand,
                'Min' => (int) $row->Min,
                'Max' => $row->Max,
                'Status' => $row->Status,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_data_mit_dashboard($kodeHari)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        if (empty($kodeHari)) {
            return response()->json([
                'data_date' => '',
                'data_released' => '',
                'data_received' => ''
            ]);
        }

        $year = date('Y', strtotime($kodeHari));
        $month = date('m', strtotime($kodeHari));

        $query = "
            WITH MITDashboard AS (
                SELECT
                    DAY(a.Date01) AS [Days],
                    MONTH(a.Date01) AS [Months],
                    YEAR(a.Date01) AS [Years],
                    a.Key1 AS [MIT],
                    b.ShortChar01 AS [PartNumber],
                    b.Number01 AS [Qty],
                    b.Number03 AS [Received],
                    CASE
                        WHEN b.Number01 = b.Number03 THEN 'Close'
                        ELSE 'Open'
                    END AS Status
                FROM Ice.UD101 a
                LEFT JOIN Ice.UD101A b ON a.Key1 = b.Key1
                WHERE YEAR(a.Date01) = '$year'
                AND MONTH(a.Date01) = '$month'
            )
            SELECT
                A.Days,
                A.Months,
                A.Years,
                COUNT(*) AS Released,
                SUM(CASE WHEN A.Status = 'Close' THEN 1 ELSE 0 END) AS ReceivedMIT
            FROM MITDashboard A
            GROUP BY A.Days, A.Months, A.Years
            ORDER BY A.Years, A.Months, A.Days
        ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_date' => '',
                'data_released' => '',
                'data_received' => ''
            ]);
        }

        $dataDate = [];
        $dataReleased = [];
        $dataReceived = [];

        foreach ($results as $row) {
            $dataDate[] = (int) $row->Days;
            $dataReleased[] = (int) $row->Released;
            $dataReceived[] = (int) $row->ReceivedMIT;
        }

        return response()->json([
            'data_date' => implode(', ', $dataDate),
            'data_released' => implode(', ', $dataReleased),
            'data_received' => implode(', ', $dataReceived)
        ]);
    }

    public function get_data_mit_dashboard_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");
        $kodeHari = $request->input('date_select');

        if (!$kodeHari || !strtotime($kodeHari)) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Invalid or missing date_select'
            ]);
        }

        $year = date('Y', strtotime($kodeHari));
        $month = date('m', strtotime($kodeHari));
        $day = date('d', strtotime($kodeHari));

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Date',
            1 => 'MIT',
            2 => 'PartNumber',
            3 => 'Qty',
            4 => 'ClassID',
            5 => 'Received',
            6 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Date';

        $subQuery = "
        WITH MITDashboard AS (
            SELECT
                a.Date01 AS [Date],
                a.Key1 AS [MIT],
                b.ShortChar01 AS [PartNumber],
                c.ClassID,
                b.Number01 AS [Qty],
                b.Number03 AS [Received],
                CASE
                    WHEN b.Number01 = b.Number03 THEN 'Close'
                    ELSE 'Open'
                END AS Status
            FROM Ice.UD101 a
            LEFT JOIN Ice.UD101A b ON a.Key1 = b.Key1
            LEFT JOIN Erp.Part c ON c.PartNum = b.ShortChar01
            WHERE
                YEAR(a.Date01) = '$year' AND
                MONTH(a.Date01) = '$month' AND
                DAY(a.Date01) = '$day'
        )
        SELECT
            FORMAT(A.Date, 'dd-MMM-yyyy') AS [Date],
            A.MIT,
            A.PartNumber,
            A.Qty,
            A.ClassID,
            A.Received,
            A.Status
        FROM MITDashboard A
        WHERE A.ClassID = 'FG'
        ORDER BY A.$orderColumn $orderDirection
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $search = strtolower($search);
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Date), $search) ||
                    str_contains(strtolower($item->MIT), $search) ||
                    str_contains(strtolower($item->PartNumber), $search) ||
                    str_contains((string) $item->Qty, $search) ||
                    str_contains(strtolower($item->ClassID), $search) ||
                    str_contains((string) $item->Received, $search) ||
                    str_contains(strtolower($item->Status), $search);
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Date' => $row->Date,
                'MIT' => $row->MIT,
                'PartNumber' => $row->PartNumber,
                'Qty' => (int) $row->Qty,
                'ClassID' => $row->ClassID,
                'Received' => (int) $row->Received,
                'Status' => $row->Status,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_data_gcr_monitoring_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");
        $kodeHari = $request->input('month_select');

        if (!$kodeHari || !strtotime($kodeHari)) {
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Invalid or missing month_select'
            ]);
        }

        $year = date('Y', strtotime($kodeHari));
        $month = date('m', strtotime($kodeHari));

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Customer_Name',
            1 => 'ShipHead_PackNum',
            2 => 'ShipHead_LegalNumber',
            3 => 'ShipDate',
            4 => 'ShipComplete',
            5 => 'CGR',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'ShipHead_ShipDate';

        $subQuery = "
            WITH CGRSummary AS (
                SELECT
                    a.[PackNum] AS [ShipHead_PackNum],
                    a.[LegalNumber] AS [ShipHead_LegalNumber],
                    a.[ShipDate] AS [ShipHead_ShipDate],
                    b.[Name] AS [Customer_Name],
                    CASE
                        WHEN a.[CustomerRecievedStatus_c] = 1 THEN 'GR'
                        ELSE 'NotGR'
                    END AS CGR,
                    CASE
                        WHEN a.[ShipComplete_c] = 1 THEN 'RCV'
                        ELSE 'NotRcv'
                    END AS ShipComplete
                FROM ShipHead AS a
                INNER JOIN Customer AS b
                    ON a.Company = b.Company
                    AND a.CustNum = b.CustNum
                INNER JOIN erp.ShipDtl c
                    ON c.PackNum = a.PackNum
                WHERE
                    MONTH(a.[ShipDate]) = '$month' AND
                    YEAR(a.[ShipDate]) = '$year' AND
                    c.WarehouseCode = '05-03-01'
                GROUP BY
                    a.[PackNum],
                    a.[LegalNumber],
                    a.[ShipDate],
                    b.[Name],
                    a.[CustomerRecievedStatus_c],
                    a.[ShipComplete_c]
            )

            SELECT
                A.Customer_Name,
                A.[ShipHead_PackNum],
                A.[ShipHead_LegalNumber],
                FORMAT(A.[ShipHead_ShipDate], 'dd-MMM-yyyy') AS ShipDate,
                CASE
                    WHEN A.ShipComplete = 'RCV' THEN 'Closed'
                    ELSE 'Open'
                END AS ShipComplete,
                CASE
                    WHEN A.CGR = 'GR' THEN 'Closed'
                    ELSE 'Open'
                END AS CGR
            FROM CGRSummary A
            GROUP BY
                A.[ShipHead_ShipDate],
                A.[ShipHead_PackNum],
                A.[ShipHead_LegalNumber],
                A.Customer_Name,
                A.ShipComplete,
                A.CGR
            ORDER BY A.[ShipHead_ShipDate] DESC;
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $search = strtolower($search);
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->Customer_Name), $search) ||
                    str_contains(strtolower($item->ShipHead_PackNum), $search) ||
                    str_contains(strtolower($item->ShipHead_LegalNumber), $search) ||
                    str_contains(strtolower($item->ShipDate), $search) ||
                    str_contains(strtolower($item->ShipComplete), $search) ||
                    str_contains(strtolower($item->CGR), $search);
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Customer_Name' => $row->Customer_Name,
                'ShipHead_PackNum' => $row->ShipHead_PackNum,
                'ShipHead_LegalNumber' => $row->ShipHead_LegalNumber,
                'ShipDate' => $row->ShipDate,
                'ShipComplete' => $row->ShipComplete,
                'CGR' => $row->CGR
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }
    public function get_data_monitoring_cgr($selectMonth)
    {
        DB::connection('sqlsrv5')->statement("USE [app]");

        if (empty($selectMonth)) {
            return response()->json([
                'data_date' => '',
                'data_total_shipment' => '',
                'data_shipmemt_complete' => ''
            ]);
        }

        $year = date('Y', strtotime($selectMonth));
        $month = date('m', strtotime($selectMonth));

        $query = "
            WITH CGRSummary AS (
            SELECT
                a.[PackNum] AS [ShipHead_PackNum],
                a.[LegalNumber] AS [ShipHead_LegalNumber],
                a.[ShipDate] AS [ShipHead_ShipDate],
                b.[Name] AS [Customer_Name],
                CASE WHEN a.[CustomerRecievedStatus_c] = 1 THEN 'GR' ELSE 'NotGR' END AS CGR,
                CASE WHEN a.[ShipComplete_c] = 1 THEN 'RCV' ELSE 'NotRcv' END AS ShipComplete
            FROM ShipHead AS a
            INNER JOIN Customer AS b
                ON a.Company = b.Company AND a.CustNum = b.CustNum
            WHERE
                YEAR(a.[ShipDate]) = '$year'
                AND MONTH(a.[ShipDate]) = '$month'
        )

        SELECT
            DAY(A.[ShipHead_ShipDate]) AS [Days],
            MONTH(A.[ShipHead_ShipDate]) AS [Months],
            YEAR(A.[ShipHead_ShipDate]) AS [Years],
            COUNT([ShipHead_PackNum]) AS Total_Shipment,
            SUM(CASE WHEN A.ShipComplete = 'RCV' THEN 1 ELSE 0 END) AS Ship_Complete
        FROM CGRSummary A
        GROUP BY
            DAY(A.[ShipHead_ShipDate]),
            MONTH(A.[ShipHead_ShipDate]),
            YEAR(A.[ShipHead_ShipDate])
        ORDER BY Years, Months, Days
        ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_date' => '',
                'data_total_shipment' => '',
                'data_shipmemt_complete' => ''
            ]);
        }

        $dataDate = [];
        $dataTotal = [];
        $dataComplete = [];

        foreach ($results as $row) {
            $dataDate[] = (int) $row->Days;
            $dataTotal[] = (int) $row->Total_Shipment;
            $dataComplete[] = (int) $row->Ship_Complete;
        }

        return response()->json([
            'data_date' => implode(', ', $dataDate),
            'data_total_shipment' => implode(', ', $dataTotal),
            'data_shipmemt_complete' => implode(', ', $dataComplete)
        ]);
    }
    public function dataTablePart(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->Delivery->PartOneLesson();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function PartList(Request $request)
    {
        return response()->json($this->Delivery->PartList());
    }
    public function PartCreate(Request $request)
    {
        $request->validate([
            'PartNum' => 'required|max:50|unique:Part,PartNum',
            'photo' => 'required|max:5700|image|mimes:png,jpg,jpeg'
        ], [
            'PartNum.max' => 'Part Num Maksimum 50 Karakter',
            'PartNum.unique' => 'PartNum Sudah digunakan',
            'photo.max' => 'Photo maksimal 5 MB',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format harus jpg,png,dan jpeg'
        ]);
        $PartNum = $request->PartNum;
        $PartRelation = $request->PartRelation;
        try {
            $DetailPart = $this->Delivery->DetailPart($PartNum);
            if (empty($DetailPart)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Part Revision tidak ditemukan'
                ]);
            }
            if (!empty($PartRelation)) {
                $CheckRelatedParm = $this->Delivery->CheckRelatedParm($PartRelation);
                if ($CheckRelatedParm == false) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Part Relasi tidak ditemukan'
                    ]);
                }
            }
            $file = $request->file('photo');
            $name = str_replace('.', '-', $PartNum) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('part'), $name);
            $data = [
                'PartNum' => $PartNum,
                'Photo' => $name,
                'PartName' => $DetailPart->PartDescription,
                'Model' => $DetailPart->RevisionNum,
                'CreatedAt' => now('Asia/Jakarta')
            ];
            $this->Delivery->CreatePart($data, $PartRelation);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function PartListCard(Request $request)
    {
        $search_select = $request->search;
        $scan_search = $request->scan_search;
        if (!empty($search_select)) {
            $search = $search_select;
        } else if (!empty($scan_search)) {
            $search = $scan_search;
        } else {
            $search = null;
        }

        return response()->json($this->Delivery->cardPartList($search));
    }
    public function PartRelation(Request $request)
    {
        return response()->json($this->Delivery->PartRelation());
    }
    public function QrView($id)
    {
        $id = Crypt::decryptString($id);
        $data = $this->Delivery->QR($id);
        $fileName = 'qr_' . $data->PartNum . '.png';
        $path = storage_path('app/public/' . $fileName);
        QrCode::format('svg')
            ->size(300)
            ->generate($data->PartNum, $path);
        $pdf = Pdf::setPaper('A6', 'portrait')->loadView('dashboard.delivery.one-point-lesson.qr', [
            'data' => $data,
            'qrPath' => $path
        ]);
        return $pdf->stream('qr-' . $data->PartNum . '.pdf');
    }
    public function show_data(Request $request)
    {
        $scan = $request->scan;
        $data = $this->Delivery->cardPartList($scan);
        try {
            if (empty($data)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            $client = new Client("ws://127.0.0.1:8080");
            $value = [
                'action' => 'trigger',
                'channel' => 'one-point-lesson',
                'event' => 'part',
                'data' => [
                    'message' => $data
                ]
            ];
            $client->send(json_encode($value));
            $client->close();
            return response()->json([
                'status' => 200,
                'message' => 'Data ditemukan',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}
