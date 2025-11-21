<?php

namespace Database\Seeders;

use App\Models\GameAccountField;
use Illuminate\Database\Seeder;

class GameAccountFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                'game_name' => 'Mobile Legends',
                'field_key' => 'user_id',
                'label' => 'User ID',
                'placeholder' => 'Masukkan User ID',
                'input_type' => 'text',
                'is_required' => true,
                'helper_text' => 'Contoh: 12345678 (cek di profil kiri atas).',
                'sort_order' => 1,
            ],
            [
                'game_name' => 'Mobile Legends',
                'field_key' => 'zone_id',
                'label' => 'Zone ID',
                'placeholder' => 'Masukkan Zone ID',
                'input_type' => 'text',
                'is_required' => true,
                'helper_text' => 'Contoh: 1234',
                'sort_order' => 2,
            ],
            [
                'game_name' => 'Free Fire',
                'field_key' => 'user_id',
                'label' => 'User ID',
                'placeholder' => 'Masukkan User ID',
                'input_type' => 'text',
                'is_required' => true,
                'helper_text' => 'Buka profil > tekan ikon copy ID.',
                'sort_order' => 1,
            ],
        ];

        foreach ($fields as $field) {
            GameAccountField::updateOrCreate(
                [
                    'game_name' => $field['game_name'],
                    'field_key' => $field['field_key'],
                ],
                $field
            );
        }
    }
}
