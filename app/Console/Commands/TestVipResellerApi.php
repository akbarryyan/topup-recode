<?php

namespace App\Console\Commands;

use App\Services\VipResellerService;
use Illuminate\Console\Command;

class TestVipResellerApi extends Command
{
    protected $signature = 'test:vip-api';
    protected $description = 'Test VIP Reseller API connection';

    public function handle(VipResellerService $service)
    {
        $this->info('Testing VIP Reseller API...');
        $this->newLine();

        $response = $service->getGameServices();

        if ($response['success']) {
            $this->info('✓ API connection successful!');
            $this->info('Message: ' . $response['message']);
            $this->info('Total services: ' . count($response['data']));
            
            if (count($response['data']) > 0) {
                $this->newLine();
                $this->info('Sample data (first 3 services):');
                
                foreach (array_slice($response['data'], 0, 3) as $index => $service) {
                    $this->line(($index + 1) . '. ' . $service['game'] . ' - ' . $service['name']);
                    $this->line('   Code: ' . $service['code']);
                    $this->line('   Price: Rp ' . number_format($service['price']['basic'], 0, ',', '.'));
                    $this->line('   Status: ' . $service['status']);
                    $this->newLine();
                }
            }
        } else {
            $this->error('✗ API connection failed!');
            $this->error('Message: ' . $response['message']);
        }

        return 0;
    }
}
