<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'website_name', 'value' => 'NVD STORE', 'type' => 'text'],
            ['key' => 'website_description', 'value' => 'Top Up Games Favoritmu di NVDSTORE! Tersedia Berbagai Macam Produk, Layanan & Pembayaran yang bisa digunakan. Proses Otomatis, Aman, & Pastinya Terjangkau.', 'type' => 'textarea'],
            ['key' => 'website_phone', 'value' => '6282227113307', 'type' => 'text'],
            ['key' => 'website_address', 'value' => 'Medan Sunggal, Kota Medan, Sumatera Utara 20122', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
