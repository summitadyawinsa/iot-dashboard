<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\search;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'log_header_machine';
    protected $primaryKey = 'machine_id';
    protected $fillable = ['total_stroke', 'qty_actual'];
    public function PartList()
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.Part')
            ->select('PartNum')
            ->where('InActive', 0)
            ->get();
    }
    public function PartAFList()
    {
        return DB::table('Part')
            ->select('PartNum')
            ->distinct()->get();
    }
    public function PartOneLesson()
    {
        return DB::table('Part');
    }
    public function CreatePart($data, $PartRelation)
    {
        DB::table('Part')
            ->insert($data);
        if (!empty($PartRelation)) {
            DB::table('PartPivot')
                ->insert([
                    'PartNum' => $data['PartNum'],
                    'PartNumSec' => $PartRelation
                ]);
        }
        return true;
    }
    public function DetailPart($PartNum)
    {
        return DB::connection('sqlsrv4')
            ->table('Erp.Part as a')
            ->leftJoin('Erp.PartRev as b', 'b.PartNum', '=', 'a.PartNum')
            ->select('a.PartDescription', 'b.RevisionNum')
            ->where('a.PartNum', $PartNum)
            ->first();
    }
    public function cardPartList($search)
    {
        $query = DB::table('Part as a')
            ->leftJoin('PartPivot as b', function ($join) {
                $join->on('b.PartNum', '=', 'a.PartNum')
                    ->orOn('b.PartNumSec', '=', 'a.PartNum');
            })
            ->select(
                'a.id',
                'a.PartNum',
                'a.PartName',
                'a.Model',
                'a.Photo',
                DB::raw("
                    STRING_AGG(
                    CASE
                    WHEN b.PartNum = a.PartNum THEN b.PartNumSec
                    WHEN b.PartNumSec = a.PartNum THEN b.PartNum
                    END, ','
                    ) as related_parts
                    ")
            )
            ->groupBy('a.id', 'a.PartNum', 'a.PartName', 'a.Model', 'a.Photo')
            ->orderBy('related_parts')
            ->orderBy('a.PartNum');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('a.PartNum', $search)
                    ->orWhere('b.PartNum', $search)
                    ->orWhere('b.PartNumSec', $search);
            });
        } else {
            $query->limit(10);
        }
        $data = $query->get();
        $data->transform(function ($item) {
            $item->id = Crypt::encryptString($item->id);
            return $item;
        });
        return $data;

    }

    public function CheckRelatedParm($PartRelation)
    {
        return DB::table('Part')->where('PartNum', $PartRelation)->exists();
    }
    public function PartRelation()
    {
        return DB::table('Part')->select('PartNum')->distinct()->orderByDesc('PartNum')->get();
    }
    public function QR($id)
    {
        return DB::table('Part as a')
            ->leftJoin('PartPivot as b', 'a.PartNum', '=', 'b.PartNum')
            ->select('a.PartNum', 'a.PartName')
            ->where('a.id', $id)->first();
    }
}
