<?php

namespace Database\Seeders;

use App\Models\VipResellerSetting;
use Illuminate\Database\Seeder;

class VipResellerSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VipResellerSetting::updateOrCreate(
            ['id' => 1],
            [
                'api_url' => env('VIP_RESELLER_API_URL', 'https://vip-reseller.co.id/api'),
                'api_id' => env('VIP_RESELLER_API_ID'),
                'api_key' => env('VIP_RESELLER_API_KEY'),
                'sign' => env('VIP_RESELLER_SIGN'),
                'notes' => 'Auto imported from .env during seeding. Perbarui via admin panel.',
                'is_active' => true,
            ]
        );
    }
}
