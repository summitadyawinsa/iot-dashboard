<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $message;

    /**
     * Retry 3x jika gagal
     */
    public $tries = 3;

    /**
     * Timeout queue
     */
    public $timeout = 30;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle(): void
    {
        try {

            $response = Http::acceptJson()
                ->timeout(15)
                ->post(config('services.ems_wa.url'), [
                    'phone' => $this->phone,
                    'message' => $this->message
                ]);

            $results = $response->json();

            if (
                !$response->successful() ||
                !isset($results['success']) ||
                $results['success'] != true
            ) {

                Log::error('Failed Send WhatsApp', [
                    'phone' => $this->phone,
                    'response' => $response->body()
                ]);

                throw new \Exception('API WA Failed');
            }

        } catch (\Throwable $th) {

            Log::error('Queue WhatsApp Error', [
                'phone' => $this->phone,
                'message' => $th->getMessage()
            ]);

            throw $th;
        }
    }
}
