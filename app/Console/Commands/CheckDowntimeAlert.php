<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsappJob;
use App\Models\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckDowntimeAlert extends Command
{
    protected $config;
    public function __construct(Config $config)
    {
        parent::__construct();

        $this->config = $config;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-downtime-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $downtime_data = DB::table('log_downtime as a')
            ->leftJoin('log_header_machine as b', 'a.machine_id', '=', 'b.machine_id')
            ->leftJoin('downtime_list as c', 'a.downtime_id', '=', 'c.id')
            ->where('a.is_active', true)
            ->select('a.*', 'b.category_line_id', 'c.name as downtime_name', 'c.type as downtime_type')
            ->get();

        foreach ($downtime_data as $data) {

            $diff_in_minutes = \Carbon\Carbon::parse($data->started_at)
                ->diffInMinutes(now());

            $machine = 'ALL';

            /*
            |--------------------------------------------------------------------------
            | LINE & MACHINE
            |--------------------------------------------------------------------------
            */

            if (str_contains(strtoupper($data->category_line_id), 'ASSY')) {

                $line = 'ASSY';

                if (str_contains(strtoupper($data->machine_id), 'RSW')) {
                    $machine = 'RSW';
                }

                if (str_contains(strtoupper($data->machine_id), 'SSW-B')) {
                    $machine = 'SSW-B';
                }

            } else {

                $line = 'STP';

                if (str_contains(strtoupper($data->machine_id), 'A6')) {
                    $machine = 'A6';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5 MENIT
            |--------------------------------------------------------------------------
            */

            if ($diff_in_minutes >= 5 && !$data->notif_5m) {

                $employees = $this->config->employee_data(
                    $line,
                    $machine,
                    $data->downtime_type,
                    6
                );

                foreach ($employees as $emp) {

                    $this->sendWhatsapp($emp, $data, 5);
                }

                DB::table('log_downtime')
                    ->where('seq_id', $data->seq_id)
                    ->update([
                        'notif_5m' => 1
                    ]);
            }
            if ($diff_in_minutes >= 7 && !$data->notif_7m) {

                $employees = $this->config->employee_data(
                    $line,
                    $machine,
                    $data->downtime_type,
                    6
                );

                foreach ($employees as $emp) {

                    $this->sendRemindWhatsapp($emp, $data, 7);
                }

                DB::table('log_downtime')
                    ->where('seq_id', $data->seq_id)
                    ->update([
                        'notif_7m' => 1
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 10 MENIT
            |--------------------------------------------------------------------------
            */

            // if ($diff_in_minutes >= 10 && !$data->notif_10m) {

            //     $employees = $this->config->employee_data(
            //         $line,
            //         $machine,
            //         $data->downtime_type,
            //         3
            //     );

            //     foreach ($employees as $emp) {

            //         $this->sendWhatsapp($emp, $data, 10);
            //     }

            //     DB::table('log_downtime')
            //         ->where('seq_id', $data->seq_id)
            //         ->update([
            //             'notif_10m' => 1
            //         ]);
            // }
            // if ($diff_in_minutes >= 13 && !$data->notif_13m) {

            //     $employees = $this->config->employee_data(
            //         $line,
            //         $machine,
            //         $data->downtime_type,
            //         3
            //     );

            //     foreach ($employees as $emp) {

            //         $this->sendRemindWhatsapp($emp, $data, 13);
            //     }

            //     DB::table('log_downtime')
            //         ->where('seq_id', $data->seq_id)
            //         ->update([
            //             'notif_13m' => 1
            //         ]);
            // }
            /*
            |--------------------------------------------------------------------------
            | 15 MENIT
            |--------------------------------------------------------------------------
            */

            // if ($diff_in_minutes >= 15 && !$data->notif_15m) {

            //     $employees = $this->config->employee_data(
            //         $line,
            //         $machine,
            //         $data->downtime_type,
            //         4
            //     );

            //     foreach ($employees as $emp) {

            //         $this->sendWhatsapp($emp, $data, 15);
            //     }

            //     DB::table('log_downtime')
            //         ->where('seq_id', $data->seq_id)
            //         ->update([
            //             'notif_15m' => 1
            //         ]);
            // }
            // if ($diff_in_minutes >= 20 && !$data->notif_20m) {

            //     $employees = $this->config->employee_data(
            //         $line,
            //         $machine,
            //         $data->downtime_type,
            //         4
            //     );

            //     foreach ($employees as $emp) {

            //         $this->sendRemindWhatsapp($emp, $data, 20);
            //     }

            //     DB::table('log_downtime')
            //         ->where('seq_id', $data->seq_id)
            //         ->update([
            //             'notif_20m' => 1
            //         ]);
            // }
            /*
            |--------------------------------------------------------------------------
            | 30 MENIT
            |--------------------------------------------------------------------------
            */

            // if ($diff_in_minutes >= 30 && !$data->notif_30m) {

            //     $employees = $this->config->employee_data(
            //         $line,
            //         $machine,
            //         $data->downtime_type,
            //         5
            //     );

            //     foreach ($employees as $emp) {

            //         $this->sendWhatsapp($emp, $data, 30);
            //     }

            //     DB::table('log_downtime')
            //         ->where('seq_id', $data->seq_id)
            //         ->update([
            //             'notif_30m' => 1
            //         ]);
            // }
        }
    }
    private function sendWhatsapp($emp, $data, $minutes)
    {
        try {
            $message = "*[ESCALATION DOWNTIME {$minutes} MENIT]*

Dear Bapak/Ibu,
Downtime telah berlangsung lebih dari {$minutes} menit dan memerlukan perhatian segera.
Machine  : {$data->machine_id}
Downtime : {$data->downtime_name}
Start    : " . \Carbon\Carbon::parse($data->started_at)->format('Y-m-d H:i:s') . "
Duration : {$minutes} Menit+

Mohon segera dilakukan pengecekan dan tindak lanjut untuk meminimalisir impact terhadap produksi.

Terima kasih.";
            SendWhatsappJob::dispatch(
                $emp->Telp,
                $message
            );
        } catch (\Throwable $th) {
            Log::error('Failed to send WhatsApp message', [
                'phone' => $emp->Telp,
                'message' => $message,
                'response' => $th->getMessage()
            ]);
        }
    }
    private function sendRemindWhatsapp($emp, $data, $minutes)
    {
        try {
            $message = "*[REMINDER DOWNTIME {$minutes} MENIT]*

Dear Bapak/Ibu,
Downtime telah berlangsung lebih dari {$minutes} menit dan memerlukan perhatian segera.
Machine  : {$data->machine_id}
Downtime : {$data->downtime_name}
Start    : " . \Carbon\Carbon::parse($data->started_at)->format('Y-m-d H:i:s') . "
Duration : {$minutes} Menit+

Mohon segera dilakukan pengecekan dan tindak lanjut untuk meminimalisir impact terhadap produksi.

Terima kasih.";

            SendWhatsappJob::dispatch(
                $emp->Telp,
                $message
            );
        } catch (\Throwable $th) {
            Log::error('Failed to send WhatsApp message', [
                'phone' => $emp->Telp,
                'message' => $message,
                'response' => $th->getMessage()
            ]);
        }
    }
}
