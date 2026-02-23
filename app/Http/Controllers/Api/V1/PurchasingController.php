<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AppModel;
use App\Models\POApproval;
use Carbon\Carbon;


class PurchasingController extends Controller
{

    public function get_purchasing_actualYears($year)
    {
        $str_year = explode('-', $year);
        $year = (int) ($str_year[0] ?? date('Y'));

        $results = DB::connection('sqlsrv5')->table('POActualYears')
            ->select('Years', 'POAmount', 'SalesAmount')
            ->orderBy('Years', )
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_poamount' => [],
                'data_salesamount' => [],
            ]);
        }

        return response()->json([
            'data_poamount' => $results->pluck('POAmount')->toArray(),
            'data_salesamount' => $results->pluck('SalesAmount')->toArray(),
        ]);
    }

    public function get_purchasing_actualMonth($year)
    {
        $str_year = explode('-', $year);
        $year = (int) ($str_year[0] ?? date('Y'));

        $results = DB::connection('sqlsrv5')->table('POActualMonths')
            ->select('Years', 'Months', 'POAmount', 'SalesAmount')
            ->where('Years', $year)
            ->orderBy('Months', )
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_poamount_month' => [],
                'data_salesamount_month' => [],
            ]);
        }

        return response()->json([
            'data_poamount_month' => $results->pluck('POAmount')->toArray(),
            'data_salesamount_month' => $results->pluck('SalesAmount')->toArray(),
        ]);
    }

    public function get_purchasing_pocategoryYear($year)
    {
        $str_year = explode('-', $year);
        $year = (int) ($str_year[0] ?? date('Y'));

        $results = DB::connection('sqlsrv5')->table('POActualCategoryYears')
            ->select('Years', 'Category', 'POAmount')
            ->where('Years', $year)
            ->orderBy('POAmount', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_category_categoryyear' => [],
                'data_poamount_categoryyear' => [],
            ]);
        }

        return response()->json([
            'data_category_categoryyear' => $results->pluck('Category')->toArray(),
            'data_poamount_categoryyear' => $results->pluck('POAmount')->toArray(),
        ]);
    }

    public function get_purchasing_pocategoryMonth($year)
    {

        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')->table('POACtualCategoryMonths')
            ->select(
                'Years',
                'Months',
                'Category',
                'POAmount'
            )

            ->where('Years', $year)
            ->where('Months', $month)
            ->orderBy('POAmount', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_category_categorymonth' => [],
                'data_poamount_categorymonth' => [],
            ]);
        }

        return response()->json([
            'data_category_categorymonth' => $results->pluck('Category')->toArray(),
            'data_poamount_categorymonth' => $results->pluck('POAmount')->toArray(),
        ]);
    }

    public function get_purchasing_pocategoryMonth_Stack($year)
    {
        $parsedYear = explode('-', $year);
        $year = (int) ($parsedYear[0] ?? date('Y'));

        $categoriesOrder = [
            'RM' => 1,
            'PS' => 2,
            'PP' => 3,
            'SBC' => 4,
        ];
        $categoriesMonth = [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];

        $results = DB::connection('sqlsrv5')
            ->table('POACtualCategoryMonths')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw("SUM(POAmount) AS Total")
            )
            ->where('Years', $year)
            ->whereIn('Category', array_keys($categoriesOrder))
            ->whereIn('Months', array_keys($categoriesMonth))
            ->groupBy('Years', 'Months', 'Category')

            ->get();

        $dataMonth = [];
        $dataCategories = [];
        $dataAmounts = [];

        foreach ($results as $result) {
            $dataMonth[] = $result->Months;
            $dataCategories[] = $result->Category;
            $dataAmounts[] = (string) $result->Total;
        }

        return response()->json([
            'data_months_categorymonthstack' => $dataMonth,
            'data_category_categorymonthstack' => $dataCategories,
            'data_poamount_categorymonthstack' => $dataAmounts,
        ]);
    }

    public function get_purchasing_pocategoryByMonth($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $categoriesOrder = [
            'RM' => 1,
            'PS' => 2,
            'PP' => 3,
            'SBC' => 4,
        ];

        $results = DB::connection('sqlsrv5')
            ->table('POACtualCategoryMonths')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw("SUM(POAmount) AS Total")
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->whereIn('Category', array_keys($categoriesOrder))
            ->groupBy('Years', 'Months', 'Category')
            ->get();

        $responseData = [
            'data_months_bystack' => [],
            'data_category_bystack' => [],
            'data_poamount_bystack' => []
        ];

        foreach ($results as $result) {
            $responseData['data_months_bystack'][] = $result->Months;
            $responseData['data_category_bystack'][] = $result->Category;
            $responseData['data_poamount_bystack'][] = (string) $result->Total;
        }

        return response()->json($responseData);
    }

    public function get_purchase_po_month(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('POActualMonths')
            ->select('Years', 'Months', 'POAmount', 'SalesAmount')
            ->where('Years', $year)
            ->orderBy('Months');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'POAmount', 'SalesAmount'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%")
                    ->orWhere('SalesAmount', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_table_month_stack(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('POACtualCategoryMonths')
            ->select('Years', 'Months', 'Category', 'POAmount')
            ->where('Years', $year)
            ->orderBy('Months');

        if (!is_null($month)) {
            $query->where('Years', $year);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Category', 'POAmount'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_purchase_po_year()
    {

        $data = DB::connection('sqlsrv5')
            ->table('POActualYears')
            ->select('Years', 'POAmount', 'SalesAmount')
            ->orderBy('Years', 'asc')
            ->get();

        if (!empty($search)) {
            $data->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%")
                    ->orWhere('SalesAmount', 'LIKE', "%{$search}%");
            });
        }
        return response()->json([
            'success' => true,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_purchase_category_year(Request $request)
    {
        $inputYear = $request->input('yearMonth', date('Y'));
        $yearParts = explode('-', $inputYear);
        $year = (int) (count($yearParts) === 2 ? $yearParts[0] : $inputYear);

        $query = DB::connection('sqlsrv5')
            ->table('POActualCategoryYears')
            ->select('Years', 'Category', 'POAmount')
            ->where('Years', $year)
            ->orderBy('POAmount', 'desc');


        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Category', 'POAmount',];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_purchase_category_month(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('POACtualCategoryMonths')
            ->select('Years', 'Months', 'Category', 'POAmount', )
            ->where('Years', $year)
            ->orderBy('Months');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Category', 'POAmount'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_purchase_stack_bymonth(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('POACtualCategoryMonths')
            ->select('Years', 'Months', 'Category', 'POAmount')
            ->where('Years', $year)
            ->where('Months', $month)
            ->orderBy('Months');

        if (!is_null($month)) {
            $query->where('Years', $year);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Category', 'POAmount'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere('POAmount', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_purchase_po_project(Request $request)
    {

        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));


        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'OrderDate',
            1 => 'PONum',
            2 => 'RelQty',
            3 => 'TotalGR',
            4 => 'OutstandingQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
            WITH InvoicePerPoRel AS (
                SELECT
                    id.Company,
                    rd.PONum,
                    rd.POLine,
                    rd.PORelNum,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(id.DocExtCost) AS TotalInvoiced
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
            ),

            PaymentPerPoRel AS (
                SELECT
                    rd.Company,
                    rd.PONum,
                    rd.POLine,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(tr.TranAmt) AS TotalPaid
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                INNER JOIN app.Erp.APInvHed ih 
                    ON id.Company = ih.Company 
                    AND id.InvoiceNum = ih.InvoiceNum
                INNER JOIN app.Erp.APTran tr 
                    ON ih.Company = tr.Company 
                    AND ih.InvoiceNum = tr.InvoiceNum 
                    AND tr.TranType = 'Pay'
                WHERE rd.PONum = 3177
                GROUP BY rd.Company, rd.PONum, rd.POLine
            )

            SELECT
                ph.PONum,
                pd.POLine,
                pr.PORelNum,
                ph.OrderDate,
                pd.PartNum,
                pd.LineDesc,
                pr.RelQty,
                pd.IUM,
                ip.TotalGR,
                pd.UnitCost,
                (pr.RelQty * pd.UnitCost) AS ReleaseCost,
                (pr.RelQty - ip.TotalGR) AS OutstandingQty,
                ip.TotalInvoiced,
                (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
            FROM
                app.Erp.POHeader ph
            INNER JOIN app.Erp.PoDetail pd 
                ON ph.Company = pd.Company 
                AND ph.PONum = pd.PONum
            INNER JOIN app.Erp.PoRel pr 
                ON pd.Company = pr.Company 
                AND pd.PONum = pr.PONum 
                AND pd.POLine = pr.POLine
            LEFT JOIN InvoicePerPoRel ip 
                ON pr.Company = ip.Company 
                AND pr.PONum = ip.PONum 
                AND pr.POLine = ip.POLine 
                AND pr.PORelNum = ip.PORelNum
            WHERE
                ph.BuyerID = 'PROJECT' 
                AND ph.OrderDate BETWEEN '$from' AND '$to'
            ORDER BY pr.PONum, pr.POLine, pr.PORelNum
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$from], [$to]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->OrderDate), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->RelQty), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->ReleaseCost), strtolower($search));
                str_contains(strtolower($item->OutstandingQty), strtolower($search));
                str_contains(strtolower($item->TotalInvoiced), strtolower($search));
                str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'POLine' => $row->POLine,
                'PONum' => (int) $row->PONum,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) $row->TotalGR,
                'OutstandingQty' => (int) $row->OutstandingQty,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_purchase_po_project_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if (!$startDate || !$endDate) {
            return response()->json(['data' => []], 400);
        }

        DB::connection('sqlsrv5')->statement("USE [app]");

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        ),

        PaymentPerPoRel AS (
            SELECT
                rd.Company,
                rd.PONum,
                rd.POLine,
                SUM(rd.OurQty) AS TotalGR,
                SUM(tr.TranAmt) AS TotalPaid
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            INNER JOIN app.Erp.APInvHed ih 
                ON id.Company = ih.Company 
                AND id.InvoiceNum = ih.InvoiceNum
            INNER JOIN app.Erp.APTran tr 
                ON ih.Company = tr.Company 
                AND ih.InvoiceNum = tr.InvoiceNum 
                AND tr.TranType = 'Pay'
            WHERE rd.PONum = 3177
            GROUP BY rd.Company, rd.PONum, rd.POLine
        )

        SELECT
            ph.PONum,
            pd.POLine,
            pr.PORelNum,
            ph.OrderDate,
            pd.PartNum,
            pd.LineDesc,
            pr.RelQty,
            pd.IUM,
            ip.TotalGR,
            pd.UnitCost,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            (pr.RelQty - ip.TotalGR) AS OutstandingQty,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'PROJECT' 
            AND ph.OrderDate BETWEEN ? AND ?
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$startDate, $endDate]);
        $collection = collect($rawData);

        $data = $collection->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'POLine' => (int) $row->POLine,
                'PONum' => (int) $row->PONum,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) ($row->TotalGR ?? 0),
                'OutstandingQty' => (int) ($row->OutstandingQty ?? 0),
            ];
        })->values();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function get_purchase_po_ppic_export(Request $request)
    {
        $startDate = $request->input('startDate', date('Y-m-d'));
        $toDate = $request->input('endDate', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        )
        SELECT
            ph.PONum,
            pd.POLine,
            pr.PORelNum,
            ph.OrderDate,
            pd.PartNum,
            pd.LineDesc,
            pr.RelQty,
            pd.IUM,
            ip.TotalGR,
            pd.UnitCost,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            (pr.RelQty - ISNULL(ip.TotalGR, 0)) AS OutstandingQty,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'PPIC' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY pr.PONum, pr.POLine, pr.PORelNum
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$from, $to]);
        $collection = collect($rawData);

        $formattedData = $collection->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) ($row->TotalGR ?? 0),
                'OutstandingQty' => (int) $row->OutstandingQty,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'IUM' => $row->IUM,
                'UnitCost' => (float) $row->UnitCost,
                'ReleaseCost' => (float) $row->ReleaseCost,
                'TotalInvoiced' => (float) ($row->TotalInvoiced ?? 0),
                'BalanceRemaining' => (float) $row->BalanceRemaining,
            ];
        })->values()->toArray();

        return response()->json([
            'data' => $formattedData,
        ]);
    }

    public function get_purchase_data_po_project_ammount(Request $request)
    {

        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'ReleaseCost',
            3 => 'TotalInvoiced',
            4 => 'BalanceRemaining',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
            WITH InvoicePerPoRel AS (
                SELECT
                    id.Company,
                    rd.PONum,
                    rd.POLine,
                    rd.PORelNum,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(id.DocExtCost) AS TotalInvoiced
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
            ),

            PaymentPerPoRel AS (
                SELECT
                    rd.Company,
                    rd.PONum,
                    rd.POLine,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(tr.TranAmt) AS TotalPaid
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                INNER JOIN app.Erp.APInvHed ih 
                    ON id.Company = ih.Company 
                    AND id.InvoiceNum = ih.InvoiceNum
                INNER JOIN app.Erp.APTran tr 
                    ON ih.Company = tr.Company 
                    AND ih.InvoiceNum = tr.InvoiceNum 
                    AND tr.TranType = 'Pay'
                WHERE rd.PONum = 3177
                GROUP BY rd.Company, rd.PONum, rd.POLine
            )

            SELECT
                ph.PONum,
                pd.POLine,
                pr.PORelNum,
                ph.OrderDate,
                pd.PartNum,
                pd.LineDesc,
                pr.RelQty,
                pd.IUM,
                ip.TotalGR,
                pd.UnitCost,
                (pr.RelQty * pd.UnitCost) AS ReleaseCost,
                (pr.RelQty - ip.TotalGR) AS OutstandingQty,
                ip.TotalInvoiced,
                (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
            FROM
                app.Erp.POHeader ph
            INNER JOIN app.Erp.PoDetail pd 
                ON ph.Company = pd.Company 
                AND ph.PONum = pd.PONum
            INNER JOIN app.Erp.PoRel pr 
                ON pd.Company = pr.Company 
                AND pd.PONum = pr.PONum 
                AND pd.POLine = pr.POLine
            LEFT JOIN InvoicePerPoRel ip 
                ON pr.Company = ip.Company 
                AND pr.PONum = ip.PONum 
                AND pr.POLine = ip.POLine 
                AND pr.PORelNum = ip.PORelNum
            WHERE
                ph.BuyerID = 'PROJECT' 
                AND ph.OrderDate BETWEEN '$from' AND '$to'
            ORDER BY pr.PONum, pr.POLine, pr.PORelNum
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->OrderDate), strtolower($search)) ||
                    str_contains(strtolower($item->ReleaseCost), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'ReleaseCost' => (int) $row->ReleaseCost,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
                'BalanceRemaining' => (int) $row->BalanceRemaining,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }
    public function get_purchase_data_po_project_ammount_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if (!$startDate || !$endDate) {
            return response()->json(['data' => []], 400);
        }

        DB::connection('sqlsrv5')->statement("USE [app]");

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        ),

        PaymentPerPoRel AS (
            SELECT
                rd.Company,
                rd.PONum,
                rd.POLine,
                SUM(rd.OurQty) AS TotalGR,
                SUM(tr.TranAmt) AS TotalPaid
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            INNER JOIN app.Erp.APInvHed ih 
                ON id.Company = ih.Company 
                AND id.InvoiceNum = ih.InvoiceNum
            INNER JOIN app.Erp.APTran tr 
                ON ih.Company = tr.Company 
                AND ih.InvoiceNum = tr.InvoiceNum 
                AND tr.TranType = 'Pay'
            GROUP BY rd.Company, rd.PONum, rd.POLine
        )

        SELECT
            ph.PONum,
            pd.POLine,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'PROJECT' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY ph.PONum, pr.POLine, pr.PORelNum
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$startDate, $endDate]);
        $collection = collect($rawData);

        $data = $collection->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'ReleaseCost' => 'Rp. ' . number_format((float) ($row->ReleaseCost ?? 0), 0, ',', '.'),
                'TotalInvoiced' => 'Rp. ' . number_format((float) ($row->TotalInvoiced ?? 0), 0, ',', '.'),
                'BalanceRemaining' => 'Rp. ' . number_format((float) ($row->BalanceRemaining ?? 0), 0, ',', '.')
            ];
        })->values();

        return response()->json(['data' => $data]);
    }

    public function get_received_po_project(Request $request)
    {
        $vendor = trim(str_replace('"', '', $request->input('vendorName')));

        DB::connection('sqlsrv5')->statement("USE [sai_db]");
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'VendorName',
            1 => 'PONum',
            2 => 'POLine',
            3 => 'PartName',
            4 => 'ReqQty',
            5 => 'RemainingQty',
            6 => 'ReceivedQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
            SELECT 
                VendorName,
                PONum,
                POLine,
                PartName,
                ReqQty,
                RemainingQty,
                ReceivedQty
            FROM RcvPOProject
            WHERE VendorName = '$vendor'
            ORDER BY PONum, POLine
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->VendorName), strtolower($search)) ||
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->ReqQty), strtolower($search)) ||
                    str_contains(strtolower($item->RemainingQty), strtolower($search));
                str_contains(strtolower($item->ReceivedQty), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'VendorName' => $row->VendorName,
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PartName' => $row->PartName,
                'ReqQty' => (int) $row->ReqQty,
                'RemainingQty' => (int) $row->RemainingQty,
                'ReceivedQty' => (int) $row->ReceivedQty,

            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_received_po_project_export(Request $request)
    {
        $vendor = trim(str_replace('"', '', $request->input('vendor')));

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $subQuery = "
        SELECT 
            VendorName,
            PONum,
            POLine,
            PartName,
            ReqQty,
            RemainingQty,
            ReceivedQty
        FROM RcvPOProject
        WHERE VendorName = '$vendor'
        ORDER BY PONum, POLine
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        $data = $collection->map(function ($row) {
            return [
                'VendorName' => $row->VendorName,
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PartName' => $row->PartName,
                'ReqQty' => (int) $row->ReqQty,
                'RemainingQty' => (int) $row->RemainingQty,
                'ReceivedQty' => (int) $row->ReceivedQty,
            ];
        })->values();

        return response()->json(['vendor' => $vendor, 'data' => $data]);
    }





    public function get_data_po_gr_project()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $query = "
        WITH PONumStatus AS (
            SELECT 
                BuyerID,
                PONum,
                SUM(TotalQtyPO) AS TotalQtyPO,
                SUM(TotalGR) AS TotalGR,
                CASE 
                    WHEN SUM(TotalGR) < SUM(TotalQtyPO) THEN 'UnderGR'
                    WHEN SUM(TotalGR) > SUM(TotalQtyPO) THEN 'OverGR'
                    ELSE 'OK'
                END AS Status
            FROM 
                PO_GR_AP
            WHERE 
                BuyerID = 'Project'
            GROUP BY 
                BuyerID, PONum
        )

        SELECT
            BuyerID,
            SUM(CASE WHEN Status = 'UnderGR' THEN 1 ELSE 0 END) AS UnderGR,
            SUM(CASE WHEN Status = 'OverGR' THEN 1 ELSE 0 END) AS OverGR,
            SUM(CASE WHEN Status = 'OK' THEN 1 ELSE 0 END) AS OK
        FROM 
            PONumStatus
        GROUP BY
            BuyerID;
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_total_oke' => [],
                'data_total_under' => [],
                'data_total_over' => []
            ]);
        }

        foreach ($results as $row) {
            $data_oke[] = (int) $row->OK;
            $data_under[] = (int) $row->UnderGR;
            $data_over[] = (int) $row->OverGR;
        }

        return response()->json([
            'data_total_oke' => $data_oke,
            'data_total_under' => $data_under,
            'data_total_over' => $data_over
        ]);
    }

    public function get_data_po_project_receipt($year)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')
            ->table('GrPOMonthly')
            ->select(
                'Years',
                'Months',
                'BuyerID',
                'ReceiptStatus',
                DB::raw('SUM(TotalPO) as TotalPO')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('BuyerID', 'PROJECT')
            ->groupBy('Years', 'Months', 'BuyerID', 'ReceiptStatus')
            ->get();

        $data = [
            'data_fully_received' => 0,
            'data_partially_received' => 0,
            'data_open' => 0,
        ];

        foreach ($results as $row) {
            $status = strtolower(str_replace(' ', '_', $row->ReceiptStatus));
            if (isset($data["data_{$status}"])) {
                $data["data_{$status}"] = (int) $row->TotalPO;
            }
        }

        return response()->json($data);
    }


    public function get_data_po_ppic_receipt($year)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')
            ->table('GrPOMonthly')
            ->select(
                'Years',
                'Months',
                'BuyerID',
                'ReceiptStatus',
                DB::raw('SUM(TotalPO) as TotalPO')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('BuyerID', 'PPIC')
            ->groupBy('Years', 'Months', 'BuyerID', 'ReceiptStatus')
            ->get();

        $data = [
            'data_fully_received' => 0,
            'data_partially_received' => 0,
            'data_open' => 0,
        ];

        foreach ($results as $row) {
            $status = strtolower(str_replace(' ', '_', $row->ReceiptStatus));
            if (isset($data["data_{$status}"])) {
                $data["data_{$status}"] = (int) $row->TotalPO;
            }
        }

        return response()->json($data);
    }

    public function get_data_po_reguler_receipt($year)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')
            ->table('GrPOMonthly')
            ->select(
                'Years',
                'Months',
                'BuyerID',
                'ReceiptStatus',
                DB::raw('SUM(TotalPO) as TotalPO')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('BuyerID', 'REGULER')
            ->groupBy('Years', 'Months', 'BuyerID', 'ReceiptStatus')
            ->get();

        $data = [
            'data_fully_received' => 0,
            'data_partially_received' => 0,
            'data_open' => 0,
        ];

        foreach ($results as $row) {
            $status = strtolower(str_replace(' ', '_', $row->ReceiptStatus));
            if (isset($data["data_{$status}"])) {
                $data["data_{$status}"] = (int) $row->TotalPO;
            }
        }

        return response()->json($data);
    }




    public function get_data_po_project_table_gr(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        $status = $request->input('status');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'PORel',
            3 => 'PartNumber',
            4 => 'PartDescription',
            5 => 'TotalQtyPO',
            6 => 'TotalGR',
            7 => 'TotalInvoiced',
            8 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
SELECT 
    PONum, 
    POLine, 
    PORel, 
    PartNumber, 
    PartDescription, 
    TotalQtyPO, 
    TotalGR, 
    TotalInvoiced,
    CASE 
        WHEN TotalGR < TotalQtyPO THEN 'Under GR'
        WHEN TotalGR > TotalQtyPO THEN 'Over GR'
        ELSE 'OK'
    END AS Status
FROM 
    PO_GR_AP
WHERE 
    BuyerID = 'Project';
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PartNumber), strtolower($search)) ||
                    str_contains(strtolower($item->PartDescription), strtolower($search)) ||
                    str_contains(strtolower($item->TotalQtyPO), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
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

    public function get_data_po_project_table_gr_export(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $status = $request->input('status');

        $subQuery = "
        SELECT 
            PONum, 
            POLine, 
            PORel, 
            PartNumber, 
            PartDescription, 
            TotalQtyPO, 
            TotalGR, 
            TotalInvoiced,
            CASE 
                WHEN TotalGR < TotalQtyPO THEN 'Under GR'
                WHEN TotalGR > TotalQtyPO THEN 'Over GR'
                ELSE 'OK'
            END AS Status
        FROM 
            PO_GR_AP
        WHERE 
            BuyerID = 'Project'
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        $data = $collection->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
                'Status' => $row->Status,
            ];
        })->values();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function get_data_purchase_po_ppic()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $query = "
        WITH PONumStatus AS (
            SELECT 
                BuyerID,
                PONum,
                SUM(TotalQtyPO) AS TotalQtyPO,
                SUM(TotalGR) AS TotalGR,
                CASE 
                    WHEN SUM(TotalGR) < SUM(TotalQtyPO) THEN 'UnderGR'
                    WHEN SUM(TotalGR) > SUM(TotalQtyPO) THEN 'OverGR'
                    ELSE 'OK'
                END AS Status
            FROM 
                PO_GR_AP
            WHERE 
                BuyerID = 'PPIC'
            GROUP BY 
                BuyerID, PONum
        )

        SELECT
            BuyerID,
            SUM(CASE WHEN Status = 'UnderGR' THEN 1 ELSE 0 END) AS UnderGR,
            SUM(CASE WHEN Status = 'OverGR' THEN 1 ELSE 0 END) AS OverGR,
            SUM(CASE WHEN Status = 'OK' THEN 1 ELSE 0 END) AS OK
        FROM 
            PONumStatus
        GROUP BY
            BuyerID;
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_total_oke' => [],
                'data_total_under' => [],
                'data_total_over' => []
            ]);
        }

        foreach ($results as $row) {
            $data_oke[] = (int) $row->OK;
            $data_under[] = (int) $row->UnderGR;
            $data_over[] = (int) $row->OverGR;
        }

        return response()->json([
            'data_total_oke' => $data_oke,
            'data_total_under' => $data_under,
            'data_total_over' => $data_over
        ]);
    }
    public function get_data_purchasing_po_ppic_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        $status = $request->input('status');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'PORel',
            3 => 'PartNumber',
            4 => 'PartDescription',
            5 => 'TotalQtyPO',
            6 => 'TotalGR',
            7 => 'TotalInvoiced',
            8 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
