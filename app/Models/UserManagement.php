<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\Clock\now;

class UserManagement extends Model
{
    public function list()
    {
        return DB::table('users');
    }
    public function show_by_name($username, $email)
    {
        return DB::table('users')
            ->where('username', $username)
            ->orWhere('email', $email)
            ->exists();
    }
    public function emp_basic($name)
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.EmpBasic')
            ->where('Name', $name)
            ->first();
    }
    public function store_user($name, $username, $email, $password, $EmpID)
    {
        return DB::table('users')
            ->insert([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'employee_id' => $EmpID,
                'password' => Hash::make($password),
                'created_at' => now('Asia/Jakarta'),
                'updated_at' => now('Asia/Jakarta')
            ]);
    }
    public function find_by_id($id)
    {
        return DB::table('users')
            ->where('id', $id)
            ->first();
    }
    public function updateUser($epicor_id, $data)
    {
        return DB::table('users')->where('employee_id', $epicor_id)->update($data);
    }
    public function delete_user($id)
    {
        return DB::table('users')->where('id', $id)->delete();
    }
}
