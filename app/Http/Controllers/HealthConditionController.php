<?php

namespace App\Http\Controllers;

use App\Models\HealthCondition;
use Illuminate\Http\Request;

class HealthConditionController extends Controller
{
    protected $HealthCondition;
    public function __construct(HealthCondition $HealthCondition)
    {
        $this->HealthCondition = $HealthCondition;
    }
    public function submit(Request $request)
    {
        try {
            $condition = [
                'employee' => $request->employee_selected,
                'physical_condition' => $request->physical_condition,
                'sleep_condition' => $request->sleep_condition,
                'medicine_condition' => $request->medicine_condition,
                'drug_condition' => $request->drug_condition,
                'mental_condition' => $request->mental_condition
            ];
            $yaCount = collect($condition)
                ->filter(fn($value) => strtolower($value) === 'ya')
                ->count();
            $condition['range_health'] = $yaCount;
            $this->HealthCondition->Store($condition);
            return response()->json([
                'status' => 'success',
                'message' => 'Data created successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}
