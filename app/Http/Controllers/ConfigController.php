<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ConfigController extends Controller
{
    protected $config;
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    public function setup(Request $request)
    {
        $machines = $request->input('machine');
        $production_date = $request->production_date;
        $customer = $request->customer;
        $job_num = $request->job_num;
        if (empty($machines) || !is_array($machines)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ], 422);
        }
        $db_job_num = DB::table("f_select_schedule_epicor('$job_num')")
            ->select('qty_plan', 'shift', 'item_no')
            ->get();
        if ($db_job_num->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Machine data is invalid'
            ]);
        }
        if ($db_job_num->isEmpty()) {
            return response()->json(
                [
                    'job_num' => $job_num,
                    'status' => false,
                    'message' => 'Data machine tidak ditemukan atau OFF',
                ],
                404,
            );
        }
        foreach ($db_job_num as $row) {
            $qty_plan = $row->qty_plan;
            $part_no = $row->item_no;
            $qty_actual = 0;
        }
        DB::beginTransaction();
        try {
            foreach ($machines as $machine) {
                $emp = explode('~', $machine['employee_id']);
                $data_update = [
                    'started_at' => date('Y-m-d H:i:s'),
                    'job_num' => $job_num,
                    'standard_sph' => $machine['std_jph'],
                    'production_date' => $production_date,
                    'customer' => $customer,
                    'condition_id' => 1,
                    'is_active' => 1,
                    'employee_id' => $emp[0],
                    'employee_name' => $emp[1],
                    'qty_plan' => $qty_plan,
                    'part_no' => $part_no
                ];
                $jo_lanjutan = $this->config->setup_jo_lanjutan($machine['machine_id'], $job_num);
                if ($jo_lanjutan) {
                    $data_update['qty_actual'] = $jo_lanjutan->qty_actual;
                } else {
                    $data_update['qty_actual'] = 0;
                }
                $this->config->update_setup($machine['machine_id'], $data_update);
                Http::withoutVerifying()->post(
                    'https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update',
                    [
                        "machine_id" => $machine['machine_id'],
                        "condition_id" => 1,
                        "job_num" => $job_num
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Machine setup successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function shift_get_all(Request $request)
    {
        $category = $request->category;
        $data = $this->config->shift_get_all($category);
        return response()->json($data);
    }
    public function get_job_all(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $category = $request->category;
        $data = $this->config->get_job_all($search, $category);
        $total = $this->config->get_job_all_count($search, $category);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function get_downtime(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 30;
        $offset = ($page - 1) * $limit;
        $dept = $request->dept;

        $data = $this->config->get_downtime($search, $dept, $offset, $limit);
        $total = $this->config->get_downtime_all_count($search, $dept);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function get_machine(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');
        $jobNum = $request->input('job_num');
        $shift = $request->input('shift');
        $response = Http::withoutVerifying()
            ->post('https://192.168.1.251:8000/EPIAPI/JobEntry/GetDetailJob', [
                'ipJobNum' => $jobNum
            ]);
        $responseData = $response->json();
        if (!isset($responseData['data'])) {
            return response()->json([
                'message' => 'Data tidak ditemukan dalam response dari API JobEntry.',
                'raw_response' => $responseData
            ], 500);
        }
        $jobOpDtl = $responseData['data']['jobOpDtl'] ?? [];
        $machineIds = [];
        foreach ($jobOpDtl as $detail) {
            if (!empty($detail['resourceID'])) {
                $machineIds[] = $detail['resourceID'];
            }
        }
        // dd($jobOpDtl);
        $machineData = DB::table('log_header_machine')
            ->whereIn('machine_id', $machineIds)
            // ->where('condition_id', 1)
            ->get();
        return response()->json(array_merge(
            $responseData,
            [
                'machine' => $machineData
            ]
        ));
    }
    public function get_employee(Request $request)
    {
        $search = $request->search;
        $page = $request->page ?? 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;
        $data = $this->config->get_employee($search, $offset, $limit);
        $total = $this->config->get_employee_count($search);

        return response()->json([
            'results' => $data,
            'pagination' => [
                'more' => ($offset + $limit) < $total
            ]
        ]);
    }
    public function save_downtime(Request $request)
    {
        $id = $request->downtime['id'];
        $machine = $request->machine;
        try {
            $downtime_list = $this->config->downtime_list_id($id);
            $machine_data = $this->config->machine_data($machine);
            if (!$machine_data) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data machine tidak ditemukan'
                ]);
            }
            $insert = $this->config->insert_downtime([
                'machine_id' => $machine,
                'job_num' => $machine_data->job_num,
                'shift' => $machine_data->shift,
                'downtime_id' => $downtime_list->id,
                'started_at' => now('Asia/Jakarta'),
                'downtime' => 0,
                'is_active' => 1,
                'production_date' => $machine_data->production_date
            ]);
            $this->config->insert_activity([
                'activity' => $downtime_list->name,
                'machine_id' => $machine,
                'job_num' => $machine_data->job_num,
                'start_date' => now('Asia/Jakarta'),
                'downtime_seq_id' => $insert,
                'shift' => $machine_data->shift,
                'production_date' => $machine_data->production_date
            ]);
            Http::withoutVerifying()->post('https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update', [
                "machine_id" => $machine,
                "condition_id" => 2,
                "job_num" => $machine_data->job_num
            ]);
            Http::withoutVerifying()->post('https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/tickets/create', [
                "machine_id" => $machine,
                "title" => $downtime_list->name,
                "downtime_category" => $downtime_list->type,
                "impact_breakdown" => "Yes",
                "downtime_type" => "Unplanned"
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function stop_downtime(Request $request)
    {
        $machine = $request->machine;
        $job_num = $request->job_num;
        try {
            $machine_data = $this->config->machine_data($machine);
            $this->config->downtime_update($machine, $job_num, $machine_data->production_date);
            $this->config->activity_update($machine, $job_num, $machine_data->production_date);
            Http::withoutVerifying()->post('https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update', [
                "machine_id" => $machine,
                "condition_id" => 1,
                "job_num" => $machine_data->job_num
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Downtime berhasil di hentikan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function finish_machine(Request $request)
    {
        $machine = $request->machine;
        $job_num = $request->job_num;
        try {
            $data = [
                'machine_id' =>$machine,
                'part_no'=>null,
                'job_num'=>null,
                'qty_plan'=>0,
                'qty_actual'=>0,
                'qty_ng'=>0,
                'shift'=>null,
                'current_gsph'=>null,
                'finished_at'=>now('Asia/Jakarta'),
                'is_active'=>false,
                'status_finish'=>true,
                'customer'=>null,
                'employee_id'=>null,

            ];
            $this->config->update($machine,$data);
            Http::withoutVerifying()->post(
                'https://factoryhub.summitadyawinsa.co.id/factory-hub/api/v1/machine-status/update',
                [
                    "machine_id" => $machine,
                    "condition_id" => 1,
                    "job_num" => $job_num
                ]
            );
            return response()->json([
                'status'=>201
            ]);
        } catch (\Throwable $th) {
             return response()->json([
                'status'=>500,
                'message'=>$th->getMessage()
            ]);
        }
    }
}
