<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use DateTime;
use DataTables;

class FinanceController extends Controller
{

    public function get_profit_invoice_yearly($year)
    {
        $str_year = explode('~', $year);
        $start = (int) $str_year[0];
        $end = $str_year[1] + 1;
        $results = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit'),
            )
            ->groupBy('Years')
            ->whereBetween('Years', [$start, $end])
            ->orderBy('Years', 'asc')->get();
        $data_year = '';
        $data_chart_sales = '';
        $data_chart_expenses = '';
        $data_chart_cost = '';
        $data_chart_profit = '';
        $no_sales = 0;
        foreach ($results as $a) {
            $data_year .= (int) $a->Years . ($no_sales == 5 ? '' : ', ');
            $data_chart_sales .= (int) $a->Sales . ($no_sales == 5 ? '' : ', ');
            $data_chart_expenses .= (int) $a->Expenses . ($no_sales == 5 ? '' : ', ');
            $data_chart_cost .= (int) $a->Cost . ($no_sales == 5 ? '' : ', ');
            $data_chart_profit .= (int) $a->Profit . ($no_sales == 5 ? '' : ', ');
            $no_sales++;
        }

        $responseData = [
            'data_year' => $data_year,
            'data_chart_sales' => $data_chart_sales,
            'data_chart_expenses' => $data_chart_expenses,
            'data_chart_cost' => $data_chart_cost,
            'data_chart_profit' => $data_chart_profit,

        ];
        return response()->json($responseData);
    }

    public function get_profitability_yearly($range)
    {
        [$year, $month] = explode('-', $range);
        $start = (int) $year;
        $end = (int) $month;

        $results = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->groupBy('Years')
            ->whereBetween('Years', [$start, $end])
            ->orderBy('Years', 'asc')
            ->get();

        $data_year = '';
        $data_chart_profit = '';
        $data_chart_loss = '';
        $profitability_no = 0;

        foreach ($results as $a) {
            $data_year .= (int) $a->Years . ($profitability_no == 5 ? '' : ', ');
            $data_chart_profit .= (int) $a->Profit . ($profitability_no == 5 ? '' : ', ');
            $data_chart_loss .= (int) $a->Loss . ($profitability_no == 5 ? '' : ', ');
            $profitability_no++;
        }

        $responseData = [
            'data_year' => $data_year,
            'data_chart_profit' => $data_chart_profit,
            'data_chart_loss' => $data_chart_loss,
        ];
        return response()->json($responseData);
    }
    public function get_profitability_monthly($range)
    {
        [$year] = explode('-', $range);
        $year = (int) $year;

        $results = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $year)
            ->groupBy('Years', 'Months')
            ->orderBy('Years', 'asc')
            ->orderBy('Months', 'asc')
            ->get();

        $monthNames = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        $months = [];
        $profits = [];
        $losses = [];

        foreach ($results as $a) {
            $months[] = $monthNames[(int) $a->Months] ?? $a->Months;
            $profits[] = (int) $a->Profit;
            $losses[] = (int) $a->Loss;
        }

        $responseData = [
            'data_months' => implode(', ', $months),
            'data_chart_profit' => implode(', ', $profits),
            'data_chart_loss' => implode(', ', $losses),
        ];

        return response()->json($responseData);
    }


    public function get_profit_by_year_table(Request $request)
    {
        $startYear = $request->input('start');
        $endYear = $request->input('end');

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->when($startYear && $endYear, function ($q) use ($startYear, $endYear) {
                $q->whereBetween('Years', [$startYear, $endYear]);
            })
            ->groupBy('Years');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }
    public function get_profitability_by_year_table(Request $request)
    {
        $startYear = $request->input('start');
        $endYear = $request->input('end');

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->when($startYear && $endYear, function ($q) use ($startYear, $endYear) {
                $q->whereBetween('Years', [$startYear, $endYear]);
            })
            ->groupBy('Years');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profit_by_year_table_export(Request $request)
    {
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->groupBy('Years');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $columns = [
            'Years',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }
    public function get_profit_by_month_table(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)

            ->groupBy('Years', 'Months');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Months';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    public function get_profitability_by_month_table(Request $request)
    {
        $date = $request->input('date');
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $date)
            ->groupBy('Years', 'Months');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Months';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profit_by_month_table_export(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)

            ->groupBy('Years', 'Months');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Months';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profit_invoice_cust_yearly_table(Request $request)
    {
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $date = $request->input('date');
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)

            ->groupBy('Years', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM( Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'CustNum',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profitability_invoice_cust_yearly_table(Request $request)
    {
        $date = $request->input('date');
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'CustNum',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $date)
            ->groupBy('Years', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'CustNum',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profit_invoice_cust_yearly_table_export(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)
            ->groupBy('Years', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'CustNum',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profit_invoice_cust_month_table_export(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->groupBy('Years', 'Months', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'CustNum',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profit_invoice_cust_month_table(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->groupBy('Years', 'Months', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Sales)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Cost)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Expenses)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'CustNum',
            'Sales',
            'Cost',
            'Expenses',
            'Profit',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Months';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function profitability_invoice_cust_month_table(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'CustNum',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->groupBy('Years', 'Months', 'CustNum');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('CustNum', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'CustNum',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    public function get_profit_model_yearly($year)
    {
        $year = (int) ($year ?? date('Y'));
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'ModelName',
                    DB::raw('SUM(Profit) as Profit'),
                    DB::raw('SUM(Sales) as Sales'),
                    DB::raw('SUM(CostwVariance) as Cost'),
                    DB::raw('SUM(Expenses) as Expenses'),
                )
                ->where('Years', $year)
                ->where('Months', '<=', (int)$bulan)
                ->where('ModelName', '<>', '')
                ->groupBy('Years', 'ModelName')
                ->orderByDesc('Sales')
                ->limit(10)
                ->get();

            $response = [
                'data_chart_sales' => [],
                'data_chart_cost' => [],
                'data_chart_expenses' => [],
                'data_chart_profit' => [],
                'data_chart_model' => []
            ];

            foreach ($results as $item) {
                $response['data_chart_sales'][] = (int) ($item->Sales ?? 0);
                $response['data_chart_cost'][] = (int) ($item->Cost ?? 0);
                $response['data_chart_expenses'][] = (int) ($item->Expenses ?? 0);
                $response['data_chart_profit'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_model'][] = $item->ModelName ?? '';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profitability_model_yearly($year)
    {
        $year = (int) ($year ?? date('Y'));

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'ModelName',
                    DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                    DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
                )
                ->where('Years', $year)
                ->where('ModelName', '<>', '')
                ->groupBy('Years', 'ModelName')
                ->orderByDesc('Profit')
                ->limit(10)
                ->get();

            $response = [
                'data_chart_profit' => [],
                'data_chart_loss' => [],
                'data_chart_model' => []
            ];

            foreach ($results as $item) {
                $response['data_chart_profit'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_loss'][] = (int) ($item->Loss ?? 0);
                $response['data_chart_model'][] = $item->ModelName ?? '';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profit_category_year($year)
    {
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $year = (int) ($year ?? date('Y'));

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Category',
                    DB::raw('SUM(Sales) AS TotalSales'),
                    DB::raw('SUM(CostwVariance) AS TotalCost'),
                    DB::raw('SUM(Expenses) AS TotalExpenses'),
                    DB::raw('SUM(Profit) AS TotalProfit')
                )
                ->where('Years', $year)
                ->where('Months', '<=', (int)$bulan)
                ->where('Category', '<>', '')
                ->where(function ($query) {
                    $query->whereNotNull('Category')
                        ->orWhere('Category', '');
                })
                ->whereIn('CustNum', ['HPM', 'MMKI', 'MMKSI', 'MKM', 'SIM', 'SIS', 'IAMI', 'TMMIN', 'UDAMI'])
                ->groupBy('Years', 'Category')
                ->orderBy('Years')
                ->orderBy('TotalSales', 'desc')
                ->get();

            $response = [
                'data_chart_sales' => [],
                'data_chart_cost' => [],
                'data_chart_expenses' => [],
                'data_chart_profit' => [],
                'data_chart_category' => [],
            ];

            foreach ($results as $item) {
                $response['data_chart_sales'][] = (int) ($item->TotalSales ?? 0);
                $response['data_chart_cost'][] = (int) ($item->TotalCost ?? 0);
                $response['data_chart_expenses'][] = (int) ($item->TotalExpenses ?? 0);
                $response['data_chart_profit'][] = (int) ($item->TotalProfit ?? 0);
                $response['data_chart_category'][] = ($item->Category ?? 0);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function get_profitability_category_year($year)
    {
        $year = (int) ($year ?? date('Y'));

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Category',
                    DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                    DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
                )
                ->where('Years', $year)
                ->where(function ($query) {
                    $query->whereNotNull('Category')
                        ->orWhere('Category', '');
                })
                ->whereIn('CustNum', ['HPM', 'MMKI', 'MMKSI', 'MKM', 'SIM', 'SIS', 'IAMI', 'TMMIN', 'UDAMI'])
                ->groupBy('Years', 'Category')
                ->orderBy('Years')
                ->orderBy('Profit', 'desc')
                ->get();

            $response = [
                'data_chart_profit' => [],
                'data_chart_loss' => [],
                'data_chart_category' => [],
            ];

            foreach ($results as $item) {
                $response['data_chart_profit'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_loss'][] = (int) ($item->Loss ?? 0);
                $response['data_chart_category'][] = ($item->Category ?? 0);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profit_category_month($year)
    {
        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Months',
                    'Category',
                    DB::raw('SUM(Sales) AS TotalSales'),
                    DB::raw('SUM(CostwVariance) AS TotalCost'),
                    DB::raw('SUM(Expenses) AS TotalExpenses'),
                    DB::raw('SUM(Profit) AS TotalProfit')
                )
                ->where('Years', $year)
                ->where('Months', $month)
                ->where(function ($query) {
                    $query->whereNotNull('Category')
                        ->orWhere('Category', '');
                })
                ->whereIn('CustNum', ['HPM', 'MMKI', 'MMKSI', 'MKM', 'SIM', 'SIS', 'IAMI', 'TMMIN', 'UDAMI'])
                ->groupBy('Months', 'Years', 'Category')
                ->orderBy('TotalSales', 'desc')
                ->get();

            $response = [
                'data_chart_sales_month' => [],
                'data_chart_cost_month' => [],
                'data_chart_expenses_month' => [],
                'data_chart_profit_month' => [],
                'data_chart_category_month' => [],
            ];

            foreach ($results as $item) {
                $response['data_chart_sales_month'][] = (int) ($item->TotalSales ?? 0);
                $response['data_chart_cost_month'][] = (int) ($item->TotalCost ?? 0);
                $response['data_chart_expenses_month'][] = (int) ($item->TotalExpenses ?? 0);
                $response['data_chart_profit_month'][] = (int) ($item->TotalProfit ?? 0);
                $response['data_chart_category_month'][] = ($item->Category ?? 0);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profitability_category_month($year)
    {
        [$year, $month] = explode('-', $year);
        $year = (int) $year;
        $month = (int) $month;

        try {
            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Months',
                    'Category',
                    DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                    DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
                )
                ->where('Years', $year)
                ->where('Months', $month)
                ->where(function ($query) {
                    $query->whereNotNull('Category')
                        ->orWhere('Category', '');
                })
                ->whereIn('CustNum', ['HPM', 'MMKI', 'MMKSI', 'MKM', 'SIM', 'SIS', 'IAMI', 'TMMIN', 'UDAMI'])
                ->groupBy('Months', 'Years', 'Category')
                ->orderBy('Profit', 'desc')
                ->get();

            $response = [
                'data_chart_profit_month' => [],
                'data_chart_loss_month' => [],
                'data_chart_category_month' => [],
            ];

            foreach ($results as $item) {
                $response['data_chart_profit_month'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_loss_month'][] = (int) ($item->Loss ?? 0);
                $response['data_chart_category_month'][] = ($item->Category ?? 0);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function get_profit_model_monthly($year)
    {
        try {
            [$year, $month] = explode('-', $year);
            $year = (int) $year;
            $month = (int) $month;

            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Months',
                    'ModelName',
                    DB::raw('SUM(Profit) as Profit'),
                    DB::raw('SUM(Sales) as Sales'),
                    DB::raw('SUM(CostwVariance) as Cost'),
                    DB::raw('SUM(Expenses) as Expenses'),
                )
                ->where('Years', $year)
                ->where('Months', $month)
                ->where('ModelName', '<>', '')
                ->groupBy('Years', 'Months', 'ModelName')
                ->orderByDesc('Sales')
                ->limit(10)
                ->get();

            $response = [
                'data_chart_sales' => [],
                'data_chart_cost' => [],
                'data_chart_expenses' => [],
                'data_chart_profit' => [],
                'data_chart_model' => []
            ];

            foreach ($results as $item) {
                $response['data_chart_sales'][] = (int) ($item->Sales ?? 0);
                $response['data_chart_cost'][] = (int) ($item->Cost ?? 0);
                $response['data_chart_expenses'][] = (int) ($item->Expenses ?? 0);
                $response['data_chart_profit'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_model'][] = $item->ModelName ?? '';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profitability_model_monthly($year)
    {
        try {
            [$year, $month] = explode('-', $year);
            $year = (int) $year;
            $month = (int) $month;

            $results = DB::connection('sqlsrv5')
                ->table('V_ProfitCostVariance')
                ->select(
                    'Years',
                    'Months',
                    'ModelName',
                    DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                    DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
                )
                ->where('Years', $year)
                ->where('Months', $month)
                ->where('ModelName', '<>', '')
                ->groupBy('Years', 'Months', 'ModelName')
                ->orderByDesc('Profit')
                ->limit(10)
                ->get();

            $response = [
                'data_chart_profit' => [],
                'data_chart_loss' => [],
                'data_chart_model' => []
            ];

            foreach ($results as $item) {
                $response['data_chart_profit'][] = (int) ($item->Profit ?? 0);
                $response['data_chart_loss'][] = (int) ($item->Loss ?? 0);
                $response['data_chart_model'][] = $item->ModelName ?? '';
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get_profit_model_by_yearly_table(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'ModelName',
            2 => 'Sales',
            3 => 'Cost',
            4 => 'Expenses',
            5 => 'Profit',
            6 => 'Profit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $baseQuery = DB::connection('sqlsrv5')->table('V_ProfitCostVariance')
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)

            ->where('ModelName', '!=', '')
            ->select(
                'Years',
                'ModelName',
                DB::raw('SUM(Profit) as Profit'),
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses')
            )->GroupBy('Years', 'ModelName');

        $totalData = $baseQuery->get()->count();
        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Profit', 'LIKE', "%{$search}%")
                    ->orWhere('Sales', 'LIKE', "%{$search}%")
                    ->orWhere('Expenses', 'LIKE', "%{$search}%")
                    ->orWhere('Cost', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $baseQuery->get()->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {

                $profit = (float) $row->Profit;

                $loss = $profit < 0 ? 'Loss' : 'Profit';

                return [
                    'Years' => (int) $row->Years,
                    'ModelName' => $row->ModelName,
                    'Cost' => (float) $row->Cost,
                    'Expenses' => (float) $row->Expenses,
                    'Sales' => (float) $row->Sales,
                    'Profit' => (float) $row->Profit,
                    'Loss' => $loss,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profitability_model_by_yearly_table(Request $request)
    {
        $date = $request->input('date');


        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'ModelName',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $date)
            ->where('ModelName', '!=', '')
            ->groupBy('Years', 'ModelName');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'ModelName',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profitability_model_by_monthly_table(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'ModelName',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('ModelName', '!=', '')
            ->groupBy('Years', 'Months', 'ModelName');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'ModelName',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profitability_category_by_yearly_table(Request $request)
    {
        $date = $request->input('date');

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Category',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $date)
            ->where('Category', '!=', '')
            ->groupBy('Years', 'Category');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Category',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profitability_category_by_month_table(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('Category', '!=', '')
            ->groupBy('Years', 'Months', 'Category');

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->having(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Profit)'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('SUM(Loss)'), 'LIKE', "%{$search}%");
            });
        }

        $filteredQuery = clone $query;
        $filteredData = $filteredQuery->get()->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'Category',
            'Profit',
            'Loss',
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $orderColumn = $columns[$orderColumnIndex] ?? 'Profit';

        $query->orderBy($orderColumn, $orderDirection);

        $data = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_profit_category_by_yearly_table(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Category',
            2 => 'TotalSales',
            3 => 'TotalCost',
            4 => 'TotalExpenses',
            5 => 'TotalProfit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'TotalSales';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->where('Months', '<=', (int)$bulan)
            ->select(
                'Years',
                'Category',
                DB::raw('SUM(Sales) as TotalSales'),
                DB::raw('SUM(CostwVariance) as TotalCost'),
                DB::raw('SUM(Expenses) as TotalExpenses'),
                DB::raw('SUM(Profit) as TotalProfit')
            )
            ->where('Years', $date)
            ->where(function ($q) {
                $q->whereNotNull('Category')->where('Category', '!=', '');
            })
            ->whereIn('CustNum', [
                'HPM',
                'MMKI',
                'MMKSI',
                'MKM',
                'SIM',
                'SIS',
                'IAMI',
                'TMMIN',
                'UDAMI'
            ])
            ->groupBy('Years', 'Category');

        if (!empty($search)) {
            $baseQuery->having('Category', 'LIKE', "%{$search}%");
        }

        $totalData = $baseQuery->count();
        $totalFiltered = $totalData;

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {
                $TotalProfit = (float) $row->TotalProfit;
                $cost = (float) $row->TotalCost;
                $sales = (float) $row->TotalSales;
                $TotalExpenses = (float) $row->TotalExpenses;

                $status = 'Break Even';
                if ($TotalProfit > ($cost + $TotalExpenses))
                    $status = 'Profit';
                elseif ($TotalProfit < ($cost + $TotalExpenses))
                    $status = 'Loss';

                return [
                    'Years' => (int) $row->Years,
                    'Category' => $row->Category,
                    'Sales' => $sales,
                    'Cost' => $cost,
                    'TotalExpenses' => $row->TotalExpenses,
                    'Profit' => (float) $row->TotalProfit,
                    'Status' => $status,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profit_category_by_monthly_table(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'TotalSales',
            4 => 'TotalCost',
            5 => 'TotalExpenses',
            6 => 'TotalProfit',
            7 => 'TotalProfit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'TotalSales';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw('SUM(Sales) as TotalSales'),
                DB::raw('SUM(CostwVariance) as TotalCost'),
                DB::raw('SUM(Expenses) as TotalExpenses'),
                DB::raw('SUM(Profit) as TotalProfit')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where(function ($q) {
                $q->whereNotNull('Category')->where('Category', '!=', '');
            })
            ->whereIn('CustNum', [
                'HPM',
                'MMKI',
                'MMKSI',
                'MKM',
                'SIM',
                'SIS',
                'IAMI',
                'TMMIN',
                'UDAMI'
            ])
            ->groupBy('Years', 'Months', 'Category');

        if (!empty($search)) {
            $baseQuery->having('Category', 'LIKE', "%{$search}%");
        }

        $totalData = $baseQuery->count();
        $totalFiltered = $totalData;

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {
                $TotalProfit = (float) $row->TotalProfit;
                $cost = (float) $row->TotalCost;
                $sales = (float) $row->TotalSales;
                $TotalExpenses = (float) $row->TotalExpenses;

                $status = 'Break Even';
                if ($TotalProfit > ($cost + $TotalExpenses))
                    $status = 'Profit';
                elseif ($TotalProfit < ($cost + $TotalExpenses))
                    $status = 'Loss';

                return [
                    'Years' => (int) $row->Years,
                    'Months' => (int) $row->Months,
                    'Category' => $row->Category,
                    'Sales' => $sales,
                    'Cost' => $cost,
                    'Expenses' => $row->TotalExpenses,
                    'Profit' => (float) $row->TotalProfit,
                    'Status' => $status,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profit_model_by_yearly_export(Request $request)
    {
        $date = $request->input('date');
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'ModelName',
            2 => 'Profit',
            3 => 'Sales',
            4 => 'Cost',
            5 => 'Expenses',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $baseQuery = DB::connection('sqlsrv5')->table('V_ProfitCostVariance')
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)
            ->where('ModelName', '!=', '')
            ->select(
                'Years',
                'ModelName as ModelName',
                DB::raw('SUM(Profit) as Profit'),
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses')
            )
            ->groupBy('Years', 'ModelName');

        $totalData = $baseQuery->get()->count();

        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Profit', 'LIKE', "%{$search}%")
                    ->orWhere('Sales', 'LIKE', "%{$search}%")
                    ->orWhere('Cost', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $baseQuery->get()->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->Sales;
                $cost = (float) $row->Cost;
                $profit = (float) $row->Profit;

                $loss = $cost > $sales ? 'Loss' : 'Profit';

                $formatRupiah = function ($num) {
                    return 'Rp ' . number_format($num, 0, ',', '.');
                };

                return [
                    'Years' => (int) $row->Years,
                    'Model' => $row->ModelName,
                    'Profit' => $formatRupiah($profit),
                    'Sales' => $formatRupiah($sales),
                    'Cost' => $formatRupiah($cost),
                    'Expenses' => $row->Expenses,
                    'Loss' => $loss,
                ];
            });



        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profit_model_by_monthly_export(Request $request)
    {
        $date = $request->input('date');
        [$year, $month] = explode('-', $date);
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'ModelName',
            3 => 'Profit',
            4 => 'Sales',
            5 => 'Cost',
            6 => 'Expenses',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $baseQuery = DB::connection('sqlsrv5')->table('V_ProfitCostVariance')
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('ModelName', '!=', '')
            ->select(
                'Years',
                'Months',
                'ModelName as ModelName',
                DB::raw('SUM(Profit) as Profit'),
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses')
            )
            ->groupBy('Years', 'Months', 'ModelName');

        $totalData = $baseQuery->get()->count();

        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Profit', 'LIKE', "%{$search}%")
                    ->orWhere('Sales', 'LIKE', "%{$search}%")
                    ->orWhere('Cost', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $baseQuery->get()->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->Sales;
                $cost = (float) $row->Cost;
                $profit = (float) $row->Profit;

                $loss = $cost > $sales ? 'Loss' : 'Profit';

                $formatRupiah = function ($num) {
                    return 'Rp ' . number_format($num, 0, ',', '.');
                };

                return [
                    'Years' => (int) $row->Years,
                    'Months' => (int) $row->Months,
                    'Model' => $row->ModelName,
                    'Profit' => $formatRupiah($profit),
                    'Sales' => $formatRupiah($sales),
                    'Cost' => $formatRupiah($cost),
                    'Expenses' => $row->Expenses,
                    'Loss' => $loss,
                ];
            });



        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
    public function get_profit_category_by_yearly_table_export(Request $request)
    {
        $date = $request->input('date'); // Tahun
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Category',
            2 => 'TotalSales',
            3 => 'TotalCost',
            4 => 'TotalExpenses',
            5 => 'TotalProfit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'TotalSales';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Category',
                DB::raw('SUM(Sales) as TotalSales'),
                DB::raw('SUM(CostwVariance) as TotalCost'),
                DB::raw('SUM(Expenses) as TotalExpenses'),
                DB::raw('SUM(Profit) as TotalProfit')
            )
            ->where('Years', $date)
            ->where('Months', '<=', (int)$bulan)
            ->where(function ($q) {
                $q->whereNotNull('Category')->where('Category', '!=', '');
            })
            ->whereIn('CustNum', [
                'HPM',
                'MMKI',
                'MMKSI',
                'MKM',
                'SIM',
                'SIS',
                'IAMI',
                'TMMIN',
                'UDAMI'
            ])
            ->groupBy('Years', 'Category');

        // Apply search filter
        if (!empty($search)) {
            $baseQuery->having(function ($q) use ($search) {
                $q->orHaving('Category', 'LIKE', "%{$search}%")
                    ->orHaving('TotalSales', 'LIKE', "%{$search}%")
                    ->orHaving('TotalCost', 'LIKE', "%{$search}%")
                    ->orHaving('TotalProfit', 'LIKE', "%{$search}%");
            });
        }

        $totalData = $baseQuery->count(); // Jumlah kategori (group)
        $totalFiltered = $totalData;

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->TotalSales;
                $cost = (float) $row->TotalCost;
                $profit = (float) $row->TotalProfit;

                $status = 'Break Even';
                if ($profit > $cost)
                    $status = 'Profit';
                elseif ($profit < $cost)
                    $status = 'Loss';

                return [
                    'Years' => (int) $row->Years,
                    'Category' => $row->Category,
                    'Sales' => $sales,
                    'Cost' => $cost,
                    'Expenses' => (int) $row->TotalExpenses,
                    'Profit' => (float) $row->TotalProfit,
                    'Status' => $status,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profit_category_by_monthly_table_export(Request $request)
    {
        $date = $request->input('date'); // Tahun
        [$year, $month] = explode('-', $date);

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'TotalSales',
            4 => 'TotalCost',
            5 => 'TotalExpenses',
            6 => 'TotalProfit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'TotalSales';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw('SUM(Sales) as TotalSales'),
                DB::raw('SUM(CostwVariance) as TotalCost'),
                DB::raw('SUM(Expenses) as TotalExpenses'),
                DB::raw('SUM(Profit) as TotalProfit')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->where(function ($q) {
                $q->whereNotNull('Category')->where('Category', '!=', '');
            })
            ->whereIn('CustNum', [
                'HPM',
                'MMKI',
                'MMKSI',
                'MKM',
                'SIM',
                'SIS',
                'IAMI',
                'TMMIN',
                'UDAMI'
            ])
            ->groupBy('Years', 'Months', 'Category');

        // Apply search filter
        if (!empty($search)) {
            $baseQuery->having(function ($q) use ($search) {
                $q->orHaving('Category', 'LIKE', "%{$search}%")
                    ->orHaving('TotalSales', 'LIKE', "%{$search}%")
                    ->orHaving('TotalCost', 'LIKE', "%{$search}%")
                    ->orHaving('TotalProfit', 'LIKE', "%{$search}%");
            });
        }

        $totalData = $baseQuery->count(); // Jumlah kategori (group)
        $totalFiltered = $totalData;

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->TotalSales;
                $TotalProfit = (float) $row->TotalProfit;
                $cost = (float) $row->TotalCost;

                $status = 'Break Even';
                if ($TotalProfit > $cost)
                    $status = 'Profit';
                elseif ($TotalProfit < $cost)
                    $status = 'Loss';

                return [
                    'Years' => (int) $row->Years,
                    'Months' => (int) $row->Months,
                    'Category' => $row->Category,
                    'Sales' => $sales,
                    'Cost' => $cost,
                    'Expenses' => (float) $row->TotalExpenses,
                    'Profit' => (float) $row->TotalProfit,
                    'Status' => $status,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function get_profit_model_by_monthly_table(Request $request)
    {
        $date = $request->input('date');

        [$year, $month] = explode('-', $date);

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'ModelName',
            3 => 'Sales',
            4 => 'Cost',
            5 => 'Expenses',
            6 => 'Profit',
            7 => 'Profit',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Sales';

        $baseQuery = DB::connection('sqlsrv5')->table('V_ProfitCostVariance')
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('ModelName', '!=', '')
            ->select(
                'Years',
                'Months',
                'ModelName',
                DB::raw('SUM(Profit) as Profit'),
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses')
            )->GroupBy('Years', 'Months', 'ModelName');

        $totalData = $baseQuery->get()->count();
        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('ModelName', 'LIKE', "%{$search}%")
                    ->orWhere('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Profit', 'LIKE', "%{$search}%")
                    ->orWhere('Sales', 'LIKE', "%{$search}%")
                    ->orWhere('Expenses', 'LIKE', "%{$search}%")
                    ->orWhere('Cost', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $baseQuery->get()->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($limit)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->Sales;
                $cost = (float) $row->Cost;
                $profit = (float) $row->Profit;

                $loss = $profit < 0 ? 'Loss' : 'Profit';

                return [
                    'Years' => (int) $row->Years,
                    'Months' => (int) $row->Months,
                    'ModelName' => $row->ModelName,
                    'Cost' => $cost,
                    'Expenses' => $row->Expenses,
                    'Sales' => $sales,
                    'Profit' => (float) $row->Profit,
                    'Loss' => $loss,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
    public function get_profit_model_by_month_export(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        try {
            $start = Carbon::parse($startDate)->startOfMonth();
            $end = Carbon::parse($endDate)->endOfMonth();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $search = $request->input('search.value');
        $startRow = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            0 => 'InvoiceYear',
            1 => 'InvoiceMonths',
            2 => 'Model',
            3 => 'Profit',
            4 => 'Sales',
            5 => 'Cost',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'InvoiceMonths';

        $baseQuery = DB::connection('sqlsrv5')
            ->table('InvoiceProfitModelByMonth')
            ->whereRaw("CAST(CONCAT(InvoiceYear, '-', RIGHT('00' + CAST(InvoiceMonths AS VARCHAR), 2), '-01') AS DATE) BETWEEN ? AND ?", [
                $start->toDateString(),
                $end->toDateString()
            ])
            ->where('Model', '!=', '');

        $totalData = $baseQuery->count();

        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('Model', 'LIKE', "%{$search}%")
                    ->orWhere('InvoiceYear', 'LIKE', "%{$search}%")
                    ->orWhere('InvoiceMonths', 'LIKE', "%{$search}%")
                    ->orWhere('Profit', 'LIKE', "%{$search}%")
                    ->orWhere('Sales', 'LIKE', "%{$search}%")
                    ->orWhere('Cost', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $baseQuery->count();

        $data = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($startRow)
            ->get()
            ->map(function ($row) {
                $sales = (float) $row->Sales;
                $cost = (float) $row->Cost;
                $profit = (float) $row->Profit;

                $loss = $cost > $sales ? 'Loss' : 'Profit';

                $formatRupiah = function ($num) {
                    return 'Rp ' . number_format($num, 0, ',', '.');
                };

                return [
                    'InvoiceYear' => (int) $row->InvoiceYear,
                    'InvoiceMonths' => (int) $row->InvoiceMonths,
                    'Model' => $row->Model,
                    'Profit' => $formatRupiah($profit),
                    'Sales' => $formatRupiah($sales),
                    'Cost' => $formatRupiah($cost),
                    'Loss' => $loss,
                ];
            });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }


    // public function get_profit_monthly($year)
    // {
    //     $resultsX = DB::connection('sqlsrv5')->table('SalesProfitByMonthly as a')->orderBy('ShipMonth', 'asc')->where('ShipYear', $year)->get();
    //     $data_chart_sales_month = '';
    //     $data_chart_cost_month = '';
    //     $no_sales_month = 0;
    //     foreach ($resultsX as $a) {
    //         $data_chart_sales_month .= (int) $a->Sales . ($no_sales_month == 11 ? '' : ', ');
    //         $data_chart_cost_month .= (int) $a->Cost . ($no_sales_month == 11 ? '' : ', ');
    //         $no_sales_month++;
    //     }

    //     $responseData = [
    //         'data_chart_sales_month' => $data_chart_sales_month,
    //         'data_chart_cost_month' => $data_chart_cost_month,
    //     ];
    //     return response()->json($responseData);
    // }

    // public function get_profit_cust_yearly($year)
    // {
    //     $resultsX = DB::connection('sqlsrv5')->table('ProfitYearlyByCust as a')->orderBy('Sales', 'desc')->where('ShipYear', $year)->get();
    //     $data_cust_sales_year = '';
    //     $data_cust_cost_year = '';
    //     $no_sales_year = 0;
    //     foreach ($resultsX as $a) {
    //         $data_cust_sales_year .= (int) $a->Sales . ($no_sales_year == 8 ? '' : ', ');
    //         $data_cust_cost_year .= (int) $a->Cost . ($no_sales_year == 8 ? '' : ', ');
    //         $no_sales_year++;
    //     }

    //     $responseData = [
    //         'data_cust_sales_year' => $data_cust_sales_year,
    //         'data_cust_cost_year' => $data_cust_cost_year,
    //     ];
    //     return response()->json($responseData);
    // }

    // public function get_profit_cust_monthly($year)
    // {
    //     $str_year = explode('-', $year);
    //     $year = (int) $str_year[0];
    //     $month = $str_year[1];

    //     $resultsX = DB::connection('sqlsrv5')->table('SalesProfitMonthlyByCust as a')->orderBy('ShipMonth', 'asc')->where('ShipYear', $year)->where('ShipMonth', $month)->orderBy('Sales', 'desc')->get();
    //     $data_cust_sales_month = '';
    //     $data_cust_cost_month = '';
    //     $no_sales_month = 0;
    //     foreach ($resultsX as $a) {
    //         $data_cust_sales_month .= (int) $a->Sales . ($no_sales_month == 8 ? '' : ', ');
    //         $data_cust_cost_month .= (int) $a->Cost . ($no_sales_month == 8 ? '' : ', ');
    //         $no_sales_month++;
    //     }

    //     $responseData = [
    //         'data_cust_sales_month' => $data_cust_sales_month,
    //         'data_cust_cost_month' => $data_cust_cost_month,
    //     ];
    //     return response()->json($responseData);
    // }

    public function get_profit_cust_date($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')->table('SalesProfitByDay as a')
            ->selectRaw('ShipDay, SUM(Sales) as Sales, SUM(Cost) as Cost')
            ->where('ShipYear', $year)
            ->where('ShipMonth', $month)
            ->groupBy('ShipDay')
            ->orderBy('ShipDay', 'asc')
            ->get();

        $data_cust_sales_date = '';
        $data_cust_cost_date = '';
        $data_val_date = '';
        $no_sales_date = 0;

        $total_data = count($resultsX) - 1;

        foreach ($resultsX as $a) {
            $data_cust_sales_date .= (int) $a->Sales . ($no_sales_date == $total_data ? '' : ', ');
            $data_cust_cost_date .= (int) $a->Cost . ($no_sales_date == $total_data ? '' : ', ');
            $data_val_date .= (int) $a->ShipDay . ($no_sales_date == $total_data ? '' : ', ');
            $no_sales_date++;
        }

        $responseData = [
            'data_cust_sales_date' => $data_cust_sales_date,
            'data_cust_cost_date' => $data_cust_cost_date,
            'data_val_date' => $data_val_date,
        ];

        return response()->json($responseData);
    }

    public function get_profit_invoice_cust_monthly($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->groupBy('Years', 'Months', 'CustNum')
            ->where('Years', $year)
            ->where('Months', $month)
            ->whereRaw('LEN(CustNum) <= 5')
            ->limit(9)

            ->orderBy('Sales', 'desc')->get();
        $data_cust = '';
        $data_cust_sales_month = '';
        $data_cust_profit_month = '';
        $data_cust_cost_month = '';
        $data_cust_expenses_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_cust .= $a->CustNum . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_sales_month .= (int) $a->Sales . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_cost_month .= (int) $a->Cost . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_expenses_month .= (int) $a->Expenses . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_profit_month .= (int) $a->Profit . ($no_sales_month == 8 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_cust' => $data_cust,
            'data_cust_sales_month' => $data_cust_sales_month,
            'data_cust_cost_month' => $data_cust_cost_month,
            'data_cust_expenses_month' => $data_cust_expenses_month,
            'data_cust_profit_month' => $data_cust_profit_month,
        ];
        return response()->json($responseData);
    }
    public function get_profitability_invoice_cust_monthly($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'Months',
                'CustNum',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->groupBy('Years', 'Months', 'CustNum')
            ->where('Years', $year)
            ->where('Months', $month)
            ->whereRaw('LEN(CustNum) <= 5')
            ->limit(9)

            ->orderBy('Profit', 'desc')->get();
        $data_cust = '';
        $data_cust_profit_month = '';
        $data_cust_loss_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_cust .= $a->CustNum . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_profit_month .= (int) $a->Profit . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_loss_month .= (int) $a->Loss . ($no_sales_month == 8 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_cust' => $data_cust,
            'data_cust_profit_month' => $data_cust_profit_month,
            'data_cust_loss_month' => $data_cust_loss_month,
        ];
        return response()->json($responseData);
    }

    public function get_profit_invoice_cust_yearly($year)
    {
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;

        $resultsX = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'CustNum',
                DB::raw('SUM(Sales) as Sales'),
                DB::raw('SUM(CostwVariance) as Cost'),
                DB::raw('SUM(Expenses) as Expenses'),
                DB::raw('SUM(Profit) as Profit')
            )
            ->groupBy('Years', 'CustNum')
            ->where('Years', $year)
            ->where('Months', '<=', (int)$bulan)
            ->whereRaw('LEN(CustNum) <= 5')
            ->orderBy('Sales', 'DESC')
            ->get();
        $data_month = '';
        $data_cust_sales_year = '';
        $data_cust_cost_year = '';
        $data_cust_expenses_year = '';
        $data_cust_profit_year = '';
        $no_sales_year = 0;
        foreach ($resultsX as $a) {
            $data_month .= $a->CustNum . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_sales_year .= (int) $a->Sales . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_cost_year .= (int) $a->Cost . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_expenses_year .= (int) $a->Expenses . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_profit_year .= (int) $a->Profit . ($no_sales_year == 15 ? '' : ', ');
            $no_sales_year++;
        }

        $responseData = [
            'data_month' => $data_month,
            'data_cust_sales_year' => $data_cust_sales_year,
            'data_cust_cost_year' => $data_cust_cost_year,
            'data_cust_expenses_year' => $data_cust_expenses_year,
            'data_cust_profit_year' => $data_cust_profit_year,
        ];
        return response()->json($responseData);
    }

    public function get_profitability_invoice_cust_yearly($year)
    {
        $resultsX = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->select(
                'Years',
                'CustNum',
                DB::raw('SUM(CASE WHEN Profit > 0 THEN Profit ELSE 0 END) as Profit'),
                DB::raw('SUM(CASE WHEN Profit < 0 THEN ABS(Profit) ELSE 0 END) as Loss')
            )
            ->groupBy('Years', 'CustNum')
            ->where('Years', $year)
            ->whereRaw('LEN(CustNum) <= 5')
            ->orderBy('Profit', 'DESC')
            ->get();
        $data_year = '';
        $data_cust_profit_year = '';
        $data_cust_loss_year = '';
        $no_sales_year = 0;
        foreach ($resultsX as $a) {
            $data_year .= $a->CustNum . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_profit_year .= (int) $a->Profit . ($no_sales_year == 15 ? '' : ', ');
            $data_cust_loss_year .= (int) $a->Loss . ($no_sales_year == 15 ? '' : ', ');
            $no_sales_year++;
        }

        $responseData = [
            'data_year' => $data_year,
            'data_cust_profit_year' => $data_cust_profit_year,
            'data_cust_loss_year' => $data_cust_loss_year,
        ];
        return response()->json($responseData);
    }

    public function get_invoice_profit_monthly($year)
    {
        $bulan = Carbon::now()->month;
        $bulan = $bulan - 1;
        $rows = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance')
            ->selectRaw('CAST(Months AS INT ) AS m, SUM(Sales) AS sales, SUM(CostwVariance) AS cost, SUM(Expenses) AS expenses, SUM(Profit) as Profit')
            ->where('Years', (int)$year)
            ->where('Months', '<=', (int)$bulan)
            ->groupBy('Months')
            ->orderByRaw('CAST(Months AS INT)')
            ->get();

        // selalu 12 elemen
        $sales = array_fill(0, 12, 0.0);
        $cost  = array_fill(0, 12, 0.0);
        $expenses  = array_fill(0, 12, 0.0);
        $profit  = array_fill(0, 12, 0.0);

        foreach ($rows as $r) {
            $idx = max(1, min(12, (int)$r->m)) - 1; // 0..11
            $sales[$idx] = (float)$r->sales;
            $cost[$idx]  = (float)$r->cost;
            $expenses[$idx]  = (float)$r->expenses;
            $profit[$idx]  = (float)$r->Profit;
        }

        return response()->json([
            'data_chart_sales_month' => $sales, // kirim array
            'data_chart_cost_month'  => $cost,
            'data_chart_expenses_month'  => $expenses,
            'data_chart_profit_month'  => $profit,
        ]);
    }



    public function get_invoice_profit_cust_date($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];
        // $cust = $str_year[2]  ;

        $resultsX = DB::connection('sqlsrv5')->table('InvoiceProfitByDay as a')
            ->orderBy('InvoiceMonth', 'asc')
            ->where('InvoiceYear', $year)
            ->where('InvoiceMonth', $month)
            ->orderBy('InvoiceDay', 'asc')->get();
        $total_data = $resultsX->count() - 1;
        $data_cust_sales_date = '';
        $data_cust_cost_date = '';
        $data_val_date = '';
        $no_sales_date = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_date .= (int) $a->Sales . ($no_sales_date == $total_data ? '' : ', ');
            $data_cust_cost_date .= (int) $a->Cost . ($no_sales_date == $total_data ? '' : ', ');
            $data_val_date .= (int) $a->InvoiceDay . ($no_sales_date == $total_data ? '' : ', ');
            $no_sales_date++;
        }

        $responseData = [
            'data_cust_sales_date' => $data_cust_sales_date,
            'data_cust_cost_date' => $data_cust_cost_date,
            'data_val_date' => $data_val_date,
        ];
        return response()->json($responseData);
    }

    public function get_invoice_cost_table(Request $request)
    {
        $str_year = explode('-', $request->year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $search = $request->input('search.value');
        $limit = $request->input('length') ?? 10;
        $start = $request->input('start') ?? 0;
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'no',
            1 => 'PartNum',
            2 => 'Qty',
            3 => 'Sales',
            4 => 'Cost',
            5 => 'Expenses',
            6 => 'Profit',
            7 => 'Percents',
            8 => 'Profit'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'PartNum';

        $totalData = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance as a')
            ->join('DataSalesQtyBackUp as  b', function ($join) {
                $join->on('a.Years', 'b.Years');
                $join->on('a.Months', 'b.Months');
                $join->on('a.PartNum', 'b.PartNum');
            })
            ->where('a.Years', $year)
            ->where('a.Months', $month)
            ->groupBy('a.Years', 'a.Months', 'a.PartNum')
            ->select('a.Years', 'a.Months', 'a.PartNum')
            ->get()
            ->count();

        $filteredQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance as a')
            ->join('DataSalesQtyBackUp as  b', function ($join) {
                $join->on('a.Years', 'b.Years');
                $join->on('a.Months', 'b.Months');
                $join->on('a.PartNum', 'b.PartNum');
            })
            ->select(
                'a.Years',
                'a.Months',
                'a.PartNum',
                'b.Qty',
                'a.Sales',
                'a.CostwVariance as Cost',
                'a.Expenses',
                'a.Profit',
                DB::raw('ROUND((a.Profit / NULLIF(a.Sales, 0)) * 100, 1) as Percents')

            )
            ->where('a.Years', $year)
            ->where('a.Months', $month);

        if (!empty($search)) {
            $filteredQuery->where('a.PartNum', 'like', "{$search}");
        }

        $totalFiltered = $filteredQuery->get()->count();

        $posts = $filteredQuery
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        $no = $start;

        foreach ($posts as $post) {
            $no++;
            $statusText = '';
            $bgClass = 'warning';

            if ($post->Profit < 0) {
                $statusText = 'Loss';
                $bgClass = 'danger';
            } elseif ($post->Profit == 0) {
                $statusText = 'Break Even';
                $bgClass = 'warning';
            } else {
                $statusText = 'Profit';
                $bgClass = 'success';
            }

            $status = '<a onclick="">
            <div style="cursor: pointer;" class="shrink-0 bg-' . $bgClass . ' text-white rounded-sm w-15 h-6 flex justify-center items-center dark:bg-' . $bgClass . ' dark:text-white">
                <div class="align-center text-xs">
                    <span id="total_open_doc">' . $statusText . '</span>
                </div>
            </div>
        </a>';

            $nestedData = [
                'no' => $no,
                'PartNum' => $post->PartNum,
                'qty' => number_format($post->Qty, 0),
                'sales' => number_format($post->Sales, 0),
                'cost' => number_format($post->Cost, 0),
                'expenses' => number_format($post->Expenses, 0),
                'profit' => number_format($post->Profit, 0),
                'percens' => number_format($post->Percents, 1),
                'status' => $status
            ];

            $data[] = $nestedData;
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }

    public function get_invoice_expenses_table(Request $request)
    {
        $str_year = explode('-', $request->year);
        $year = (int) $str_year[0];
        $month = (int) $str_year[1];

        $search = $request->input('search.value');
        $limit = $request->input('length') ?? 10;
        $start = $request->input('start') ?? 0;
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'PartNum',
            3 => 'Sales',
            4 => 'DistributeAmount'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $totalData = DB::connection('sqlsrv5')
            ->table('DistributeExpenses')
            ->where('Years', $year)
            ->where('Months', $month)
            ->count();

        $filteredQuery = DB::connection('sqlsrv5')
            ->table('DistributeExpenses')
            ->select(
                'Years',
                'Months',
                'PartNum',
                'Sales',
                'DistributeAmount'
            )
            ->where('Years', $year)
            ->where('Months', $month);

        if (!empty($search)) {
            $filteredQuery->where('PartNum', 'like', "%{$search}%");
        }

        $totalFiltered = $filteredQuery->count();

        $posts = $filteredQuery
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];
        $no = $start;

        foreach ($posts as $post) {
            $no++;

            $nestedData = [
                'no' => $no,
                'Years' => $post->Years,
                'Months' => $post->Months,
                'PartNum' => $post->PartNum,
                'Sales' => number_format($post->Sales, 0),
                'DistributeAmount' => number_format($post->DistributeAmount, 0),
            ];

            $data[] = $nestedData;
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }



    public function get_invoice_cost_table_detail(Request $request)
    {
        $partnum = $request->input('PartNum');
        $date = $request->input('year');

        $str_year = explode('-', $date);
        $year = (int) ($str_year[0] ?? date('Y'));
        $month = isset($str_year[1]) ? (int) $str_year[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance as a')
            ->join('DataSalesQtyBackUp as  b', function ($join) {
                $join->on('a.Years', 'b.Years');
                $join->on('a.Months', 'b.Months');
                $join->on('a.PartNum', 'b.PartNum');
            })
            ->select(
                'a.Years',
                'a.Months',
                'a.PartNum',
                'a.LaborCost',
                'a.BurdenCost',
                'a.MtlUnitCost',
                'a.BurMtlUniCost',
                'a.SbcCost',
                'a.CostwVariance',
                DB::raw('(a.VarianceLabor + a.AplLabor) as VarianceAplLabor'),
                DB::raw('(a.VarianceMaterial + a.AplMtl) as VarianceAplMaterial'),
                DB::raw('(a.VarianceBurden + a.AplBurden) as VarianceAplBurden'),
                'a.VarianceMtlBurden',
                'a.VarianceSubCont',

            )
            ->where('a.PartNum', $partnum)
            ->where('a.Years', $year)
            ->where('a.Months', $month);

        $totalData = $query->count();
        $filteredQuery = clone $query;
        $totalFiltered = $filteredQuery->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);

        $columns = [
            'Years',
            'Months',
            'LaborCost',
            'BurdenCost',
            'MtlUnitCost',
            'BurMtlUniCost',
            'SbcCost',
            'CostwVariance',
            'VarianceAplLabor',
            'VarianceAplMaterial',
            'VarianceAplBurden',
            'VarianceMtlBurden',
            'VarianceSubCont'
        ];

        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $query->orderBy($orderColumn, $orderDirection);

        $posts = $query
            ->offset($start)
            ->limit($limit)
            ->get();

        $data = [];

        foreach ($posts as $post) {
            $nestedData = [
                'Years'            => $post->Years,
                'Months'           => $post->Months,
                'PartNum'          => $post->PartNum,
                'LaborCost'        => number_format($post->LaborCost, 0),
                'BurdenCost'       => number_format($post->BurdenCost, 0),
                'MtlUnitCost'      => number_format($post->MtlUnitCost, 0),
                'BurMtlUniCost'    => number_format($post->BurMtlUniCost, 0),
                'SbcCost'          => number_format($post->SbcCost, 0),
                'CostwVariance'             => number_format($post->CostwVariance, 0),
                'VarianceAplLabor' => number_format($post->VarianceAplLabor, 0),
                'VarianceAplMaterial' => number_format($post->VarianceAplMaterial, 0),
                'VarianceAplBurden' => number_format($post->VarianceAplBurden, 0),
                'VarianceMtlBurden' => number_format($post->VarianceMtlBurden, 0),
                'VarianceSubCont' => number_format($post->VarianceSubCont, 0),
            ];
            $data[] = $nestedData;
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }
    public function get_invoice_cost_table_export(Request $request)
    {
        $str_year = explode('-', $request->date);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $search = $request->input('search.value');
        $start = $request->input('start') ?? 0;
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PartNum',
            1 => 'PartNum',
            2 => 'Qty',
            3 => 'Sales',
            4 => 'Cost',
            5 => 'Expenses',
            6 => 'Profit',
            7 => 'Percents',
            8 => 'Profit'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'PartNum';

        $totalData = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance as a')
            ->join('DataSalesQtyBackUp as  b', function ($join) {
                $join->on('a.Years', 'b.Years');
                $join->on('a.Months', 'b.Months');
                $join->on('a.PartNum', 'b.PartNum');
            })
            ->where('a.Years', $year)
            ->where('a.Months', $month)
            ->groupBy('a.Years', 'a.Months', 'a.PartNum')
            ->select('a.Years', 'a.Months', 'a.PartNum')
            ->get()
            ->count();

        $filteredQuery = DB::connection('sqlsrv5')
            ->table('V_ProfitCostVariance as a')
            ->join('DataSalesQtyBackUp as  b', function ($join) {
                $join->on('a.Years', 'b.Years');
                $join->on('a.Months', 'b.Months');
                $join->on('a.PartNum', 'b.PartNum');
            })
            ->select(
                'a.Years',
                'a.Months',
                'a.PartNum',
                'b.Qty',
                'a.Sales',
                'a.CostwVariance as Cost',
                'a.Expenses',
                'a.Profit',
                DB::raw('ROUND((a.Profit / NULLIF(a.Sales, 0)) * 100, 1) as Percents'),
                'a.LaborCost',
                'a.BurdenCost',
                'a.MtlUnitCost',
                'a.BurMtlUniCost',
                'a.SbcCost',
                'a.CostwVariance',
                DB::raw('(a.VarianceLabor + a.AplLabor) as VarianceAplLabor'),
                DB::raw('(a.VarianceMaterial + a.AplMtl) as VarianceAplMaterial'),
                DB::raw('(a.VarianceBurden + a.AplBurden) as VarianceAplBurden'),
                'a.VarianceMtlBurden',
                'a.VarianceSubCont',

            )
            ->where('a.Years', $year)
            ->where('a.Months', $month);

        if (!empty($search)) {
            $filteredQuery->having('a.PartNum', 'LIKE', "%{$search}%");
        }

        $totalFiltered = $filteredQuery->get()->count();

        $posts = $filteredQuery
            ->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->get();

        $data = [];
        $no = $start;

        foreach ($posts as $post) {
            $no++;
            $statusText = '';
            $bgClass = 'warning';

            if ($post->Profit < 0) {
                $statusText = 'Loss';
                $bgClass = 'danger';
            } elseif ($post->Profit == 0) {
                $statusText = 'Break Even';
                $bgClass = 'warning';
            } else {
                $statusText = 'Profit';
                $bgClass = 'success';
            }

            $status = $statusText;

            $nestedData = [
                'No' => $no,
                'PartNum' => $post->PartNum,
                'Qty' => $post->Qty,
                'Sales' => $post->Sales,
                'Cost' => $post->Cost,
                'Expenses' => $post->Expenses,
                'Profit' => $post->Profit,
                'Percents' => $post->Percents,
                'Status' => $status,
                'LaborCost'        => number_format($post->LaborCost, 0),
                'BurdenCost'       => number_format($post->BurdenCost, 0),
                'MtlUnitCost'      => number_format($post->MtlUnitCost, 0),
                'BurMtlUniCost'    => number_format($post->BurMtlUniCost, 0),
                'SbcCost'          => number_format($post->SbcCost, 0),
                'CostwVariance'             => number_format($post->CostwVariance, 0),
                'VarianceAplLabor' => number_format($post->VarianceAplLabor, 0),
                'VarianceAplMaterial' => number_format($post->VarianceAplMaterial, 0),
                'VarianceAplBurden' => number_format($post->VarianceAplBurden, 0),
                'VarianceMtlBurden' => number_format($post->VarianceMtlBurden, 0),
                'VarianceSubCont' => number_format($post->VarianceSubCont, 0),
            ];

            $data[] = $nestedData;
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }



    public function get_RCDPerYears()
    {
        $results = DB::connection('sqlsrv5')->table('RCDPerYears')
            ->select('Years', 'Plan', 'Actual', 'Percents')
            ->orderBy('Years', 'asc')
            ->get();

        $data = [
            'data_plan_year' => [],
            'data_actual_year' => [],
            'data_persentase_year' => []
        ];

        foreach ($results as $row) {
            $year = (int) $row->Years;
            $data['data_plan_year'][$year] = (float) $row->Plan;
            $data['data_actual_year'][$year] = (float) $row->Actual;
            $data['data_persentase_year'][$year] = round($row->Percents);
        }

        return response()->json($data);
    }

    public function get_RCDPerMonthAccum($year)
    {

        $str_year = explode('-', $year);
        $Year = (int) $str_year[0];

        $results = DB::connection('sqlsrv5')->table('RCDPerMonthsAccum')
            ->select('Months', 'AccumulativePlan', 'AccumulativeActual', 'Percents')
            ->where('Years', $Year)
            ->orderBy('Months', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'inpt_val_plan_accum' => '',
                'inpt_val_actual_accum' => '',
                'inpt_val_persentase_accum' => ''
            ]);
        }

        return response()->json([
            'data_plan_accum' => $results->pluck('AccumulativePlan')->toArray(),
            'data_actual_accum' => $results->pluck('AccumulativeActual')->toArray(),
            'data_persentase_accum' => $results->pluck('Percents')->map(fn($value) => round($value))->toArray()
        ]);
    }

    public function get_RCDPerYearsCategory($year)
    {
        $str_year = explode('-', $year);
        $Year = (int) $str_year[0];

        $results = DB::connection('sqlsrv5')->table('RCDPerYearsCategory')
            ->select('Years', 'Category', 'Plan', 'Actual', 'Percents')
            ->where('Years', $Year)
            ->whereNotNull('category')
            ->orderBy('Category')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_cat' => [],
                'data_actual_cat' => [],
                'data_persentase_cat' => [],
                'data_category_cat' => []
            ]);
        }

        return response()->json([
            'data_plan_cat' => $results->pluck('Plan')->toArray(),
            'data_actual_cat' => $results->pluck('Actual')->toArray(),
            'data_persentase_cat' => $results->pluck('Percents')->map(fn($value) => (int) round($value))->toArray(),
            'data_category_cat' => $results->pluck('Category')->toArray()
        ]);
    }

    public function get_transaction_effect($yearMonth)
    {
        $str_year_month = explode('-', $yearMonth);
        $Year = isset($str_year_month[0]) ? (int) $str_year_month[0] : (int) date('Y');
        $Month = isset($str_year_month[1]) ? (int) $str_year_month[1] : null;

        $query = DB::connection('sqlsrv5')->table('RCDTransactionEffect')
            ->select(
                'TransactionEffects',
                DB::raw('SUM([Plan]) as TotalPlan'),
                DB::raw('SUM([Actual]) as TotalActual')
            )
            ->whereNotNull('TransactionEffects')
            ->where('TransactionEffects', '!=', '')
            ->where('Years', $Year);

        if (!is_null($Month)) {
            $query->where('Months', $Month);
        }

        $results = $query->groupBy('TransactionEffects')
            ->orderBy('TotalPlan', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_effect' => [],
                'data_actual_effect' => [],
                'categories' => []
            ]);
        }

        return response()->json([
            'categories' => $results->pluck('TransactionEffects')->toArray(),
            'data_plan_effect' => $results->pluck('TotalPlan')->toArray(),
            'data_actual_effect' => $results->pluck('TotalActual')->toArray()
        ]);
    }

    public function get_transaction_Category($yearMonth)
    {
        $str_year = explode('-', $yearMonth);
        $Year = (int) $str_year[0];
        $Month = isset($str_year[1]) ? (int) $str_year[1] : null;

        $query = DB::connection('sqlsrv5')->table('RCDTransactionCategory')
            ->select(
                'Category',
                DB::raw('SUM([Plan]) as TotalPlan'),
                DB::raw('SUM([Actual]) as TotalActual')
            )
            ->where('Years', $Year);

        if (!is_null($Month)) {
            $query->where('Months', $Month);
        }

        $results = $query->groupBy('Category')
            ->orderBy('TotalPlan', 'desc')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_Category' => [],
                'data_actual_Category' => [],
                'categoriesTransaction' => []
            ]);
        }

        return response()->json([
            'categoriesTransaction' => $results->pluck('Category')->toArray(),
            'data_plan_Category' => $results->pluck('TotalPlan')->toArray(),
            'data_actual_Category' => $results->pluck('TotalActual')->toArray()

        ]);
    }

    public function get_transaction_CategoryAccum($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')->table('RCDTransactionCategoryAccum')
            ->select(
                'Years',
                'Months',
                'Category',
                DB::raw('SUM(CASE WHEN Category IS NOT NULL THEN [Plan] ELSE 0 END) AS TotalPlan'),
                DB::raw('SUM(CASE WHEN Category IS NOT NULL THEN Actual ELSE 0 END) AS TotalActual')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->groupBy('Years', 'Months', 'Category')
            ->orderBy('TotalPlan', 'desc')
            ->get();

        $filteredResults = $results->filter(function ($item) {
            return $item->TotalPlan != 0 && $item->TotalActual != 0;
        });

        if ($filteredResults->isEmpty()) {
            return response()->json([
                'categories' => [],
                'data_plan_categoryAccum' => [],
                'data_actual_categoryAccum' => []
            ]);
        }

        return response()->json([
            'categories' => $filteredResults->pluck('Category')->toArray(),
            'data_plan_categoryAccum' => $filteredResults->pluck('TotalPlan')->toArray(),
            'data_actual_categoryAccum' => $filteredResults->pluck('TotalActual')->toArray()
        ]);
    }


    public function get_transaction_EffectAccum($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $results = DB::connection('sqlsrv5')->table('RCDTransactionEffectAccum')
            ->select(
                'Years',
                'Months',
                'TransactionEffects',
                DB::raw('SUM([Plan]) AS TotalPlan'),
                DB::raw('SUM(Actual) AS TotalActual')
            )
            ->where('Years', $year)
            ->where('Months', $month)
            ->whereRaw("ISNULL(LTRIM(RTRIM(TransactionEffects)), '') <> ''")
            ->groupBy('Years', 'Months', 'TransactionEffects')
            ->orderBy('TotalPlan', 'desc')
            ->get();

        return response()->json([
            'categoriesEffect' => $results->pluck('TransactionEffects')->toArray(),
            'data_plan_effectAccum' => $results->pluck('TotalPlan')->toArray(),
            'data_actual_effectAccum' => $results->pluck('TotalActual')->toArray(),
        ]);
    }


    public function get_transaction_summaryMonth($year)
    {
        $year = (int) $year;

        $results = DB::connection('sqlsrv5')->table('RCDSummaryPerMonths')
            ->select('Years', 'Plan', 'Actual', 'Months')
            ->where('Years', $year)
            ->orderBy('Months')
            ->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_summary' => [],
                'data_actual_summary' => [],
            ]);
        }

        $planArray = $results->pluck('Plan')->toArray();
        $actualArray = $results->pluck('Actual')->toArray();

        $groupIntoQuarters = function ($monthlyData) {
            return array_map(
                function ($chunk) {
                    return array_sum($chunk);
                },
                array_chunk($monthlyData, 3)
            );
        };

        return response()->json([
            'data_plan_summary' => $groupIntoQuarters($planArray),
            'data_actual_summary' => $groupIntoQuarters($actualArray),
        ]);
    }


    public function get_transaction_dept($year)
    {
        $str_year = explode('-', $year);
        $year = (int) ($str_year[0] ?? date('Y'));
        $month = isset($str_year[1]) ? (int) $str_year[1] : null;

        $query = DB::connection('sqlsrv5')->table('RCDDeptSummary')
            ->select('Years', 'Months', 'Plan', 'Actual', 'Dept')
            ->where('Years', $year)
            ->whereRaw("ISNULL(LTRIM(RTRIM(Dept)), '') <> ''")
            ->orderBy('Plan', 'desc');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_dept' => [],
                'data_actual_dept' => [],
                'data_category_dept' => [],
            ]);
        }

        return response()->json([
            'data_plan_dept' => $results->pluck('Plan')->toArray(),
            'data_actual_dept' => $results->pluck('Actual')->toArray(),
            'data_category_dept' => $results->pluck('Dept')->toArray(),
        ]);
    }
    public function get_transaction_deptAccum($year)
    {
        $str_year = explode('-', $year);
        $year = (int) ($str_year[0] ?? date('Y'));
        $month = isset($str_year[1]) ? (int) $str_year[1] : null;

        $query = DB::connection('sqlsrv5')->table('RCDDeptAccum')
            ->select('Years', 'Months', 'Plan', 'Actual', 'Dept')
            ->where('Years', $year)
            ->whereRaw("ISNULL(LTRIM(RTRIM(Dept)), '') <> ''")
            ->orderBy('Plan', 'desc');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            return response()->json([
                'data_plan_deptAccum' => [],
                'data_actual_deptAccum' => [],
                'data_category_deptAccum' => [],
            ]);
        }

        return response()->json([
            'data_plan_deptAccum' => $results->pluck('Plan')->toArray(),
            'data_actual_deptAccum' => $results->pluck('Actual')->toArray(),
            'data_category_deptAccum' => $results->pluck('Dept')->toArray(),
        ]);
    }


    public function get_transaction_activity($param)
    {
        $param_parts = explode('~', $param);

        $category = $param_parts[0] ?? null;
        $year_month = $param_parts[1] ?? date('Y');
        $order_by_param = strtolower($param_parts[2] ?? 'plan'); // default ke plan

        // Extract tahun & bulan
        $str_year = explode('-', $year_month);
        $year = (int) ($str_year[0] ?? date('Y'));
        $month = isset($str_year[1]) ? (int) $str_year[1] : null;

        // Tentukan field orderBy (Total_Plan atau Total_Actual)
        $order_by_field = $order_by_param === 'actual' ? 'Total_Actual' : 'Total_Plan';

        $query = DB::connection('sqlsrv5')->table('RCDTransactionActivity')
            ->select(
                'Description',
                DB::raw('SUM([Plan]) as Total_Plan'),
                DB::raw('SUM([Actual]) as Total_Actual')
            )
            ->where('Years', $year)
            ->whereNotNull('Key2')
            ->groupBy('Description')
            ->orderBy($order_by_field, 'desc'); // flexible orderBy

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        if (!is_null($category) && $category !== 'all') {
            $query->where('Category', $category);
        }

        $results = $query->take(5)->get();

        return response()->json([
            'data_plan_activity' => $results->pluck('Total_Plan')->map(fn($val) => (float) $val)->toArray(),
            'data_actual_activity' => $results->pluck('Total_Actual')->map(fn($val) => (float) $val)->toArray(),
            'data_category_activity' => $results->pluck('Description')->toArray(),
        ]);
    }






    public function get_transaction_EffectAccum_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDTransactionEffectAccum')
            ->select('Months', 'Years', 'TransactionEffects', 'Actual', 'Plan')
            ->where('Years', $year)
            ->whereNotNull('TransactionEffects')
            ->where('TransactionEffects', '!=', '')
            ->orderBy('Plan', 'desc');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'TransactionEffects', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('TransactionEffects', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
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

    public function get_invoice_summary_table(Request $request)
    {
        $yearMonth = explode('-', $request->year ?? date('Y'));
        $year = (int) ($yearMonth[0] ?? date('Y'));

        $query = DB::connection('sqlsrv5')->table('RCDPerMonthsAccum')
            ->select('Years', 'Months', 'AccumulativeActual', 'AccumulativePlan', 'Percents')
            ->where('Years', $year)
            ->whereNotNull('AccumulativeActual')->where('AccumulativeActual', '!=', 0)
            ->whereNotNull('AccumulativePlan')->where('AccumulativePlan', '!=', 0)
            ->whereNotNull('Percents')->where('Percents', '!=', 0)
            ->orderBy('Months');

        $totalData = clone $query;
        $totalData = $totalData->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'AccumulativeActual', 'AccumulativePlan', 'Percents'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('AccumulativeActual', 'LIKE', "%{$search}%")
                    ->orWhere('AccumulativePlan', 'LIKE', "%{$search}%")
                    ->orWhere('Percents', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    public function get_rcdYears_summary_table(Request $request)
    {
        $yearMonth = explode('-', $request->year ?? date('Y-m'));
        $year = (int) ($yearMonth[0] ?? date('Y'));

        $query = DB::connection('sqlsrv5')->table('RCDPerYears')
            ->select('Years', 'Actual', 'Plan')
            ->where('Years', $year);

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Actual', 'Plan'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_transaction_CategoryAccum_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDTransactionCategoryAccum')
            ->select('Months', 'Years', 'Category', 'Actual', 'Plan')
            ->where('Years', $year)
            ->whereNotNull('Category')
            ->where('Category', '!=', '');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();

        $data = $query->orderBy('Plan', 'desc')->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => count($data),
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_transactionCategory_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('year', date('Y-m')));
        $year = isset($yearMonth[0]) ? (int) $yearMonth[0] : (int) date('Y');
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDTransactionCategory')
            ->select('Months', 'Years', 'Category', 'Actual', 'Plan')
            ->orderBy('Plan', 'desc')
            ->where('Years', $year);

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();

        $search = $request->input('search.value');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Category', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
            });
        }

        $filteredData = $query->count();

        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        $columns = ['Years', 'Months', 'Category', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_transactionEffect_table(Request $request)
    {
        $dateInput = $request->input('month', date('Y-m'));
        $dateParts = explode('-', $dateInput);

        $year = isset($dateParts[0]) ? (int) $dateParts[0] : (int) date('Y');
        $month = isset($dateParts[1]) ? (int) $dateParts[1] : (int) date('m');

        $query = DB::connection('sqlsrv5')
            ->table('RCDTransactionEffect')
            ->select('Years', 'Months', 'TransactionEffects', 'Actual', 'Plan')
            ->whereNotNull('TransactionEffects')
            ->where('TransactionEffects', '!=', '')
            ->where('Years', $year)
            ->where('Months', $month)
            ->orderBy('Plan', 'desc');

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'TransactionEffects', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Months';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('TransactionEffects', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
            });
        }

        $filteredData = $query->count();

        $data = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function get_transaction_summaryMonth_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDSummaryPerMonths')
            ->select('Years', 'Months', 'Plan', 'Actual', 'Percents')
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

        $columns = ['Years', 'Months', 'Plan', 'Actual', 'Percents'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
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

    public function get_transaction_activity_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;
        $category = $request->input('selectCategory');
        $sort = strtolower($request->input('selectSort'));

        $query = DB::connection('sqlsrv5')
            ->table('RCDTransactionActivity')
            ->select('Years', 'Months', 'Key2', 'Description', 'Plan', 'Actual')
            ->whereNotNull('Key2')
            ->where('Key2', '!=', '')
            ->where('Years', $year);

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        if (!empty($category) && $category !== 'all') {
            $query->where('Category', $category);
        }

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Key2', 'Description', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Key2', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
            });
        }

        if (in_array($sort, ['plan', 'actual'])) {
            $query->orderBy(ucfirst($sort), 'desc');
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        $data = $query->offset($start)
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ], 200, [], JSON_NUMERIC_CHECK);
    }


    public function get_transaction_dept_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDDeptSummary')
            ->select('Years', 'Months', 'Dept', 'Plan', 'Actual')
            ->whereNotNull('Dept')
            ->where('Dept', '!=', '')
            ->where('Years', $year)
            ->orderBy('Plan', 'desc');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Dept', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Dept', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
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
    public function get_transaction_deptAccum_table(Request $request)
    {
        $yearMonth = explode('-', $request->input('yearMonth', date('Y-m')));
        $year = (int) ($yearMonth[0] ?? date('Y'));
        $month = isset($yearMonth[1]) ? (int) $yearMonth[1] : null;

        $query = DB::connection('sqlsrv5')
            ->table('RCDDeptAccum')
            ->select('Years', 'Months', 'Dept', 'Plan', 'Actual')
            ->whereNotNull('Dept')
            ->where('Dept', '!=', '')
            ->where('Years', $year)
            ->orderBy('Plan', 'desc');

        if (!is_null($month)) {
            $query->where('Months', $month);
        }

        $totalData = $query->count();
        $search = $request->input('search.value');
        $limit = $request->input('length', $totalData);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['Years', 'Months', 'Dept', 'Plan', 'Actual'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('Years', 'LIKE', "%{$search}%")
                    ->orWhere('Months', 'LIKE', "%{$search}%")
                    ->orWhere('Dept', 'LIKE', "%{$search}%")
                    ->orWhere('Plan', 'LIKE', "%{$search}%")
                    ->orWhere('Actual', 'LIKE', "%{$search}%");
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

    public function get_transaction_accum_detail(Request $request)
    {
        $selectedDate = $request->input('selected_date', date('Y-m-d'));
        $year = $request->input('year', date('Y', strtotime($selectedDate)));
        $month = $request->input('month', date('m', strtotime($selectedDate)));
        $category = $request->input('category');

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'Department',
            4 => 'Plan',
            5 => 'Actual',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $subQuery = "
        SELECT
            Years,
            Months,
            Category,
            Department,
            [Plan],
            Actual
        FROM RCDTransactionCategoryAccumDetail
        WHERE Category = ?
        AND Years = ?
        AND Months = ?
        ORDER BY [Plan] DESC
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$category, $year, $month]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->Years), strtolower($search)) ||
                    str_contains(strtolower($item->Months), strtolower($search)) ||
                    str_contains(strtolower($item->Category), strtolower($search)) ||
                    str_contains(strtolower($item->Department), strtolower($search)) ||
                    str_contains(strtolower($item->Plan), strtolower($search)) ||
                    str_contains(strtolower($item->Actual), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Years' => $row->Years,
                'Months' => $row->Months,
                'Category' => $row->Category,
                'Departement' => $row->Department,
                'Plan' => (int) $row->Plan,
                'Actual' => (int) $row->Actual,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }
    public function get_transaction_category_detail(Request $request)
    {
        $selectedDate = $request->input('selected_date', date('Y-m-d'));
        $year = $request->input('year', date('Y', strtotime($selectedDate)));
        $month = $request->input('month', date('m', strtotime($selectedDate)));
        $category = $request->input('category');

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'Department',
            4 => 'Plan',
            5 => 'Actual',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $subQuery = "
        SELECT
            Years,
            Months,
            Category,
            Department,
            [Plan],
            Actual
        FROM RCDTransactionCategoryDetail
        WHERE Category = ?
        AND Years = ?
        AND Months = ?
        ORDER BY [Plan] DESC
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$category, $year, $month]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->Years), strtolower($search)) ||
                    str_contains(strtolower($item->Months), strtolower($search)) ||
                    str_contains(strtolower($item->Category), strtolower($search)) ||
                    str_contains(strtolower($item->Department), strtolower($search)) ||
                    str_contains(strtolower($item->Plan), strtolower($search)) ||
                    str_contains(strtolower($item->Actual), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Years' => $row->Years,
                'Months' => $row->Months,
                'Category' => $row->Category,
                'Departement' => $row->Department,
                'Plan' => (int) $row->Plan,
                'Actual' => (int) $row->Actual,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }

    public function get_transaction_effect_accum_detail(Request $request)
    {
        $selectedDate = $request->input('selected_date', date('Y-m-d'));
        $year = $request->input('year', date('Y', strtotime($selectedDate)));
        $month = $request->input('month', date('m', strtotime($selectedDate)));
        $category = $request->input('category');

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'Department',
            4 => 'Plan',
            5 => 'Actual',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $subQuery = "
        SELECT *
        FROM (
        SELECT
            Years,
            Months,
            TransactionEffects as Category,
            Department,
            [Plan],
            Actual
        FROM RCDTransactionEffectAccumDetail
        ) AS Sub
        WHERE Category = ?
        AND Years = ?
        AND Months = ?
        ORDER BY [Plan] DESC
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$category, $year, $month]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->Years), strtolower($search)) ||
                    str_contains(strtolower($item->Months), strtolower($search)) ||
                    str_contains(strtolower($item->Category), strtolower($search)) ||
                    str_contains(strtolower($item->Department), strtolower($search)) ||
                    str_contains(strtolower($item->Plan), strtolower($search)) ||
                    str_contains(strtolower($item->Actual), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Years' => $row->Years,
                'Months' => $row->Months,
                'Category' => $row->Category,
                'Departement' => $row->Department,
                'Plan' => (int) $row->Plan,
                'Actual' => (int) $row->Actual,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }
    public function get_transaction_effect_detail(Request $request)
    {
        $selectedDate = $request->input('selected_date', date('Y-m-d'));
        $year = $request->input('year', date('Y', strtotime($selectedDate)));
        $month = $request->input('month', date('m', strtotime($selectedDate)));
        $category = $request->input('category');

        DB::connection('sqlsrv5')->statement("USE [sai_db]");

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'Years',
            1 => 'Months',
            2 => 'Category',
            3 => 'Department',
            4 => 'Plan',
            5 => 'Actual',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'Years';

        $subQuery = "
        SELECT *
        FROM (
        SELECT
            Years,
            Months,
            TransactionEffects as Category,
            Department,
            [Plan],
            Actual
        FROM RCDTransactionEffectDetail
        ) AS Sub
        WHERE Category = ?
        AND Years = ?
        AND Months = ?
        ORDER BY [Plan] DESC
    ";

        $rawData = DB::connection('sqlsrv5')->select($subQuery, [$category, $year, $month]);
        $collection = collect($rawData);

        if (!empty($search)) {
            $collection = $collection->filter(function ($item) use ($search) {
                return
                    str_contains(strtolower($item->Years), strtolower($search)) ||
                    str_contains(strtolower($item->Months), strtolower($search)) ||
                    str_contains(strtolower($item->Category), strtolower($search)) ||
                    str_contains(strtolower($item->Department), strtolower($search)) ||
                    str_contains(strtolower($item->Plan), strtolower($search)) ||
                    str_contains(strtolower($item->Actual), strtolower($search));
            });
        }

        $totalData = count($rawData);
        $totalFiltered = $collection->count();

        $paginated = $collection->slice($start, $limit)->values()->map(function ($row) {
            return [
                'Years' => $row->Years,
                'Months' => $row->Months,
                'Category' => $row->Category,
                'Departement' => $row->Department,
                'Plan' => (int) $row->Plan,
                'Actual' => (int) $row->Actual,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $paginated,
        ]);
    }
}
