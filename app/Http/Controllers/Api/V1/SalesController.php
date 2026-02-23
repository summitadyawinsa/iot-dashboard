<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use DateTime;
use DataTables;

class SalesController extends Controller
{
    public function get_profit_yearly($year)
    {
        $str_year = explode('~', $year);
        $start = (int) $str_year[0];
        $end = $str_year[1] + 1;
        $results = DB::connection('sqlsrv5')->table('SalesProfitByYearly as a')->whereBetween('ShipYear', [$start, $end])->get();
        $data_chart_sales = '';
        $data_chart_cost = '';
        $data_chart_budget = '';
        $no_sales = 0;
        foreach ($results as $a) {
            $data_chart_sales .= (int) $a->Sales . ($no_sales == 5 ? '' : ', ');
            $data_chart_cost .= (int) $a->Cost . ($no_sales == 5 ? '' : ', ');
            $data_chart_budget .= (int) $a->Budget . ($no_sales == 5 ? '' : ', ');

            $no_sales++;
        }

        $responseData = [
            'data_chart_sales' => $data_chart_sales,
            'data_chart_cost' => $data_chart_cost,
            'data_chart_budget' => $data_chart_budget,
        ];
        return response()->json($responseData);
    }

    public function get_profit_monthly($year)
    {
        $resultsX = DB::connection('sqlsrv5')->table('SalesProfitByMonthly as a')->orderBy('ShipMonth', 'asc')->where('ShipYear', $year)->get();
        $data_chart_sales_month = '';
        $data_chart_cost_month = '';
        $data_chart_buget_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_chart_sales_month .= (int) $a->Sales . ($no_sales_month == 11 ? '' : ', ');
            $data_chart_cost_month .= (int) $a->Cost . ($no_sales_month == 11 ? '' : ', ');
            $data_chart_buget_month .= (int) $a->Budget . ($no_sales_month == 11 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_chart_sales_month' => $data_chart_sales_month,
            'data_chart_cost_month' => $data_chart_cost_month,
            'data_chart_budget_month' => $data_chart_buget_month,
        ];
        return response()->json($responseData);
    }

    public function get_profit_cust_yearly($year)
    {
        $resultsX = DB::connection('sqlsrv5')->table('ProfitYearlyByCust as a')->orderBy('Sales', 'desc')->where('ShipYear', $year)->get();
        $data_cust_sales_year = '';
        $data_cust_cost_year = '';
        $data_cust_budget_year = '';
        $no_sales_year = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_year .= (int) $a->Sales . ($no_sales_year == 8 ? '' : ', ');
            $data_cust_cost_year .= (int) $a->Cost . ($no_sales_year == 8 ? '' : ', ');
            $data_cust_budget_year .= (int) $a->Budget . ($no_sales_year == 8 ? '' : ', ');
            $no_sales_year++;
        }

