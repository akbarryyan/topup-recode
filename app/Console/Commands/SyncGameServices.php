<?php

namespace App\Console\Commands;

use App\Models\GameService;
use App\Services\VipResellerService;
use Illuminate\Console\Command;

class SyncGameServices extends Command
{
    protected $signature = 'services:sync {--game= : Filter by game name}';
    protected $description = 'Sync game services from VIP Reseller API';

    public function handle(VipResellerService $vipResellerService)
    {
        $this->info('Starting sync from VIP Reseller API...');
        $this->newLine();

        $filterGame = $this->option('game');
        
        if ($filterGame) {
            $this->info("Filter: {$filterGame}");
        } else {
            $this->info("Filter: All games");
        }
        
        $this->newLine();

        $response = $filterGame 
            ? $vipResellerService->getServicesByGame($filterGame)
            : $vipResellerService->getGameServices();

        if (!$response['success']) {
            $this->error('✗ Sync failed!');
            $this->error('Message: ' . $response['message']);
            return 1;
        }

        $totalData = count($response['data']);
        $this->info("✓ API Response received: {$totalData} services");
        $this->newLine();

        if ($totalData === 0) {
            $this->warn('No services to sync.');
            return 0;
        }

        $synced = 0;
        $updated = 0;

        $bar = $this->output->createProgressBar($totalData);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %message%');
        $bar->setMessage('Starting...');
        $bar->start();

        foreach ($response['data'] as $service) {
            $bar->setMessage("Processing: {$service['game']} - {$service['name']}");
            
            $gameService = GameService::updateOrCreate(
                ['code' => $service['code']],
                [
                    'game' => $service['game'],
                    'name' => $service['name'],
                    'price_basic' => $service['price']['basic'],
                    'price_premium' => $service['price']['premium'],
                    'price_special' => $service['price']['special'],
                    'server' => $service['server'],
                    'status' => $service['status'],
                ]
            );

            if ($gameService->wasRecentlyCreated) {
                $synced++;
            } else {
                $updated++;
            }

            $bar->advance();
        }

        $bar->setMessage('Completed!');
        $bar->finish();
        
        $this->newLine(2);
        $this->info('✓ Sync completed successfully!');
        $this->newLine();
        
        $this->table(
            ['Type', 'Count'],
            [
                ['New services added', $synced],
                ['Existing services updated', $updated],
                ['Total processed', $totalData],
            ]
        );

        return 0;
    }
}