SELECT 
    PONum, 
    POLine, 
    PORel, 
    PartNumber, 
    PartDescription, 
    TotalQtyPO, 
    TotalGR, 
    TotalInvoiced,
    CASE 
        WHEN TotalGR < TotalQtyPO THEN 'Under GR'
        WHEN TotalGR > TotalQtyPO THEN 'Over GR'
        ELSE 'OK'
    END AS Status
FROM 
    PO_GR_AP
WHERE 
    BuyerID = 'PPIC';
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PartNumber), strtolower($search)) ||
                    str_contains(strtolower($item->PartDescription), strtolower($search)) ||
                    str_contains(strtolower($item->TotalQtyPO), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
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

    public function get_data_purchasing_po_ppic_table_export(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $status = $request->input('status');

        $subQuery = "
SELECT 
    PONum, 
    POLine, 
    PORel, 
    PartNumber, 
    PartDescription, 
    TotalQtyPO, 
    TotalGR, 
    TotalInvoiced,
    CASE 
        WHEN TotalGR < TotalQtyPO THEN 'Under GR'
        WHEN TotalGR > TotalQtyPO THEN 'Over GR'
        ELSE 'OK'
    END AS Status
FROM 
    PO_GR_AP
WHERE 
    BuyerID = 'PPIC';
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        $formattedData = $collection->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
                'Status' => $row->Status,
            ];
        })->values()->toArray();

        return response()->json([
            'data' => $formattedData,
        ]);
    }

    public function get_purchase_po_ppic(Request $request)
    {

        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'OrderDate',
            1 => 'PONum',
            2 => 'RelQty',
            3 => 'TotalGR',
            4 => 'OutstandingQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
            WITH InvoicePerPoRel AS (
                SELECT
                    id.Company,
                    rd.PONum,
                    rd.POLine,
                    rd.PORelNum,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(id.DocExtCost) AS TotalInvoiced
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
            ),

            PaymentPerPoRel AS (
                SELECT
                    rd.Company,
                    rd.PONum,
                    rd.POLine,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(tr.TranAmt) AS TotalPaid
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                INNER JOIN app.Erp.APInvHed ih 
                    ON id.Company = ih.Company 
                    AND id.InvoiceNum = ih.InvoiceNum
                INNER JOIN app.Erp.APTran tr 
                    ON ih.Company = tr.Company 
                    AND ih.InvoiceNum = tr.InvoiceNum 
                    AND tr.TranType = 'Pay'
                WHERE rd.PONum = 3177
                GROUP BY rd.Company, rd.PONum, rd.POLine
            )

            SELECT
                ph.PONum,
                pd.POLine,
                pr.PORelNum,
                ph.OrderDate,
                pd.PartNum,
                pd.LineDesc,
                pr.RelQty,
                pd.IUM,
                ip.TotalGR,
                pd.UnitCost,
                (pr.RelQty * pd.UnitCost) AS ReleaseCost,
                (pr.RelQty - ip.TotalGR) AS OutstandingQty,
                ip.TotalInvoiced,
                (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
            FROM
                app.Erp.POHeader ph
            INNER JOIN app.Erp.PoDetail pd 
                ON ph.Company = pd.Company 
                AND ph.PONum = pd.PONum
            INNER JOIN app.Erp.PoRel pr 
                ON pd.Company = pr.Company 
                AND pd.PONum = pr.PONum 
                AND pd.POLine = pr.POLine
            LEFT JOIN InvoicePerPoRel ip 
                ON pr.Company = ip.Company 
                AND pr.PONum = ip.PONum 
                AND pr.POLine = ip.POLine 
                AND pr.PORelNum = ip.PORelNum
            WHERE
                ph.BuyerID = 'PPIC' 
                AND ph.OrderDate BETWEEN '$from' AND '$to'
            ORDER BY pr.PONum, pr.POLine, pr.PORelNum
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$from], [$to]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->OrderDate), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->RelQty), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->ReleaseCost), strtolower($search));
                str_contains(strtolower($item->OutstandingQty), strtolower($search));
                str_contains(strtolower($item->TotalInvoiced), strtolower($search));
                str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'POLine' => $row->POLine,
                'PONum' => (int) $row->PONum,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) $row->TotalGR,
                'OutstandingQty' => (int) $row->OutstandingQty,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_purchase_data_po_ppic_ammount(Request $request)
    {

        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'ReleaseCost',
            3 => 'TotalInvoiced',
            4 => 'BalanceRemaining',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
            WITH InvoicePerPoRel AS (
                SELECT
                    id.Company,
                    rd.PONum,
                    rd.POLine,
                    rd.PORelNum,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(id.DocExtCost) AS TotalInvoiced
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
            ),

            PaymentPerPoRel AS (
                SELECT
                    rd.Company,
                    rd.PONum,
                    rd.POLine,
                    SUM(rd.OurQty) AS TotalGR,
                    SUM(tr.TranAmt) AS TotalPaid
                FROM app.Erp.RcvDtl rd
                INNER JOIN app.Erp.APInvDtl id 
                    ON rd.Company = id.Company 
                    AND rd.PackSlip = id.PackSlip 
                    AND rd.PackLine = id.PackLine
                INNER JOIN app.Erp.APInvHed ih 
                    ON id.Company = ih.Company 
                    AND id.InvoiceNum = ih.InvoiceNum
                INNER JOIN app.Erp.APTran tr 
                    ON ih.Company = tr.Company 
                    AND ih.InvoiceNum = tr.InvoiceNum 
                    AND tr.TranType = 'Pay'
                WHERE rd.PONum = 3177
                GROUP BY rd.Company, rd.PONum, rd.POLine
            )

            SELECT
                ph.PONum,
                pd.POLine,
                pr.PORelNum,
                ph.OrderDate,
                pd.PartNum,
                pd.LineDesc,
                pr.RelQty,
                pd.IUM,
                ip.TotalGR,
                pd.UnitCost,
                (pr.RelQty * pd.UnitCost) AS ReleaseCost,
                (pr.RelQty - ip.TotalGR) AS OutstandingQty,
                ip.TotalInvoiced,
                (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
            FROM
                app.Erp.POHeader ph
            INNER JOIN app.Erp.PoDetail pd 
                ON ph.Company = pd.Company 
                AND ph.PONum = pd.PONum
            INNER JOIN app.Erp.PoRel pr 
                ON pd.Company = pr.Company 
                AND pd.PONum = pr.PONum 
                AND pd.POLine = pr.POLine
            LEFT JOIN InvoicePerPoRel ip 
                ON pr.Company = ip.Company 
                AND pr.PONum = ip.PONum 
                AND pr.POLine = ip.POLine 
                AND pr.PORelNum = ip.PORelNum
            WHERE
                ph.BuyerID = 'PPIC' 
                AND ph.OrderDate BETWEEN '$from' AND '$to'
            ORDER BY pr.PONum, pr.POLine, pr.PORelNum
        ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->OrderDate), strtolower($search)) ||
                    str_contains(strtolower($item->ReleaseCost), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'ReleaseCost' => (int) $row->ReleaseCost,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
                'BalanceRemaining' => (int) $row->BalanceRemaining,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_purchase_data_po_ppic_ammount_export(Request $request)
    {
        $startDate = $request->input('startDate', date('Y-m-d'));
        $toDate = $request->input('endDate', date('Y-m-d'));
        $from = date('Y-m-d', strtotime($startDate));
        $to = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        )
        SELECT
            ph.PONum,
            pd.POLine,
            pr.PORelNum,
            ph.OrderDate,
            pd.PartNum,
            pd.LineDesc,
            pr.RelQty,
            pd.IUM,
            ip.TotalGR,
            pd.UnitCost,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            (pr.RelQty - ISNULL(ip.TotalGR, 0)) AS OutstandingQty,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'PPIC' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY pr.PONum, pr.POLine, pr.PORelNum
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$from, $to]);
        $collection = collect($rawData);

        $formattedData = $collection->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'ReleaseCost' => (float) $row->ReleaseCost,
                'TotalInvoiced' => (float) ($row->TotalInvoiced ?? 0),
                'BalanceRemaining' => (float) $row->BalanceRemaining,
                'OrderDate' => $row->OrderDate,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'RelQty' => (int) $row->RelQty,
                'IUM' => $row->IUM,
                'TotalGR' => (int) ($row->TotalGR ?? 0),
                'OutstandingQty' => (int) $row->OutstandingQty,
                'UnitCost' => (float) $row->UnitCost,
            ];
        })->values()->toArray();

        return response()->json([
            'data' => $formattedData,
        ]);
    }

    public function get_data_purchase_po_reguler()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $query = "
        WITH PONumStatus AS (
            SELECT 
                BuyerID,
                PONum,
                SUM(TotalQtyPO) AS TotalQtyPO,
                SUM(TotalGR) AS TotalGR,
                CASE 
                    WHEN SUM(TotalGR) < SUM(TotalQtyPO) THEN 'UnderGR'
                    WHEN SUM(TotalGR) > SUM(TotalQtyPO) THEN 'OverGR'
                    ELSE 'OK'
                END AS Status
            FROM 
                PO_GR_AP
            WHERE 
                BuyerID = 'Reguler'
            GROUP BY 
                BuyerID, PONum

        )

        SELECT
            BuyerID,
            SUM(CASE WHEN Status = 'UnderGR' THEN 1 ELSE 0 END) AS UnderGR,
            SUM(CASE WHEN Status = 'OverGR' THEN 1 ELSE 0 END) AS OverGR,
            SUM(CASE WHEN Status = 'OK' THEN 1 ELSE 0 END) AS OK
        FROM 
            PONumStatus
        GROUP BY
            BuyerID;
    ";

        $results = DB::connection('sqlsrv5')->select($query);

        if (empty($results)) {
            return response()->json([
                'data_total_oke' => [],
                'data_total_under' => [],
                'data_total_over' => []
            ]);
        }

        foreach ($results as $row) {
            $data_oke[] = (int) $row->OK;
            $data_under[] = (int) $row->UnderGR;
            $data_over[] = (int) $row->OverGR;
        }

        return response()->json([
            'data_total_oke' => $data_oke,
            'data_total_under' => $data_under,
            'data_total_over' => $data_over
        ]);
    }
    public function get_data_purchasing_po_reguler_table(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        $status = $request->input('status');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'PORel',
            3 => 'PartNumber',
            4 => 'PartDescription',
            5 => 'TotalQtyPO',
            6 => 'TotalGR',
            7 => 'TotalInvoiced',
            8 => 'Status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Customer';

        $subQuery = "
SELECT 
    PONum, 
    POLine, 
    PORel, 
    PartNumber, 
    PartDescription, 
    TotalQtyPO, 
    TotalGR, 
    TotalInvoiced,
    CASE 
        WHEN TotalGR < TotalQtyPO THEN 'Under GR'
        WHEN TotalGR > TotalQtyPO THEN 'Over GR'
        ELSE 'OK'
    END AS Status
FROM 
    PO_GR_AP
WHERE 
    BuyerID = 'Reguler';
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PartNumber), strtolower($search)) ||
                    str_contains(strtolower($item->PartDescription), strtolower($search)) ||
                    str_contains(strtolower($item->TotalQtyPO), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->Status), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
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

    public function get_data_purchasing_po_reguler_table_export(Request $request)
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $status = $request->input('status');

        $subQuery = "
