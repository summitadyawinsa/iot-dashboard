<?php

namespace App\Http\Controllers;

use App\Models\HeaderMachine;
use App\Models\HeaderMachineOld;
use App\Models\LogActivity;
use App\Models\OeeLogMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
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
        $machine = $this->headerMachine->Show($id);
        if ($machine) {
            $production_date = Carbon::now()->format("Y-m-d");
            $shift = $machine->shift;
            $job_number = $machine->job_number;
            //Chart plan & actual achievement

            //GSPH Achievement
            //OEE
            $oee = OeeLogMachine::where("header_machine_id", $id)
                ->where('production_date', $production_date)
                ->where('shift', $shift)
                ->where('job_number', $job_number)->get();
            //Prod Downtime

            //Log activity
            $activity = LogActivity::where('header_machine_id', $id)
                ->where('production_date', $production_date)
                ->where('shift', $shift)
                ->where('job_number', $job_number)->get();
            return response()->json([
                'machine' => [
                    'job_number' => $machine->job_number,
                    'part_number' => $machine->part_number,
                    'start' => $machine->start,
                    'finish' => $machine->finish,
                    'standard_gsph' => $machine->standard_gsph,
                    'actual_gsph' => $machine->actual_gsph
                ],
                'oee' => [
                    'avability' => $oee->avability,
                    'performance' => $oee->performance,
                    'quality' => $oee->quality,
                ],
                'activity' => $activity
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Mesin tidak ditemukan'
            ], 404);
        }

    }

    public function assembly()
    {

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
            $oee = OeeLogMachine::where('header_machine_id',$id)
            ->where('production_date',$production_date)
            ->where('shift',$shift)
            ->where('job_number',$job_number)
            ->get();
            $activity = LogActivity::where('header_machine_id',$id)
            ->where('production_date',$production_date)
            ->where('shift',$shift)
            ->where('job_number',$job_number)
            ->get();
            return response()->json([
                'machine' => [
                    'job_number' => $headerMachine->job_number,
                    'part_number' => $headerMachine->part_number,
                    'start' => $headerMachine->start,
                    'finish' => $headerMachine->finish,
                    'standard_gsph' => $headerMachine->standard_gsph,
                    'actual_gsph' => $headerMachine->actual_gsph
                ],
                'oee' => [
                    'avability' => $oee->avability,
                    'performance' => $oee->performance,
                    'quality' => $oee->quality,
                ],
                'activity' => $activity
            ]);
        } else {
            return response()->json([
                'status'=>false,
                'message'=>'Mesin tidak ditemukan'
            ],404);
        }
        
    }
}
