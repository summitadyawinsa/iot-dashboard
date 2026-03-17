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

class ProfileController extends Controller
{
    protected $profile;
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
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
        $emp_id = $request->employeeID;
        $production_date = $request->production_date;
        try {
            $data = $this->profile->main_dashboard($emp_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil di ambil',
                'data' => $data,
                'activity' => $this->profile->activity($emp_id),
                'act_summary' => $this->profile->act_summary($emp_id, $production_date),
                'oee_current' => $this->profile->oee_current($emp_id),
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
        $emp_id = $request->empId;
        try {
            $data = $this->profile->main_dashboard($emp_id);
            return response()->json([
                'dt_log' => $this->profile->dt_log($data->machine_id, $data->job_num, $data->shift, $data->production_date)
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
            $machine = $this->profile->machine_data($empID);
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
            $filename = time() . '_' . $file->getClientOriginalName();
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
        $main_gsph = $this->profile->main_gsph($employee_id);
        return response()->json([
            'status' => 'success',
            'data' => $main_gsph
        ]);
    }
}