SELECT 
    PONum, 
    POLine, 
    PORel, 
    PartNumber, 
    PartDescription, 
    TotalQtyPO, 
    TotalGR, 
    TotalInvoiced,
    CASE 
        WHEN TotalGR < TotalQtyPO THEN 'Under GR'
        WHEN TotalGR > TotalQtyPO THEN 'Over GR'
        ELSE 'OK'
    END AS Status
FROM 
    PO_GR_AP
WHERE 
    BuyerID = 'Reguler';
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery);
        $collection = collect($rawData);

        if (!empty($status)) {
            $collection = $collection->filter(function ($item) use ($status) {
                return $item->Status == $status;
            });
        }

        $data = $collection->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'PORel' => (int) $row->PORel,
                'PartNumber' => $row->PartNumber,
                'PartDescription' => $row->PartDescription,
                'TotalQtyPO' => (int) $row->TotalQtyPO,
                'TotalGR' => (int) $row->TotalGR,
                'TotalInvoiced' => (int) $row->TotalInvoiced,
                'Status' => $row->Status,
            ];
        })->values()->toArray();

        return response()->json([
            'data' => $data
        ]);
    }

    public function get_purchase_po_reguler(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $startDate = date('Y-m-d', strtotime($startDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'OrderDate',
            1 => 'POLine',
            2 => 'PONum',
            3 => 'RelQty',
            4 => 'TotalGR',
            5 => 'OutstandingQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'PONum';

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        ),

        PaymentPerPoRel AS (
            SELECT
                rd.Company,
                rd.PONum,
                rd.POLine,
                SUM(rd.OurQty) AS TotalGR,
                SUM(tr.TranAmt) AS TotalPaid
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            INNER JOIN app.Erp.APInvHed ih 
                ON id.Company = ih.Company 
                AND id.InvoiceNum = ih.InvoiceNum
            INNER JOIN app.Erp.APTran tr 
                ON ih.Company = tr.Company 
                AND ih.InvoiceNum = tr.InvoiceNum 
                AND tr.TranType = 'Pay'
            WHERE rd.PONum = 3177
            GROUP BY rd.Company, rd.PONum, rd.POLine
        )

        SELECT
            ph.PONum,
            pd.POLine,
            pr.PORelNum,
            ph.OrderDate,
            pd.PartNum,
            pd.LineDesc,
            pr.RelQty,
            pd.IUM,
            ip.TotalGR,
            pd.UnitCost,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            (pr.RelQty - ISNULL(ip.TotalGR, 0)) AS OutstandingQty,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'Reguler' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY $orderColumn $orderDirection
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$startDate, $toDate]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->OrderDate), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->RelQty), strtolower($search)) ||
                    str_contains(strtolower($item->TotalGR), strtolower($search)) ||
                    str_contains(strtolower($item->OutstandingQty), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'POLine' => $row->POLine,
                'PONum' => (int) $row->PONum,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) $row->TotalGR,
                'OutstandingQty' => (int) $row->OutstandingQty,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_purchase_po_reguler_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if (!$startDate || !$endDate) {
            return response()->json(['data' => []], 400);
        }

        DB::connection('sqlsrv5')->statement("USE [app]");

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'OrderDate',
            1 => 'POLine',
            2 => 'PONum',
            3 => 'RelQty',
            4 => 'TotalGR',
            5 => 'OutstandingQty',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'OrderDate';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        ),

        PaymentPerPoRel AS (
            SELECT
                rd.Company,
                rd.PONum,
                rd.POLine,
                SUM(rd.OurQty) AS TotalGR,
                SUM(tr.TranAmt) AS TotalPaid
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            INNER JOIN app.Erp.APInvHed ih 
                ON id.Company = ih.Company 
                AND id.InvoiceNum = ih.InvoiceNum
            INNER JOIN app.Erp.APTran tr 
                ON ih.Company = tr.Company 
                AND ih.InvoiceNum = tr.InvoiceNum 
                AND tr.TranType = 'Pay'
            WHERE rd.PONum = 3177
            GROUP BY rd.Company, rd.PONum, rd.POLine
        )

        SELECT
            ph.PONum,
            pd.POLine,
            pr.PORelNum,
            ph.OrderDate,
            pd.PartNum,
            pd.LineDesc,
            pr.RelQty,
            pd.IUM,
            ip.TotalGR,
            pd.UnitCost,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            (pr.RelQty - ISNULL(ip.TotalGR, 0)) AS OutstandingQty,
            ip.TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'Reguler' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY $orderColumn $orderDirection
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$startDate, $endDate]);
        $collection = collect($rawData);

        $data = $collection->map(function ($row) {
            return [
                'OrderDate' => $row->OrderDate,
                'POLine' => (int) $row->POLine,
                'PONum' => (int) $row->PONum,
                'RelQty' => (int) $row->RelQty,
                'TotalGR' => (int) ($row->TotalGR ?? 0),
                'OutstandingQty' => (int) ($row->OutstandingQty ?? 0),
            ];
        })->values();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function get_purchase_data_po_reguler_ammount(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $startDate = date('Y-m-d', strtotime($startDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        DB::connection('sqlsrv5')->statement("USE [app]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PONum',
            1 => 'POLine',
            2 => 'ReleaseCost',
            3 => 'TotalInvoiced',
            4 => 'BalanceRemaining',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'PONum';

        $subQuery = "
        WITH InvoicePerPoRel AS (
            SELECT
                id.Company,
                rd.PONum,
                rd.POLine,
                rd.PORelNum,
                SUM(rd.OurQty) AS TotalGR,
                SUM(id.DocExtCost) AS TotalInvoiced
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            GROUP BY id.Company, rd.PONum, rd.POLine, rd.PORelNum
        ),

        PaymentPerPoRel AS (
            SELECT
                rd.Company,
                rd.PONum,
                rd.POLine,
                SUM(rd.OurQty) AS TotalGR,
                SUM(tr.TranAmt) AS TotalPaid
            FROM app.Erp.RcvDtl rd
            INNER JOIN app.Erp.APInvDtl id 
                ON rd.Company = id.Company 
                AND rd.PackSlip = id.PackSlip 
                AND rd.PackLine = id.PackLine
            INNER JOIN app.Erp.APInvHed ih 
                ON id.Company = ih.Company 
                AND id.InvoiceNum = ih.InvoiceNum
            INNER JOIN app.Erp.APTran tr 
                ON ih.Company = tr.Company 
                AND ih.InvoiceNum = tr.InvoiceNum 
                AND tr.TranType = 'Pay'
            WHERE rd.PONum = 3177
            GROUP BY rd.Company, rd.PONum, rd.POLine
        )

        SELECT
            ph.PONum,
            pd.POLine,
            ph.OrderDate,
            (pr.RelQty * pd.UnitCost) AS ReleaseCost,
            ISNULL(ip.TotalInvoiced, 0) AS TotalInvoiced,
            (ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining
        FROM
            app.Erp.POHeader ph
        INNER JOIN app.Erp.PoDetail pd 
            ON ph.Company = pd.Company 
            AND ph.PONum = pd.PONum
        INNER JOIN app.Erp.PoRel pr 
            ON pd.Company = pr.Company 
            AND pd.PONum = pr.PONum 
            AND pd.POLine = pr.POLine
        LEFT JOIN InvoicePerPoRel ip 
            ON pr.Company = ip.Company 
            AND pr.PONum = ip.PONum 
            AND pr.POLine = ip.POLine 
            AND pr.PORelNum = ip.PORelNum
        WHERE
            ph.BuyerID = 'Reguler' 
            AND ph.OrderDate BETWEEN ? AND ?
        ORDER BY $orderColumn $orderDirection
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$startDate, $toDate]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->PONum), strtolower($search)) ||
                    str_contains(strtolower($item->POLine), strtolower($search)) ||
                    str_contains(strtolower($item->ReleaseCost), strtolower($search)) ||
                    str_contains(strtolower($item->TotalInvoiced), strtolower($search)) ||
                    str_contains(strtolower($item->BalanceRemaining), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => $row->POLine,
                'ReleaseCost' => (float) $row->ReleaseCost,
                'TotalInvoiced' => (float) $row->TotalInvoiced,
                'BalanceRemaining' => (float) $row->BalanceRemaining,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }


    public function get_purchase_data_po_reguler_ammount_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if (!$startDate || !$endDate) {
            return response()->json(['data' => []], 400);
        }

        DB::connection('sqlsrv5')->statement("USE [app]");

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'OrderDate',
            1 => 'POLine',
            2 => 'PONum',
            3 => 'ReleaseCost',
            4 => 'TotalInvoiced',
            5 => 'BalanceRemaining',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'OrderDate';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $invoiceSub = DB::connection('sqlsrv5')->table('app.Erp.RcvDtl as rd')
            ->selectRaw('id.Company, rd.PONum, rd.POLine, rd.PORelNum, SUM(rd.OurQty) AS TotalGR, SUM(id.DocExtCost) AS TotalInvoiced')
            ->join('app.Erp.APInvDtl as id', function ($join) {
                $join->on('rd.Company', '=', 'id.Company')
                    ->on('rd.PackSlip', '=', 'id.PackSlip')
                    ->on('rd.PackLine', '=', 'id.PackLine');
            })
            ->groupBy('id.Company', 'rd.PONum', 'rd.POLine', 'rd.PORelNum');

        $data = DB::connection('sqlsrv5')->table('app.Erp.POHeader as ph')
            ->select([
                'ph.PONum',
                'pd.POLine',
                'ph.OrderDate',
                DB::raw('(pr.RelQty * pd.UnitCost) AS ReleaseCost'),
                DB::raw('ISNULL(ip.TotalInvoiced, 0) AS TotalInvoiced'),
                DB::raw('(ISNULL((pr.RelQty * pd.UnitCost), 0) - ISNULL(ip.TotalInvoiced, 0)) AS BalanceRemaining'),
            ])
            ->join('app.Erp.PODetail as pd', function ($join) {
                $join->on('ph.Company', '=', 'pd.Company')
                    ->on('ph.PONum', '=', 'pd.PONum');
            })
            ->join('app.Erp.PORel as pr', function ($join) {
                $join->on('pd.Company', '=', 'pr.Company')
                    ->on('pd.PONum', '=', 'pr.PONum')
                    ->on('pd.POLine', '=', 'pr.POLine');
            })
            ->leftJoinSub($invoiceSub, 'ip', function ($join) {
                $join->on('pr.Company', '=', 'ip.Company')
                    ->on('pr.PONum', '=', 'ip.PONum')
                    ->on('pr.POLine', '=', 'ip.POLine')
                    ->on('pr.PORelNum', '=', 'ip.PORelNum');
            })
            ->where('ph.BuyerID', 'Reguler')
            ->whereBetween('ph.OrderDate', [$startDate, $endDate])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => (int) $row->PONum,
                'POLine' => (int) $row->POLine,
                'ReleaseCost' => (float) $row->ReleaseCost,
                'TotalInvoiced' => (float) $row->TotalInvoiced,
                'BalanceRemaining' => (float) $row->BalanceRemaining,
            ];
        });

        return response()->json([
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_project_aging()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $results = DB::connection('sqlsrv5')
            ->table('App.Erp.POHeader as poh')
            ->selectRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END AS AgingBucket,
            COUNT(*) AS TotalPO
        ")
            ->whereNotNull('poh.OrderDate')
            ->where('poh.BuyerID', 'PROJECT')
            ->groupByRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END
        ")
            ->get();

        $data_aging = [];
        $data_totalPO = [];

        foreach ($results as $row) {
            $data_aging[] = $row->AgingBucket;
            $data_totalPO[] = (int) $row->TotalPO;
        }

        return response()->json([
            'data_aging_bucket' => $data_aging,
            'data_total_po' => $data_totalPO,
        ]);
    }

    public function get_data_po_ppic_aging()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $results = DB::connection('sqlsrv5')
            ->table('App.Erp.POHeader as poh')
            ->selectRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END AS AgingBucket,
            COUNT(*) AS TotalPO
        ")
            ->whereNotNull('poh.OrderDate')
            ->where('poh.BuyerID', 'PPIC')
            ->groupByRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END
        ")
            ->get();

        $data_aging = [];
        $data_totalPO = [];

        foreach ($results as $row) {
            $data_aging[] = $row->AgingBucket;
            $data_totalPO[] = (int) $row->TotalPO;
        }

        return response()->json([
            'data_aging_bucket' => $data_aging,
            'data_total_po' => $data_totalPO,
        ]);
    }

    public function get_data_po_reguler_aging()
    {
        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $results = DB::connection('sqlsrv5')
            ->table('App.Erp.POHeader as poh')
            ->selectRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END AS AgingBucket,
            COUNT(*) AS TotalPO
        ")
            ->whereNotNull('poh.OrderDate')
            ->where('poh.BuyerID', 'REGULER')
            ->groupByRaw("
            CASE 
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) < 30 THEN '<30 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 30 AND 60 THEN '30-60 Hari'
                WHEN DATEDIFF(DAY, poh.OrderDate, GETDATE()) BETWEEN 61 AND 90 THEN '61-90 Hari'
                ELSE '>90 Hari'
            END
        ")
            ->get();

        $data_aging = [];
        $data_totalPO = [];

        foreach ($results as $row) {
            $data_aging[] = $row->AgingBucket;
            $data_totalPO[] = (int) $row->TotalPO;
        }

        return response()->json([
            'data_aging_bucket' => $data_aging,
            'data_total_po' => $data_totalPO,
        ]);
    }

    public function get_data_purchasing_req_po_status_table(Request $request)
    {
        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;
        $status = $request->input('status');

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rd.ReqLine',
            3 => 'rd.PartNum',
            4 => 'rd.LineDesc',
            5 => 'rd.OrderQty',
            6 => 'rd.VendorNum',
            7 => 'rd.POLine',
            8 => 'pod.PONum',
            9 => 'pod.LineDesc',
            10 => 'poh.BuyerID',
            11 => 'poh.OpenOrder',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->select([
                'rh.Company',
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                'rd.OrderQty',
                'rd.VendorNum',
                'rd.POLine',
                'pod.PONum',
                'pod.LineDesc as POLineDesc',
                'poh.OrderDate as PODate',
                'poh.BuyerID as POBuyer',
                'poh.OpenOrder',
                DB::raw("CASE 
                WHEN rd.POLine > 0 AND pod.PONum IS NOT NULL THEN 'Converted to PO'
                WHEN rd.POLine > 0 AND pod.PONum IS NULL THEN 'PO Line Assigned - PO Not Found'
                WHEN rh.OpenReq = 0 THEN 'Closed'
                ELSE 'Open - Pending PO'
            END as ReqStatus")
            ])
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('rd.Company', '=', 'pod.Company')
                    ->on('rd.VendorNum', '=', 'pod.VendorNum')
                    ->on('rd.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->where('rh.Company', 'SAI')
            ->whereMonth('rh.RequestDate', $month)
            ->whereYear('rh.RequestDate', $year);
        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        if (!empty($status)) {
            $data = $data->filter(function ($row) use ($status) {
                return $row->ReqStatus === $status;
            });
        }

        $recordsTotal = $data->count();

        $mappedData = $data->slice($start, $length)->map(function ($row) {
            return [
                'ReqNum' => (int) $row->ReqNum,
                'RequestDate' => $row->RequestDate,
                'ReqLine' => (int) $row->ReqLine,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'OrderQty' => number_format((float) $row->OrderQty, 2, '.', ''),
                'VendorNum' => (int) $row->VendorNum,
                'OpenOrder' => (int) $row->OpenOrder,
                'ReqStatus' => $row->ReqStatus,
            ];
        })->values();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_purchasing_req_only_po_status_table(Request $request)
    {
        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;
        $status = $request->input('status');

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rd.ReqLine',
            3 => 'rd.PartNum',
            4 => 'rd.LineDesc',
            5 => 'rd.OrderQty',
            6 => 'rd.VendorNum',
            7 => 'rd.POLine',
            8 => 'pod.PONum',
            9 => 'pod.LineDesc',
            10 => 'poh.BuyerID',
            11 => 'poh.OpenOrder',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->select([
                'rh.Company',
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                'rd.OrderQty',
                'rd.VendorNum',
                'rd.POLine',
                'pod.PONum',
                'pod.LineDesc as POLineDesc',
                'poh.OrderDate as PODate',
                'poh.BuyerID as POBuyer',
                'poh.OpenOrder',
                DB::raw("CASE 
                WHEN rd.POLine > 0 AND pod.PONum IS NOT NULL THEN 'Converted to PO'
                WHEN rd.POLine > 0 AND pod.PONum IS NULL THEN 'PO Line Assigned - PO Not Found'
                WHEN rh.OpenReq = 0 THEN 'Closed'
                ELSE 'Open - Pending PO'
            END as ReqStatus")
            ])
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('rd.Company', '=', 'pod.Company')
                    ->on('rd.VendorNum', '=', 'pod.VendorNum')
                    ->on('rd.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->where('rh.Company', 'SAI')
            ->whereMonth('rh.RequestDate', $month)
            ->whereYear('rh.RequestDate', $year);
        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        if (!empty($status)) {
            $data = $data->filter(function ($row) use ($status) {
                return $row->ReqStatus === $status;
            });
        }

        $recordsTotal = $data->count();

        $mappedData = $data->slice($start, $length)->map(function ($row) {
            return [
                'POLine' => (int) $row->POLine,
                'PONum' => (int) $row->PONum,
                'POLineDesc' => $row->POLineDesc,
                'PODate' => $row->PODate,
                'POBuyer' => $row->POBuyer,
            ];
        })->values();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }


    public function get_data_purchasing_req_under_po_table(Request $request)
    {
        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $status = $request->input('status');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rh.RequestorID',
            3 => 'rd.ReqLine',
            4 => 'rd.PartNum',
            5 => 'rd.LineDesc',
            6 => 'rd.OrderQty',
            7 => 'rd.IUM',
            8 => 'rd.VendorNum',
            9 => 'v.Name',
            10 => DB::raw("DATEDIFF(DAY, rh.RequestDate, GETDATE())"),
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.Vendor as v', function ($join) {
                $join->on('rd.Company', '=', 'v.Company')
                    ->on('rd.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', 'SAI')
            ->where('rh.OpenReq', 1)
            ->where(function ($q) {
                $q->where('rd.POLine', 0)
                    ->orWhereNull('rd.POLine');
            })
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month);

        // Filter Urgency via whereRaw
        if (!empty($status)) {
            if ($status === 'Critical - Over 30 days') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30");
            } elseif ($status === 'Warning - Over 14 days') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 AND DATEDIFF(DAY, rh.RequestDate, GETDATE()) <= 30");
            } elseif ($status === 'Normal') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) <= 14");
            }
        }

        // Total records
        $recordsTotal = $baseQuery->count();

        // Data hasil akhir
        $data = $baseQuery
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                'rd.OrderQty',
                'rd.IUM',
                'rd.VendorNum',
                'v.Name as VendorName',
                DB::raw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) as DaysOpen"),
                DB::raw("CASE 
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30 THEN 'Critical - Over 30 days'
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 THEN 'Warning - Over 14 days'
                ELSE 'Normal'
            END as Urgency"),
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        // Map hasil akhir
        $mappedData = $data->map(function ($row) {
            return [
                'ReqNum' => $row->ReqNum,
                'RequestDate' => $row->RequestDate,
                'RequestorID' => $row->RequestorID,
                'ReqLine' => (int) $row->ReqLine,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'OrderQty' => number_format((float) $row->OrderQty, 2, '.', ''),
                'IUM' => $row->IUM,
                'VendorNum' => (int) $row->VendorNum,
                'VendorName' => $row->VendorName,
                'DaysOpen' => (int) $row->DaysOpen,
                'Urgency' => $row->Urgency,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }




    public function get_data_summary_status_req($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        DB::connection('sqlsrv5')->statement("USE [app]");

        $result = DB::connection('sqlsrv5')->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->where('rh.Company', 'SAI')
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month)
            ->selectRaw("
            COUNT(DISTINCT CASE 
                WHEN rd.POLine > 0 THEN rh.ReqNum 
                ELSE NULL 
            END) AS [Converted_to_PO],
            
            COUNT(DISTINCT CASE 
                WHEN rd.POLine = 0 AND rh.OpenReq = 0 THEN rh.ReqNum 
                ELSE NULL 
            END) AS [Closed_without_PO],
            
            COUNT(DISTINCT CASE 
                WHEN rd.POLine = 0 AND rh.OpenReq = 1 THEN rh.ReqNum 
                ELSE NULL 
            END) AS [Open_Pending_PO]
        ")
            ->first();

        return response()->json([
            'closed_without_po' => (int) $result->Closed_without_PO,
            'open_pending_po' => (int) $result->Open_Pending_PO,
            'converted_to_po' => (int) $result->Converted_to_PO,
        ]);
    }

    public function get_data_req_po_pipeline($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        DB::connection('sqlsrv5')->statement("USE [app]");

        $results = DB::connection('sqlsrv5')
            ->select(DB::raw("
            SELECT 
                'Total Open Requisitions' as Metric,
                COUNT(DISTINCT rh.ReqNum) as Value
            FROM ReqHead rh
            WHERE rh.Company = 'SAI' AND rh.OpenReq = 1

            UNION ALL

            SELECT 
                'Requisitions Pending PO' as Metric,
                COUNT(DISTINCT rd.ReqNum) as Value
            FROM ReqDetail rd
                INNER JOIN ReqHead rh ON rd.Company = rh.Company AND rd.ReqNum = rh.ReqNum
            WHERE rd.Company = 'SAI' 
                AND rh.OpenReq = 1
                AND NOT EXISTS (
                    SELECT 1 FROM SugPoDtl spd 
                    WHERE spd.Company = rd.Company 
                    AND spd.ReqNum = rd.ReqNum 
                    AND spd.ReqLine = rd.ReqLine
                    AND spd.PONum > 0
                )

            UNION ALL

            SELECT 
                'Requisitions with Suggested PO' as Metric,
                COUNT(DISTINCT spd.ReqNum) as Value
            FROM SugPoDtl spd
            WHERE spd.Company = 'SAI' 
                AND spd.PONum = 0

            UNION ALL

            SELECT 
                'Requisitions Converted to PO' as Metric,
                COUNT(DISTINCT spd.ReqNum) as Value
            FROM SugPoDtl spd
            WHERE spd.Company = 'SAI' 
                AND spd.PONum > 0

            UNION ALL

            SELECT 
                'Average Days Req to PO' as Metric,
                AVG(DATEDIFF(DAY, rh.RequestDate, poh.OrderDate)) as Value
            FROM ReqHead rh
                INNER JOIN SugPoDtl spd ON rh.Company = spd.Company AND rh.ReqNum = spd.ReqNum
                INNER JOIN POHeader poh ON spd.Company = poh.Company AND spd.PONum = poh.PONum
            WHERE rh.Company = 'SAI'
                AND YEAR(poh.OrderDate) = '$year'
                AND MONTH(poh.OrderDate) = '$month'
        "));

        $data_metrics = [];
        $data_values = [];

        foreach ($results as $row) {
            $data_metrics[] = $row->Metric;
            $data_values[] = (int) $row->Value;
        }

        return response()->json([
            'data_matric' => $data_metrics,
            'data_value' => $data_values,
        ]);
    }

    public function get_data_req_under_po($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        DB::connection('sqlsrv5')->statement("USE [app]");

        $result = DB::connection('sqlsrv5')->table('ReqHead as rh')
            ->join('ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('Vendor as v', function ($join) {
                $join->on('rd.Company', '=', 'v.Company')
                    ->on('rd.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', 'SAI')
            ->where('rh.OpenReq', 1)
            ->where(function ($query) {
                $query->where('rd.POLine', 0)
                    ->orWhereNull('rd.POLine');
            })
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month)
            ->selectRaw("
            CASE 
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30 THEN 'Critical - Over 30 days'
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 THEN 'Warning - Over 14 days'
                ELSE 'Normal'
            END as Urgency,
            COUNT(*) as TotalUrgency
        ")
            ->groupByRaw("
            CASE 
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30 THEN 'Critical - Over 30 days'
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 THEN 'Warning - Over 14 days'
                ELSE 'Normal'
            END
        ")
            ->get();

        $data = [
            'data_critical' => 0,
            'data_warning' => 0,
            'data_normal' => 0,
        ];

        foreach ($result as $row) {
            if (str_contains($row->Urgency, 'Critical')) {
                $data['data_critical'] = (int) $row->TotalUrgency;
            } elseif (str_contains($row->Urgency, 'Warning')) {
                $data['data_warning'] = (int) $row->TotalUrgency;
            } else {
                $data['data_normal'] = (int) $row->TotalUrgency;
            }
        }

        return response()->json($data);
    }

    public function get_data_purchasing_tracking_req_po_table(Request $request)
    {

        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rh.RequestorID',
            3 => 'rd.ReqLine',
            4 => 'rd.PartNum',
            5 => 'rd.LineDesc',
            6 => 'rd.OrderQty',
            7 => 'poh.PONum',
            8 => 'poh.OrderDate',
            9 => 'poh.BuyerID',
            10 => 'pod.POLine',
            11 => 'pod.LineDesc',
            12 => 'por.PORelNum',
            13 => 'por.RelQty',
            14 => 'por.DueDate',
            15 => 'por.OpenRelease',
            16 => 'v.VendorID',
            17 => 'v.Name',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PORel as por', function ($join) {
                $join->on('rd.Company', '=', 'por.Company')
                    ->on('rd.ReqNum', '=', 'por.ReqNum')
                    ->on('rd.ReqLine', '=', 'por.ReqLine');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('por.Company', '=', 'pod.Company')
                    ->on('por.PONum', '=', 'pod.PONum')
                    ->on('por.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->leftJoin('erp.Vendor as v', function ($join) {
                $join->on('poh.Company', '=', 'v.Company')
                    ->on('poh.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', 'SAI')
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month)
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc as ReqDescription',
                'rd.OrderQty as ReqQty',
                'poh.PONum',
                'poh.OrderDate as PODate',
                'poh.BuyerID',
                'pod.POLine',
                'pod.LineDesc as PODescription',
                'por.PORelNum',
                'por.RelQty as PORelQty',
                'por.DueDate',
                'por.OpenRelease',
                'v.VendorID',
                'v.Name as VendorName',
                DB::raw("CASE 
                WHEN por.OpenRelease = 0 THEN 'PO Released - Closed'
                WHEN por.OpenRelease = 1 THEN 'PO Released - Open'
                WHEN poh.PONum IS NOT NULL THEN 'PO Created'
                ELSE 'Pending PO Creation'
            END as Status")
            ]);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function get_data_purchasing_matrics_po_table(Request $request)
    {
        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Year',
            1 => 'Month',
            2 => 'TotalRequisitions',
            3 => 'ConvertedToPO',
            4 => 'PendingPO',
            5 => 'ConversionRate',
            6 => 'AvgDaysToConvert',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Year';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('rd.Company', '=', 'pod.Company')
                    ->on('rd.VendorNum', '=', 'pod.VendorNum')
                    ->on('rd.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->where('rh.Company', 'SAI')
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month)
            ->selectRaw('
            YEAR(rh.RequestDate) as Year,
            MONTH(rh.RequestDate) as Month,
            COUNT(DISTINCT rh.ReqNum) as TotalRequisitions,
            COUNT(DISTINCT CASE WHEN rd.POLine > 0 THEN rh.ReqNum END) as ConvertedToPO,
            COUNT(DISTINCT CASE WHEN rd.POLine = 0 AND rh.OpenReq = 1 THEN rh.ReqNum END) as PendingPO,
            CAST(
                COUNT(DISTINCT CASE WHEN rd.POLine > 0 THEN rh.ReqNum END) * 100.0 / 
                NULLIF(COUNT(DISTINCT rh.ReqNum), 0)
            AS DECIMAL(5,2)) as ConversionRate,
            AVG(CASE 
                WHEN rd.POLine > 0 AND poh.OrderDate IS NOT NULL 
                THEN DATEDIFF(DAY, rh.RequestDate, poh.OrderDate) 
            END) as AvgDaysToConvert
        ')
            ->groupByRaw('YEAR(rh.RequestDate), MONTH(rh.RequestDate)');

        $recordsTotal = DB::connection('sqlsrv5')
            ->table(DB::raw("({$baseQuery->toSql()}) as sub"))
            ->mergeBindings($baseQuery)
            ->count();

        $data = DB::connection('sqlsrv5')
            ->table(DB::raw("({$baseQuery->toSql()}) as sub"))
            ->mergeBindings($baseQuery)
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }


    public function get_data_purchasing_suggest_po(Request $request)
    {

        $dateInput = $request->input('date');
        if (!$dateInput) {
            return response()->json(['data' => []], 400);
        }

        $date = \Carbon\Carbon::parse($dateInput);
        $month = $date->month;
        $year = $date->year;

        DB::connection('sqlsrv5')->statement("USE [app]");

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rh.RequestorID',
            3 => 'rd.ReqLine',
            4 => 'rd.PartNum',
            5 => 'rd.LineDesc',
            6 => 'rd.OrderQty',
            7 => 'spd.SugNum',
            8 => 'spd.PONum',
            9 => 'spd.POLine',
            10 => 'poh.OrderDate',
            11 => 'poh.BuyerID',
            12 => 'pod.LineDesc',
            13 => 'pod.OrderQty',
            14 => 'v.Name',
            15 => 'Status',
            16 => 'LeadTimeDays',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';

        $query = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.SugPoDtl as spd', function ($join) {
                $join->on('rd.Company', '=', 'spd.Company')
                    ->on('rd.ReqNum', '=', 'spd.ReqNum')
                    ->on('rd.ReqLine', '=', 'spd.ReqLine');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('spd.Company', '=', 'pod.Company')
                    ->on('spd.PONum', '=', 'pod.PONum')
                    ->on('spd.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->leftJoin('erp.Vendor as v', function ($join) {
                $join->on('rd.Company', '=', 'v.Company')
                    ->on('rd.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', '=', 'SAI')
            ->whereYear('rh.RequestDate', $year)
            ->whereMonth('rh.RequestDate', $month)
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                DB::raw('rd.OrderQty as ReqQty'),
                DB::raw('rd.VendorNum as ReqVendor'),
                'spd.SugNum',
                'spd.PONum',
                'spd.POLine',
                DB::raw('poh.OrderDate as PODate'),
                DB::raw('poh.BuyerID as POBuyer'),
                DB::raw('pod.LineDesc as POLineDesc'),
                DB::raw('pod.OrderQty as POQty'),
                DB::raw('v.Name as VendorName'),
                DB::raw("
                CASE 
                    WHEN spd.PONum IS NOT NULL AND spd.PONum > 0 THEN 'Converted to PO #' + CAST(spd.PONum as VARCHAR)
                    WHEN spd.SugNum IS NOT NULL THEN 'Suggested PO Created'
                    WHEN rh.OpenReq = 0 THEN 'Closed without PO'
                    ELSE 'Open - Awaiting Suggestion'
                END as Status
            "),
                DB::raw("
                CASE 
                    WHEN poh.OrderDate IS NOT NULL 
                    THEN DATEDIFF(DAY, rh.RequestDate, poh.OrderDate)
                    ELSE DATEDIFF(DAY, rh.RequestDate, GETDATE())
                END as LeadTimeDays
            ")
            ]);

        $recordsTotal = $query->count();

        $data = $query
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function get_data_purchasing_req_po_status_table_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $status = $request->input('status');
        $start = $request->input('start', 0);

        if (!$startDate || !$endDate) {
            return response()->json(['data' => []], 400);
        }

        DB::connection('sqlsrv5')->statement("USE [app]");

        $query = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('rd.Company', '=', 'pod.Company')
                    ->on('rd.VendorNum', '=', 'pod.VendorNum')
                    ->on('rd.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->where('rh.Company', 'SAI')
            ->whereBetween('rh.RequestDate', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                'rd.OrderQty',
                'rd.VendorNum',
                'rd.POLine',
                'pod.PONum',
                'pod.LineDesc as POLineDesc',
                'poh.OrderDate as PODate',
                'poh.BuyerID as POBuyer',
                'poh.OpenOrder',
                DB::raw("CASE 
                WHEN rd.POLine > 0 AND pod.PONum IS NOT NULL THEN 'Converted to PO'
                WHEN rd.POLine > 0 AND pod.PONum IS NULL THEN 'PO Line Assigned - PO Not Found'
                WHEN rh.OpenReq = 0 THEN 'Closed'
                ELSE 'Open - Pending PO'
            END as ReqStatus")
            ])
            ->orderByDesc('rh.ReqNum')
            ->orderBy('rd.ReqLine')
            ->get();

        $data = $query->map(function ($row) {
            return [
                'ReqNum' => $row->ReqNum ? (int) $row->ReqNum : null,
                'RequestDate' => $row->RequestDate,
                'ReqLine' => $row->ReqLine ? (int) $row->ReqLine : null,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'OrderQty' => $row->OrderQty !== null ? number_format($row->OrderQty, 2, '.', '') : '0.00',
                'VendorNum' => $row->VendorNum ? (int) $row->VendorNum : null,
                'POLine' => $row->POLine ? (int) $row->POLine : null,
                'PONum' => $row->PONum ? (int) $row->PONum : null,
                'POLineDesc' => $row->POLineDesc,
                'PODate' => $row->PODate,
                'POBuyer' => $row->POBuyer,
                'OpenOrder' => $row->OpenOrder !== null ? (int) $row->OpenOrder : null,
                'ReqStatus' => $row->ReqStatus,
            ];
        });

        if (!empty($status)) {
            $data = $data->filter(function ($row) use ($status) {
                return isset($row['ReqStatus']) && $row['ReqStatus'] === $status;
            });
        }

        $recordsTotal = $data->count();

        $mappedData = $data->slice($start)->values();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_purchasing_req_under_po_table_export(Request $request)
    {

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $status = $request->input('status');

        DB::connection('sqlsrv5')->statement("USE [app]");

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rh.RequestorID',
            3 => 'rd.ReqLine',
            4 => 'rd.PartNum',
            5 => 'rd.LineDesc',
            6 => 'rd.OrderQty',
            7 => 'rd.IUM',
            8 => 'rd.VendorNum',
            9 => 'v.Name',
            10 => DB::raw("DATEDIFF(DAY, rh.RequestDate, GETDATE())"),
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.Vendor as v', function ($join) {
                $join->on('rd.Company', '=', 'v.Company')
                    ->on('rd.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', 'SAI')
            ->where('rh.OpenReq', 1)
            ->where(function ($q) {
                $q->where('rd.POLine', 0)
                    ->orWhereNull('rd.POLine');
            })
            ->whereBetween('rh.RequestDate', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if (!empty($status)) {
            if ($status === 'Critical - Over 30 days') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30");
            } elseif ($status === 'Warning - Over 14 days') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 AND DATEDIFF(DAY, rh.RequestDate, GETDATE()) <= 30");
            } elseif ($status === 'Normal') {
                $baseQuery->whereRaw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) <= 14");
            }
        }

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc',
                'rd.OrderQty',
                'rd.IUM',
                'rd.VendorNum',
                'v.Name as VendorName',
                DB::raw("DATEDIFF(DAY, rh.RequestDate, GETDATE()) as DaysOpen"),
                DB::raw("CASE 
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 30 THEN 'Critical - Over 30 days'
                WHEN DATEDIFF(DAY, rh.RequestDate, GETDATE()) > 14 THEN 'Warning - Over 14 days'
                ELSE 'Normal'
            END as Urgency"),
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'ReqNum' => $row->ReqNum,
                'RequestDate' => $row->RequestDate,
                'RequestorID' => $row->RequestorID,
                'ReqLine' => (int) $row->ReqLine,
                'PartNum' => $row->PartNum,
                'LineDesc' => $row->LineDesc,
                'OrderQty' => number_format((float) $row->OrderQty, 2, '.', ''),
                'IUM' => $row->IUM,
                'VendorNum' => (int) $row->VendorNum,
                'VendorName' => $row->VendorName,
                'DaysOpen' => (int) $row->DaysOpen,
                'Urgency' => $row->Urgency,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }
    public function get_data_purchasing_tracking_req_po_table_export(Request $request)
    {

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        DB::connection('sqlsrv5')->statement("USE [app]");

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'rh.ReqNum',
            1 => 'rh.RequestDate',
            2 => 'rh.RequestorID',
            3 => 'rd.ReqLine',
            4 => 'rd.PartNum',
            5 => 'rd.LineDesc',
            6 => 'rd.OrderQty',
            7 => 'poh.PONum',
            8 => 'poh.OrderDate',
            9 => 'poh.BuyerID',
            10 => 'pod.POLine',
            11 => 'pod.LineDesc',
            12 => 'por.PORelNum',
            13 => 'por.RelQty',
            14 => 'por.DueDate',
            15 => 'por.OpenRelease',
            16 => 'v.VendorID',
            17 => 'v.Name',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'rh.ReqNum';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('erp.ReqHead as rh')
            ->join('erp.ReqDetail as rd', function ($join) {
                $join->on('rh.Company', '=', 'rd.Company')
                    ->on('rh.ReqNum', '=', 'rd.ReqNum');
            })
            ->leftJoin('erp.PORel as por', function ($join) {
                $join->on('rd.Company', '=', 'por.Company')
                    ->on('rd.ReqNum', '=', 'por.ReqNum')
                    ->on('rd.ReqLine', '=', 'por.ReqLine');
            })
            ->leftJoin('erp.PODetail as pod', function ($join) {
                $join->on('por.Company', '=', 'pod.Company')
                    ->on('por.PONum', '=', 'pod.PONum')
                    ->on('por.POLine', '=', 'pod.POLine');
            })
            ->leftJoin('erp.POHeader as poh', function ($join) {
                $join->on('pod.Company', '=', 'poh.Company')
                    ->on('pod.PONum', '=', 'poh.PONum');
            })
            ->leftJoin('erp.Vendor as v', function ($join) {
                $join->on('poh.Company', '=', 'v.Company')
                    ->on('poh.VendorNum', '=', 'v.VendorNum');
            })
            ->where('rh.Company', 'SAI')
            ->whereBetween('rh.RequestDate', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->select([
                'rh.ReqNum',
                'rh.RequestDate',
                'rh.RequestorID',
                'rd.ReqLine',
                'rd.PartNum',
                'rd.LineDesc as ReqDescription',
                'rd.OrderQty as ReqQty',
                'poh.PONum',
                'poh.OrderDate as PODate',
                'poh.BuyerID',
                'pod.POLine',
                'pod.LineDesc as PODescription',
                'por.PORelNum',
                'por.RelQty as PORelQty',
                'por.DueDate',
                'por.OpenRelease',
                'v.VendorID',
                'v.Name as VendorName',
                DB::raw("CASE 
                WHEN por.OpenRelease = 0 THEN 'PO Released - Closed'
                WHEN por.OpenRelease = 1 THEN 'PO Released - Open'
                WHEN poh.PONum IS NOT NULL THEN 'PO Created'
                ELSE 'Pending PO Creation'
            END as Status")
            ]);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function get_data_po_ppic_approval($years)
    {
        [$year, $month] = explode('-', $years);

        $summary = DB::connection('sqlsrv5')
            ->table(DB::raw('[sai_db].dbo.f_po_approval_status() AS t'))
            ->selectRaw("
            SUM(CASE WHEN status_checker = 'APPROVED' THEN 1 ELSE 0 END) AS data_check,
            SUM(CASE WHEN status_approver = 'APPROVED' THEN 1 ELSE 0 END) AS data_approve,
            SUM(CASE WHEN status_legalizer = 'APPROVED' THEN 1 ELSE 0 END) AS data_legal
        ")
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', 'PPIC')
            ->first();

        $result = [
            'data_check' => (int) ($summary->data_check ?? 0),
            'data_approve' => (int) ($summary->data_approve ?? 0),
            'data_legal' => (int) ($summary->data_legal ?? 0),
        ];

        return response()->json($result);
    }

    public function get_data_po_reguler_approval($years)
    {
        [$year, $month] = explode('-', $years);

        $summary = DB::connection('sqlsrv5')
            ->table(DB::raw('[sai_db].dbo.f_po_approval_status() AS t'))
            ->selectRaw("
            SUM(CASE WHEN status_checker = 'APPROVED' THEN 1 ELSE 0 END) AS data_check,
            SUM(CASE WHEN status_approver = 'APPROVED' THEN 1 ELSE 0 END) AS data_approve,
            SUM(CASE WHEN status_legalizer = 'APPROVED' THEN 1 ELSE 0 END) AS data_legal
        ")
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', 'Reguler')
            ->first();

        $result = [
            'data_check' => (int) ($summary->data_check ?? 0),
            'data_approve' => (int) ($summary->data_approve ?? 0),
            'data_legal' => (int) ($summary->data_legal ?? 0),
        ];

        return response()->json($result);
    }

    public function get_data_po_project_approval($years)
    {
        [$year, $month] = explode('-', $years);

        $summary = DB::connection('sqlsrv5')
            ->table(DB::raw('[sai_db].dbo.f_po_approval_status() AS t'))
            ->selectRaw("
            SUM(CASE WHEN status_checker = 'APPROVED' THEN 1 ELSE 0 END) AS data_check,
            SUM(CASE WHEN status_approver = 'APPROVED' THEN 1 ELSE 0 END) AS data_approve,
            SUM(CASE WHEN status_legalizer = 'APPROVED' THEN 1 ELSE 0 END) AS data_legal
        ")
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', 'Project')
            ->first();

        $result = [
            'data_check' => (int) ($summary->data_check ?? 0),
            'data_approve' => (int) ($summary->data_approve ?? 0),
            'data_legal' => (int) ($summary->data_legal ?? 0),
        ];

        return response()->json($result);
    }

    public function get_data_po_approval_ppic(Request $request)
    {
        $date = $request->input('date');
        $buyerId = $request->input('buyer_id', 'PPIC');
        [$year, $month] = explode('-', $date);

        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));
        $draw = intval($request->input('draw'));

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_approval_reguler(Request $request)
    {
        $date = $request->input('date');
        $buyerId = $request->input('buyer_id', 'Reguler');
        [$year, $month] = explode('-', $date);

        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));
        $draw = intval($request->input('draw'));

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_approval_project(Request $request)
    {
        $date = $request->input('date');
        $buyerId = $request->input('buyer_id', 'Project');
        [$year, $month] = explode('-', $date);

        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));
        $draw = intval($request->input('draw'));

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->whereYear('orderdate', $year)
            ->whereMonth('orderdate', $month)
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_approval_project_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $buyerId = $request->input('buyer_id', 'Project');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('orderdate', [$startDate, $endDate]);
            })
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_approval_ppic_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $buyerId = $request->input('buyer_id', 'PPIC');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('orderdate', [$startDate, $endDate]);
            })
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }

    public function get_data_po_approval_reguler_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $buyerId = $request->input('buyer_id', 'REGULER');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'po_num',
            1 => 'docnum',
            2 => 'orderdate',
            3 => 'amount',
            4 => 'buyer_id',
            5 => 'status_checker',
            6 => 'status_approver',
            7 => 'status_legalizer',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'po_num';
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? $orderDirection : 'asc';

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $baseQuery = DB::connection('sqlsrv5')
            ->table(DB::raw('f_po_approval_status()'))
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('orderdate', [$startDate, $endDate]);
            })
            ->where('buyer_id', $buyerId);

        $recordsTotal = $baseQuery->count();

        $data = $baseQuery
            ->select([
                'po_num as PONum',
                'docnum as DocNum',
                'orderdate as OrderDate',
                'amount as Amount',
                'buyer_id as BuyerID',
                'status_checker as StatusChecker',
                'status_approver as StatusApprover',
                'status_legalizer as StatusLegalizer',
            ])
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $mappedData = $data->map(function ($row) {
            return [
                'PONum' => $row->PONum,
                'DocNum' => $row->DocNum,
                'OrderDate' => $row->OrderDate,
                'Amount' => number_format((float) $row->Amount, 2, '.', ''),
                'BuyerID' => $row->BuyerID,
                'StatusChecker' => $row->StatusChecker,
                'StatusApprover' => $row->StatusApprover,
                'StatusLegalizer' => $row->StatusLegalizer,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $mappedData,
        ]);
    }
}
