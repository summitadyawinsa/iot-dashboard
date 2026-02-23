<?php

namespace App\Http\Controllers;

use App\Models\HeaderMachine;
use App\Models\HeaderMachineOld;
use App\Models\HeaderMachineSummary;
use App\Models\OeeLogMachine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    protected $oeeLogMachine;
    protected $headerMachine;
    public function __construct(OeeLogMachine $oeeLogMachine, HeaderMachine $headerMachine)
    {
        $this->oeeLogMachine = $oeeLogMachine;
        $this->headerMachine = $headerMachine;
    }
    public function stamping($id)
    {
        $headerMachine = $this->headerMachine->Show($id);
        if ($headerMachine) {
            $production_date = Carbon::now()->format('Y-m-d');
            $summaryMachine = HeaderMachineSummary::where('header_machine_id', $id)
                ->where('production_date', $production_date)
                ->where('shift', $headerMachine->shift)
                ->where('job_number', $headerMachine->job_number)
                ->get();
            $percentageSummary = ($summaryMachine->quantity_plan / $summaryMachine->quantity_actual) * 100;
            //Oee
            $oee = OeeLogMachine::where('header_machine_id', $id)
                ->where('production_date', $production_date)
                ->where('shift', $headerMachine->shift)
                ->where('job_number', $headerMachine->job_number)->get();
            $rata_rata_oee = ($oee->avability + $oee->performance + $oee->quality ?? 100) / 3;
            return response()->json([
                'percentageSummary' => $percentageSummary,
                'oee' => [
                    'avability' => $oee->avability,
                    'performance' => $oee->performance,
                    'quality' => $oee->quality,
                    'rata_rata' => $rata_rata_oee
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Mesin tidak ditemukan'
            ]);
        }

    }
    public function stampingOld(Request $request,$id)
    {
        $production_date = $request->production_date;
        $job_number = $request->job_number;
        $shift = $request->shift;
        $headerMachine = HeaderMachineOld::where('header_machine_id',$id)
        ->where('production_date',$production_date)
        ->where('shift',$shift)
        ->where('job_number',$job_number)
        ->get();
        if ($headerMachine) {
            $production_date = Carbon::now()->format('Y-m-d');
            $summaryMachine = HeaderMachineSummary::where('header_machine_id', $id)
                ->where('production_date', $production_date)
                ->where('shift', $headerMachine->shift)
                ->where('job_number', $headerMachine->job_number)
                ->get();
            $percentageSummary = ($summaryMachine->quantity_plan / $summaryMachine->quantity_actual) * 100;
            //Oee
            $oee = OeeLogMachine::where('header_machine_id', $id)
                ->where('production_date', $production_date)
                ->where('shift', $headerMachine->shift)
                ->where('job_number', $headerMachine->job_number)->get();
            $rata_rata_oee = ($oee->avability + $oee->performance + $oee->quality ?? 100) / 3;
            return response()->json([
                'percentageSummary' => $percentageSummary,
                'oee' => [
                    'avability' => $oee->avability,
                    'performance' => $oee->performance,
                    'quality' => $oee->quality,
                    'rata_rata' => $rata_rata_oee
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Mesin tidak ditemukan'
            ]);
        }

    }
}
