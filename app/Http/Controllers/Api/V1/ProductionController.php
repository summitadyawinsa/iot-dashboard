<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\DB;
use DateTime;
use DataTables;

class ProductionController extends Controller
{
    public function get_production_achiev_Year($year)
    {
        $str_year = explode('~', $year);
        $Year = (int) $str_year[0];

        $lines = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'];

        $results = DB::connection('sqlsrv5')->table('ProductionAchiev')
            ->select(
                'ResourceGrpID',
                DB::raw('SUM(PartQty) as totalPartQty'),
                DB::raw('SUM(QtyComplete) as totalQtyComplete'),
                DB::raw('SUM(ReceivedQty) as totalReceivedQty')
            )
            ->where('Years', $Year)
            ->whereIn('ResourceGrpID', $lines)
            ->groupBy('ResourceGrpID')
            ->orderBy('ResourceGrpID', 'asc')
            ->get();

        $data = [];
        foreach ($lines as $line) {
            $data[$line] = [
                'PartQty' => 0,
                'QtyComplete' => 0,
                'ReceivedQty' => 0
            ];
        }

        foreach ($results as $row) {
            $data[$row->ResourceGrpID] = [
                'PartQty' => (int) $row->totalPartQty,
                'QtyComplete' => (int) $row->totalQtyComplete,
                'ReceivedQty' => (int) $row->totalReceivedQty
            ];
        }

        return response()->json(['data_chart' => $data]);
    }


    public function get_production_achiev_Month($year)
    {
        $str_year = explode('~', $year);
        $Year = (int) $str_year[0];
        $Line = isset($str_year[1]) ? trim($str_year[1]) : '';

        $data = DB::connection('sqlsrv5')->table('ProductionAchiev')
            ->select(
                'Months',
                'ResourceGrpID',
                DB::raw('SUM(PartQty) as totalPartQty'),
                DB::raw('SUM(QtyComplete) as totalQtyComplete'),
                DB::raw('SUM(ReceivedQty) as totalReceivedQty')
            )
            ->where('Years', $Year)
            ->where('ResourceGrpID', $Line)
            ->groupBy('Months', 'ResourceGrpID')
            ->orderBy('Months', 'asc')
            ->get();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $filtered = $data->firstWhere('Months', $m);

            $monthlyData[$m] = [
                'PartQty' => $filtered->totalPartQty ?? 0,
                'QtyComplete' => $filtered->totalQtyComplete ?? 0,
                'ReceivedQty' => $filtered->totalReceivedQty ?? 0
            ];
        }

        return response()->json(['data_monthly' => $monthlyData]);
    }

    public function get_production_achiev_Daily($date)
    {
        $str_date = explode('-', $date);

        $Year = (int) $str_date[0];
        $Month = (int) $str_date[1];
        $Day = (int) $str_date[2];

        $lines = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'D14', 'P9', 'A5', 'A6'];

        $results = DB::connection('sqlsrv5')->table('ProductionAchiev')
            ->select(
                'ResourceGrpID',
                DB::raw('SUM(PartQty) as totalPartQtyDaily'),
                DB::raw('SUM(QtyComplete) as totalQtyCompleteDaily'),
                DB::raw('SUM(ReceivedQty) as totalReceivedQtyDaily')
            )
            ->where('Years', $Year)
            ->where('Months', $Month)
            ->where('Days', $Day)
            ->whereIn('ResourceGrpID', $lines)
            ->groupBy('ResourceGrpID')
            ->orderBy('ResourceGrpID', 'asc')
            ->get();

        $data = [];
        foreach ($lines as $line) {
            $data[$line] = [
                'PartQty' => 0,
                'QtyComplete' => 0,
                'ReceivedQty' => 0
            ];
        }

        foreach ($results as $row) {
            $data[$row->ResourceGrpID] = [
                'PartQty' => (int) $row->totalPartQtyDaily,
                'QtyComplete' => (int) $row->totalQtyCompleteDaily,
                'ReceivedQty' => (int) $row->totalReceivedQtyDaily
            ];
        }

        return response()->json(['data_daily' => $data]);
    }

    public function get_production_achiev_Table(Request $request)
    {

        $date = $request->date;
        $line = $request->line;

        if (!$line || !$date) {
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
        }

        $dateParts = explode('-', $date);
        $year = (int) $dateParts[0];
        $month = (int) $dateParts[1];
        $day = (int) $dateParts[2];

        $query = DB::connection('sqlsrv5')->table('ProductionAchievPart')
            ->select('PartNum', 'PartQty', 'QtyComplete', 'ReceivedQty', 'ResourceGrpID')
            ->where('Years', $year)
            ->where('Months', $month)
            ->where('Days', $day)
            ->where('ResourceGrpID', $line);

        $totalData = $query->count();

        $search = $request->input('search.value');
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            0 => 'PartNum',
            1 => 'PartQty',
            2 => 'QtyComplete',
            3 => 'ReceivedQty'
        ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'PartNum';

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('PartNum', 'LIKE', "%{$search}%")
                    ->orWhere('PartQty', 'LIKE', "%{$search}%")
                    ->orWhere('QtyComplete', 'LIKE', "%{$search}%")
                    ->orWhere('ReceivedQty', 'LIKE', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $data = $query->offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $orderDirection)
            ->get()
            ->map(function ($item) {
                return [
                    'PartNum' => $item->PartNum,
                    'PartQty' => (int) $item->PartQty,
                    'QtyComplete' => (int) $item->QtyComplete,
                    'ReceivedQty' => (int) $item->ReceivedQty,
                    'ResourceGrpID' => $item->ResourceGrpID,
                ];
            });


        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ]);
    }
}