        $responseData = [
            'data_cust_sales_year' => $data_cust_sales_year,
            'data_cust_cost_year' => $data_cust_cost_year,
            'data_cust_budget_year' => $data_cust_budget_year,
        ];
        return response()->json($responseData);
    }

    public function get_profit_cust_monthly($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')->table('SalesProfitMonthlyByCust as a')->orderBy('ShipMonth', 'asc')->where('ShipYear', $year)->where('ShipMonth', $month)->orderBy('Sales', 'desc')->get();
        $data_cust_sales_month = '';
        $data_cust_cost_month = '';
        $data_cust_budget_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_month .= (int) $a->Sales . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_cost_month .= (int) $a->Cost . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_budget_month .= (int) $a->Budget . ($no_sales_month == 8 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_cust_sales_month' => $data_cust_sales_month,
            'data_cust_cost_month' => $data_cust_cost_month,
            'data_cust_budget_month' => $data_cust_budget_month,
        ];
        return response()->json($responseData);
    }

    public function get_profit_cust_date($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')
            ->table('SalesProfitByDay as a')
            ->select(
                'ShipYear',
                'ShipMonth',
                'ShipDay',
                DB::raw('SUM(Sales) as Total_Sales'),
                DB::raw('SUM(Cost) as Total_Cost')
            )
            ->where('ShipYear', $year)
            ->where('ShipMonth', $month)
            ->groupBy('ShipYear', 'ShipMonth', 'ShipDay')
            ->orderBy('ShipMonth', 'asc')
            ->orderBy('ShipDay', 'asc')
            ->get();



        $total_data = $resultsX->count() - 1;
        $data_cust_sales_date = '';
        $data_cust_cost_date = '';
        $data_val_date = '';
        $no_sales_date = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_date .= (int) $a->Total_Sales . ($no_sales_date == $total_data ? '' : ', ');
            $data_cust_cost_date .= (int) $a->Total_Cost . ($no_sales_date == $total_data ? '' : ', ');
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

    public function get_sales_cost_table(Request $request)
    {
        $str_year = explode('-', $request->year);
        $year = (int) $str_year[0];
        $month = $str_year[1];
        // $cust = $str_year[2];

        // Query utama
        $db = DB::connection('sqlsrv5')
            ->table('SalesProfitByPartMonthly as a')
            ->where(function ($query) use ($year, $month) {
                $query->where('ShipYear', $year)
                    ->where('ShipMonth', $month)
                    ->where(function ($subQuery) {
                        $subQuery->where('Cost', '>', 0)
                            ->Where('Sales', '>', 0);
                    });
            })
            ->orderBy('ShipMonth', 'asc');

        // Total data sebelum filter
        $totalData = $db->count();

        // Parameter DataTables
        $search = $request->input('search.value'); // Perbaikan untuk DataTables search
        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'a.PartNum',
            1 => 'a.PartNum',
            2 => 'a.Sales',
            3 => 'a.Cost',
            4 => 'a.Profit',
            5 => 'a.Profit'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'a.PartNum';

        // Filter data jika ada pencarian
        if (!empty($search)) {
            $db->where(function ($query) use ($search) {
                $query->where('a.PartNum', 'LIKE', "%{$search}%")
                    ->orWhere('a.Sales', 'LIKE', "%{$search}%")
                    ->orWhere('a.Cost', 'LIKE', "%{$search}%")
                    ->orWhere('a.Profit', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data setelah filter
        $posts = $db->offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $totalFiltered = $db->count();

        // Format data untuk DataTables
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
                'part_no' => $post->PartNum,
                'sales' => number_format($post->Sales, 0),
                'cost' => number_format($post->Cost, 0),
                'profit' => number_format($post->Profit, 0),
                'status' => $status
            ];

            $data[] = $nestedData;
        }

        // Response JSON untuk DataTables
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }

    public function get_profit_invoice_yearly($year)
    {
        $str_year = explode('~', $year);
        $start = (int) $str_year[0];
        $end = $str_year[1] + 1;
        $results = DB::connection('sqlsrv5')->table('InvoiceProfitByYearly as a')->whereBetween('InvoiceYear', [$start, $end])->orderBy('InvoiceYear', 'asc')->get();
        $data_chart_sales = '';
        $data_chart_cost = '';
        $no_sales = 0;
        foreach ($results as $a) {
            $data_chart_sales .= (int) $a->Sales . ($no_sales == 5 ? '' : ', ');
            $data_chart_cost .= (int) $a->Cost . ($no_sales == 5 ? '' : ', ');
            $no_sales++;
        }

        $responseData = [
            'data_chart_sales' => $data_chart_sales,
            'data_chart_cost' => $data_chart_cost,
        ];
        return response()->json($responseData);
    }

    public function get_profit_invoice_cust_monthly($year)
    {
        $str_year = explode('-', $year);
        $year = (int) $str_year[0];
        $month = $str_year[1];

        $resultsX = DB::connection('sqlsrv5')->table('SalesInvoiceCustByMonth as a')->orderBy('InvoiceMonth', 'asc')->where('InvoiceYear', $year)->where('InvoiceMonth', $month)->orderBy('Sales', 'desc')->get();
        $data_cust_sales_month = '';
        $data_cust_cost_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_month .= (int) $a->Sales . ($no_sales_month == 8 ? '' : ', ');
            $data_cust_cost_month .= (int) $a->Cost . ($no_sales_month == 8 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_cust_sales_month' => $data_cust_sales_month,
            'data_cust_cost_month' => $data_cust_cost_month,
        ];
        return response()->json($responseData);
    }

    public function get_profit_invoice_cust_yearly($year)
    {
        $resultsX = DB::connection('sqlsrv5')->table('InvoiceProfitCustByYearly as a')->orderBy('Sales', 'desc')->where('InvoiceYear', $year)->get();
        $data_cust_sales_year = '';
        $data_cust_cost_year = '';
        $no_sales_year = 0;
        foreach ($resultsX as $a) {
            $data_cust_sales_year .= (int) $a->Sales . ($no_sales_year == 8 ? '' : ', ');
            $data_cust_cost_year .= (int) $a->Cost . ($no_sales_year == 8 ? '' : ', ');
            $no_sales_year++;
        }

        $responseData = [
            'data_cust_sales_year' => $data_cust_sales_year,
            'data_cust_cost_year' => $data_cust_cost_year,
        ];
        return response()->json($responseData);
    }

    public function get_invoice_profit_monthly($year)
    {
        $resultsX = DB::connection('sqlsrv5')->table('InvoiceProfitByMonth as a')->orderBy('InvoiceMonth', 'asc')->where('InvoiceYear', $year)->get();
        $data_chart_sales_month = '';
        $data_chart_cost_month = '';
        $no_sales_month = 0;
        foreach ($resultsX as $a) {
            $data_chart_sales_month .= (int) $a->Sales . ($no_sales_month == 11 ? '' : ', ');
            $data_chart_cost_month .= (int) $a->Cost . ($no_sales_month == 11 ? '' : ', ');
            $no_sales_month++;
        }

        $responseData = [
            'data_chart_sales_month' => $data_chart_sales_month,
            'data_chart_cost_month' => $data_chart_cost_month,
        ];
        return response()->json($responseData);
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

        // Query utama
        $db = DB::connection('sqlsrv5')
            ->table('InvoiceProfitByPartMonthly as a')
            ->where(function ($query) use ($year, $month) {
                $query->where('InvoiceYear', $year)
                    ->where('InvoiceMonth', $month)
                    ->where(function ($subQuery) {
                        $subQuery->where('Cost', '>', 0)
                            ->Where('Sales', '>', 0);
                    });
            })
            ->orderBy('InvoiceMonth', 'asc');

        $totalData = $db->count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir', 'asc');
        $search = $request->input('search.value');

        $columns = [
            0 => 'a.PartNum',
            1 => 'a.PartNum',
            2 => 'a.Sales',
            3 => 'a.Cost',
            4 => 'a.Profit',
            5 => 'a.Profit'
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'a.PartNum';

        if (!empty($search)) {
            $db->where(function ($query) use ($search) {
                $query->where('a.PartNum', 'LIKE', "%{$search}%")
                    ->orWhere('a.Sales', 'LIKE', "%{$search}%")
                    ->orWhere('a.Cost', 'LIKE', "%{$search}%")
                    ->orWhere('a.Profit', 'LIKE', "%{$search}%");
            });
        }

        $posts = $db->offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $orderDirection)
            ->get();

        $totalFiltered = $db->count();
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
                'sales' => (int) $post->Sales,
                'cost' => (int) $post->Cost,
                'profit' => (int) $post->Profit,
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
}
