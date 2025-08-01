<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetShiprocketToken extends Command
{
    protected $signature = 'shiprocket:token';
    protected $description = 'Get Shiprocket API token';

    public function handle()
    {
        $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
            'email' => 'xandertorreot06@gmail.com',
            'password' => 'your_api_password',
        ]);
        $token = $response->json()['token'] ?? null;
        $this->info('Shiprocket Token: ' . $token);
    }
}
