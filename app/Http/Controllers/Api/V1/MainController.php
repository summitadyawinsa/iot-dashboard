<?php

namespace App\Http\Controllers;

use App\Models\HeaderMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MainController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table("log_header_machine")->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
        }
    }
}
