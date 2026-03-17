<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    protected $userManagement;
    public function __construct(UserManagement $userManagement)
    {
        $this->userManagement = $userManagement;
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of($this->userManagement->list())
                ->addIndexColumn()
                ->addColumn('view', function ($row) {
                    $dtl = Crypt::encryptString($row->id);
                    return '
                    <div class="flex justify-arround">
                    <button class="btn btn-primary btn-sm mr-2" onclick="ViewBtn(\'' . $dtl . '\')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="DeleteBtn(\'' . $dtl . '\')">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    </button>
                    </div>
                    ';
                })
                ->rawColumns(['view'])
                ->make(true);
        }
    }
    public function store_user(Request $request)
    {
        $name = $request->name;
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $show_by_name = $this->userManagement->show_by_name($username, $email);
        if ($show_by_name) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Employee ID atau Email sudah digunakan'
            ]);
        }
        $emp = $this->userManagement->emp_basic(strtoupper($name));
        if (!$emp) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Nama tidak ada di epicor'
            ]);
        }
        $this->userManagement->store_user($name, $username, $email, $password, $emp->EmpID);
        return response()->json([
            'icon' => 'success',
            'title' => 'Success',
            'text' => 'Create User successfully'
        ]);
    }
    public function find_data(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        $data = $this->userManagement->find_by_id($id);
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
    public function update_user(Request $request)
    {
        $name = $request->name;
        $username = $request->username;
        $email = $request->email;
        $epicor_id = $request->epicor_id;
        try {
            if (!$epicor_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Epicor id tidak ditemukan'
                ]);
            }
            $this->userManagement->updateUser($epicor_id, [
                'name' => $name,
                'username' => $username,
                'email' => $email
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil di ubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function delete_user(Request $request)
    {
        $id = Crypt::decryptString($request->id);
        try {
            $this->userManagement->delete_user($id);
            return response()->json([
                'status' => 'error',
                'message' => 'Data berhasil di hapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
