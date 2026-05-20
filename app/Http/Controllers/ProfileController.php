<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LogMachine;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProfileController extends Controller
{
    protected $profile;
    protected $LogMachine;
    public function __construct(Profile $profile, LogMachine $LogMachine)
    {
        $this->profile = $profile;
        $this->LogMachine = $LogMachine;
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function main(Request $request)
    {
        try {
            $emp_id = $request->employeeID;
            $machineID = $request->machineID;
            $production_date = $request->production_date;
            if ($emp_id) {
                $data = $this->profile->main_dashboard_by_emp($emp_id);
                $activity = $this->profile->activity($emp_id);
                $act_summary = $this->profile->act_summary($emp_id, $production_date);
                $oee_current = $this->profile->oee_current($emp_id);
            } else if ($machineID) {
                $data = $this->profile->main_dashboard_by_machine($machineID);
                $activity = $this->profile->activity_by_machine($machineID);
                $act_summary = $this->profile->act_summary_by_machine($machineID, $production_date);
                $oee_current = $this->profile->oee_current_by_machine($machineID);
            }
            // dd($data);
            // if ($data->tool_id) {
            //     $option_tool = $this->profile->option_tool($emp_id);
            // } else {
            //     $option_tool = null;
            // }
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil di ambil',
                'data' => $data,
                'activity' => $activity,
                'act_summary' => $act_summary,
                'oee_current' => $oee_current,
                // 'option_tool' => $option_tool
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function main_dt(Request $request)
    {
        try {
            $emp_id = $request->empId;
            $machineID = $request->machineID;
            if ($emp_id) {
                $data = $this->profile->main_dashboard_by_emp($emp_id);
            } else if ($machineID) {
                $data = $this->profile->main_dashboard_by_machine($machineID);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter employee atau machine wajib diisi'
                ], 400);
            }
            $tool_id = $data->tool_id ?? null;
            return response()->json([
                'dt_log' => $this->profile->dt_log($data->machine_id, $tool_id, $data->job_num, $data->shift, $data->production_date)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function progress_bar(Request $request)
    {
        try {
            $empID = $request->employeeID;
            $machineID = $request->machineID;
            if ($empID) {
                $machine = $this->profile->machine_data_by_emp($empID);
            } else if ($machineID) {
                $machine = $this->profile->machine_data_by_machine($machineID);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil di ambil',
                'data' => $machine
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        $user = $request->employeeID;
        $data = DB::table('users')
            ->where('id', $user)
            ->first();
        if ($request->hasFile('photo')) {
            if ($data->avatar && file_exists(public_path('uploads/' . $data->avatar))) {
                unlink(public_path('uploads/' . $data->avatar));
            }

            $file = $request->file('photo');
            $filename = $user . '_' . time() . '_' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            DB::table('users')
                ->where('id', $user)
                ->update([
                    'avatar' => $filename
                ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
    public function view_more(Request $request)
    {
        $id = $request->machine_id;
        date_default_timezone_set('Asia/Jakarta');
        $date_sql = date('Y-m-d');
        $machine = LogMachine::find($id);

        if (!$machine) {
            return response()->json([
                'oee_availability' => 0,
                'oee_performance' => 0,
                'oee_quality' => 0
            ]);
        }

        $downtimes = DB::table('log_downtime')
            ->where('machine_id', $id)
            ->where('job_num', $machine->job_num)
            ->whereDate('production_date', $date_sql)
            ->where('shift', $machine->shift)
            ->get();

        $totalDowntimeMinutes = $downtimes->sum('downtime');

        $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
            ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
            : 100;

        $started_at = Carbon::parse($machine->started_at);
        $finished_at = Carbon::now('Asia/Jakarta');

        $operation_time = $finished_at->timestamp - $started_at->timestamp;

        $operasi_time = $operation_time / 60;
        $waktuOperasi = $operasi_time - $totalDowntimeMinutes;

        if ($waktuOperasi <= 0) {
            $oee_performance = 0;
            $oee_availability = 0;
        } else {

            $standardCT = 60 / $machine->standard_sph;

            $oee_performance = ($standardCT * $machine->qty_actual / $waktuOperasi) * 100;
            $oee_availability = ($waktuOperasi / $operasi_time) * 100;
        }
        $oee_average = (
            $oee_availability +
            $oee_performance +
            $oee_quality
        ) / 3;
        return response()->json([
            'oee_availability' => min(100, round($oee_availability, 2)),
            'oee_performance' => min(100, round($oee_performance, 2)),
            'oee_quality' => min(100, round($oee_quality, 2)),
            'percentage_rata_rata' => min(100, round($oee_average, 2))
        ]);
    }
    public function main_gsph(Request $request)
    {
        $employee_id = $request->employee;
        $machineID = $request->machineID;

        if ($employee_id) {
            $main_gsph = $this->profile->main_gsph_by_employee($employee_id);
        } elseif ($machineID) {
            $main_gsph = $this->profile->main_gsph_by_machine($machineID);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter employee atau machine wajib diisi'
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'data' => $main_gsph
        ]);
    }
    public function jo_show(Request $request)
    {
        $data = $this->profile->show_jo($request->machine, $request->date);
        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function change_jo(Request $request)
    {
        $data = $this->profile->change_jo($request->jo);
        return response()->json($data);
    }
    public function production_table(Request $request)
    {
        $id = 'SSW';
        $line_id = match ($id) {
            'A1' => 'STP-002',
            'A2' => 'STP-003',
            'A6' => 'STP-007',
            'RBT-5H45' => 'ASSY-002',
            'RBT-5J45' => 'ASSY-014',
            'SSW' => 'ASSY-009',
            default => null,
        };
        if ($line_id === null) {
            return response()->json([
                'error' => 'Invalid ID'
            ], 400);
        }
        if (!$request->ajax()) {
            abort(404);
        }
        // $machineIds = [
        //     'RSW-5H45-07',
        //     'RSW-5H45-08',
        //     'RSW-5H45-09',
        //     'RSW-5H45-10',
        //     'RSW-5H45-11',
        //     'RSW-5H45-12',
        // ];
        $query = $this->profile->queryTablePage($line_id);
        return DataTables::of($query)
            ->editColumn('job_num', function ($row) {
                if ($row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = $row->job_num;
                }
                return $data;
            })
            ->editColumn('part_no', function ($row) {
                if ($row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = $row->part_no;
                }
                return $data;
            })
            ->editColumn('qty_plan', function ($row) {
                return $row->qty_plan;
            })
            ->editColumn('qty_actual', function ($row) {
                return $row->qty_actual;
            })
            ->addColumn('qty_ok', function ($row) {
                return number_format($row->qty_ok, 0, '', '.') ?? '-';
            })
            ->editColumn('started_at', function ($row) {
                if (!$row->job_num || $row->qty_plan == 0) {
                    $data = '-';
                } else {
                    $data = Carbon::parse($row->started_at)->format('H:i:s');
                }
                return $data;
            })
            ->editColumn('finished_at', function ($row) use ($id) {
                if ($id === 'RBT-5H45') {
                    if ($row->status_finish = false || $row->qty_actual <= $row->qty_plan || !$row->started_at) {
                        $data = '-';
                    } else {
                        $data = Carbon::parse($row->finished_at)->format('H:i:s');
                    }
                } else {
                    if (!$row->started_at || $row->status_finish = false || $row->qty_actual <= $row->qty_plan) {
                        $data = '-';
                    } else {
                        $data = Carbon::parse($row->finished_at)->format('H:i:s');
                    }
                }

                return $data;
            })
            ->addColumn('action', function ($row) {
                return '
        <button
            class="btn btn-primary btn-sm"
            data-id="' . $row->machine_id . '"
        >
            View
        </button>
    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function dashboard_machine(Request $request)
    {
        $machine_id = $request->machineId;
        $tool_id = null;
        if (str_contains($machine_id, '~')) {
            [$machine_id, $tool_id] = explode('~', $machine_id, 2);
            $machine_id = trim($machine_id);
            $tool_id = trim($tool_id);
        }
        $machine = $this->profile->dashboard_machine($machine_id, $tool_id);
        if (!$machine) {
            return response()->json([
                'status' => 'error',
                'message' => 'machine tidak ditemukan'
            ], 404);
        }
        $gsph = $this->profile->dashboard_gsph_by_machine($machine_id, $machine->job_num, $tool_id);
        $oee = $this->profile->dashboard_oee_by_machine($machine_id, $machine->job_num, $tool_id);
        $oee_quality = (($oee->total_qty > 0 && $oee->total_ng > 0) ? 100 - ceil($oee->total_ng / $oee->total_qty * 100) : 100);
        $oee_availability = ($oee->available_time > 0 ? ceil($oee->operation_time / ($oee->available_time) * 100) : 0);
        $oee_performance = (($oee->operation_time_standard > 0 && $oee->operation_time > 0) ? ceil($oee->operation_time_standard / $oee->operation_time * 100) : 0);
        $oee_average = ($oee_availability + $oee_performance + $oee_quality) / 3;
        $activity = $this->profile->dashboard_activity_by_machine($machine_id, $machine->job_num, $tool_id);
        $next_schedule = $this->profile->dashboard_next_schedule($machine_id);
        return response()->json([
            'status' => 'success',
            'machine' => $machine,
            'gsph' => $gsph,
            'oee_average' => $oee_average,
            'oee_availability' => $oee_availability,
            'oee_performance' => $oee_performance,
            'oee_quality' => $oee_quality,
            'activity' => $activity,
            'next_schedule' => $next_schedule
        ]);
    }
}
