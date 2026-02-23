<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class HistoryLogMachineExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $lineID;

    public function __construct($id, $start, $end)
    {
        $this->lineID = $id;
        $this->startDate = $start;
        $this->endDate = $end;
    }

    public function collection()
    {
        if ($this->lineID == 'RBT-5H45') {
            $line = 'RSW-5H45';
        } else {
            $line = $this->lineID;
        }
        $machines = DB::table('history_header_machine as m')
            ->where('m.machine_id', 'like', $line . '%')
            ->whereBetween('m.production_date', [$this->startDate, $this->endDate])
            ->select(
                'm.machine_id',
                'm.line_id',
                'm.machine_name',
                'm.part_no',
                'm.job_num',
                'm.qty_plan',
                'm.qty_actual',
                'm.qty_ng',
                'm.shift',
                'm.standard_sph',
                'm.current_gsph',
                'm.started_at',
                'm.finished_at',
                'm.production_date',
                'm.employee_id',
                'm.employee_name'
            )
            ->get();

        $date_sql = date('Y-m-d');

        return $machines->map(function ($machine) use ($date_sql) {
            // Ambil downtime
            $downtimes = DB::table('log_downtime')
                ->where('machine_id', $machine->machine_id)
                ->where('job_num', $machine->job_num)
                ->whereDate('production_date', $machine->production_date)
                ->where('shift', $machine->shift)
                ->get();

            $totalDowntimeMinutes = $downtimes->sum('downtime');

            // Hitung OEE
            $oee_quality = ($machine->qty_actual > 0 && $machine->qty_ng > 0)
                ? 100 - ceil(($machine->qty_ng / $machine->qty_actual) * 100)
                : 100;

            $started_at = new \DateTime($machine->started_at);
            $finished_at = new \DateTime($machine->finished_at ?: date('Y-m-d H:i:s'));
            $operation_time = $finished_at->getTimestamp() - $started_at->getTimestamp();
            if ($operation_time <= 0) {
                $oee_performance = 0;
                $oee_availability = 0;
            } else {
                $operasi_time = $operation_time / 60 - $totalDowntimeMinutes;
                $standardCT = $machine->standard_sph > 0 ? 60 / $machine->standard_sph : 0;
                $oee_performance = $operasi_time > 0
                    ? $standardCT * $machine->qty_actual / $operasi_time * 100
                    : 0;
                $oee_availability = $operation_time > 0
                    ? $operasi_time / ($operation_time / 60) * 100
                    : 0;
            }
            $oee_total = ($oee_availability / 100) * ($oee_performance / 100) * ($oee_quality / 100) * 100;
            return [
                'machine_id' => $machine->machine_id,
                'line_id' => $machine->line_id,
                'machine_name' => $machine->machine_name,
                'part_no' => $machine->part_no,
                'job_num' => $machine->job_num,
                'qty_plan' => $machine->qty_plan,
                'qty_actual' => $machine->qty_actual,
                'qty_ng' => $machine->qty_ng,
                'shift' => $machine->shift,
                'standard_sph' => $machine->standard_sph,
                'current_gsph' => $machine->current_gsph,
                'started_at' => $machine->started_at,
                'finished_at' => $machine->finished_at,
                'production_date' => $machine->production_date,
                'employee_id' => $machine->employee_id,
                'employee_name' => $machine->employee_name,
                'oee_availability' => round($oee_availability, 2),
                'oee_performance' => round($oee_performance, 2),
                'oee_quality' => round($oee_quality, 2),
                'oee_total' => round($oee_total, 2)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Machine ID',
            'Line ID',
            'Machine Name',
            'Part Number',
            'Job Number',
            'Qty Plan',
            'Qty Actual',
            'Qty NG',
            'Shift',
            'Standard SPH',
            'Actual SPH',
            'Started',
            'Finished',
            'Production Date',
            'Employee ID',
            'Employee Name',
            'Availability (%)',
            'Performance (%)',
            'Quality (%)',
            'OEE (%)'
        ];
    }
}
