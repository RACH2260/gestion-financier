<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Revenus
            ['name' => 'Ventes', 'type' => 'income', 'icon' => 'fa-shopping-cart', 'color' => '#10B981'],
            ['name' => 'Services', 'type' => 'income', 'icon' => 'fa-briefcase', 'color' => '#3B82F6'],
            ['name' => 'Investissements', 'type' => 'income', 'icon' => 'fa-chart-line', 'color' => '#8B5CF6'],
            ['name' => 'Prestations', 'type' => 'income', 'icon' => 'fa-handshake', 'color' => '#06B6D4'],

            // Dépenses
            ['name' => 'Fournitures', 'type' => 'expense', 'icon' => 'fa-box', 'color' => '#EF4444'],
            ['name' => 'Salaires', 'type' => 'expense', 'icon' => 'fa-users', 'color' => '#F59E0B'],
            ['name' => 'Loyer', 'type' => 'expense', 'icon' => 'fa-building', 'color' => '#EC489A'],
            ['name' => 'Électricité', 'type' => 'expense', 'icon' => 'fa-bolt', 'color' => '#F97316'],
            ['name' => 'Eau', 'type' => 'expense', 'icon' => 'fa-tint', 'color' => '#3B82F6'],
            ['name' => 'Internet', 'type' => 'expense', 'icon' => 'fa-wifi', 'color' => '#8B5CF6'],
            ['name' => 'Marketing', 'type' => 'expense', 'icon' => 'fa-ad', 'color' => '#6366F1'],
            ['name' => 'Transport', 'type' => 'expense', 'icon' => 'fa-truck', 'color' => '#14B8A6'],
            ['name' => 'Impôts', 'type' => 'expense', 'icon' => 'fa-file-invoice-dollar', 'color' => '#EF4444'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['user_id' => 1]));
        }
    }
}
