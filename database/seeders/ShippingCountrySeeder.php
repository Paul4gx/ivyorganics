<?php

namespace Database\Seeders;

use App\Models\ShippingCountry;
use App\Models\ShippingState;
use Illuminate\Database\Seeder;

class ShippingCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Nigeria (Domestic)
        $nigeria = ShippingCountry::create([
            'name' => 'Nigeria',
            'code' => 'NG',
            'default_shipping_fee' => 5000.00,
            'is_active' => true,
            'is_international' => false
        ]);

        // Add Nigerian states
        $nigerianStates = [
            ['name' => 'Lagos', 'code' => 'LA', 'shipping_fee' => 3000.00],
            ['name' => 'Abuja', 'code' => 'FC', 'shipping_fee' => 4000.00],
            ['name' => 'Kano', 'code' => 'KN', 'shipping_fee' => 6000.00],
            ['name' => 'Kaduna', 'code' => 'KD', 'shipping_fee' => 5500.00],
            ['name' => 'Rivers', 'code' => 'RI', 'shipping_fee' => 7000.00],
            ['name' => 'Ondo', 'code' => 'ON', 'shipping_fee' => 4500.00],
            ['name' => 'Oyo', 'code' => 'OY', 'shipping_fee' => 4000.00],
            ['name' => 'Edo', 'code' => 'ED', 'shipping_fee' => 5000.00],
            ['name' => 'Delta', 'code' => 'DE', 'shipping_fee' => 5500.00],
            ['name' => 'Imo', 'code' => 'IM', 'shipping_fee' => 6000.00],
        ];

        foreach ($nigerianStates as $state) {
            ShippingState::create([
                'shipping_country_id' => $nigeria->id,
                'name' => $state['name'],
                'code' => $state['code'],
                'shipping_fee' => $state['shipping_fee'],
                'is_active' => true
            ]);
        }

        // Create United States (International)
        $usa = ShippingCountry::create([
            'name' => 'United States',
            'code' => 'US',
            'default_shipping_fee' => 25.00,
            'is_active' => true,
            'is_international' => true
        ]);

        // Add some US states
        $usStates = [
            ['name' => 'California', 'code' => 'CA', 'shipping_fee' => 20.00],
            ['name' => 'New York', 'code' => 'NY', 'shipping_fee' => 22.00],
            ['name' => 'Texas', 'code' => 'TX', 'shipping_fee' => 18.00],
            ['name' => 'Florida', 'code' => 'FL', 'shipping_fee' => 21.00],
            ['name' => 'Illinois', 'code' => 'IL', 'shipping_fee' => 19.00],
        ];

        foreach ($usStates as $state) {
            ShippingState::create([
                'shipping_country_id' => $usa->id,
                'name' => $state['name'],
                'code' => $state['code'],
                'shipping_fee' => $state['shipping_fee'],
                'is_active' => true
            ]);
        }

        // Create United Kingdom (International)
        $uk = ShippingCountry::create([
            'name' => 'United Kingdom',
            'code' => 'GB',
            'default_shipping_fee' => 30.00,
            'is_active' => true,
            'is_international' => true
        ]);

        // Add some UK regions
        $ukRegions = [
            ['name' => 'England', 'code' => 'ENG', 'shipping_fee' => 28.00],
            ['name' => 'Scotland', 'code' => 'SCT', 'shipping_fee' => 32.00],
            ['name' => 'Wales', 'code' => 'WLS', 'shipping_fee' => 30.00],
            ['name' => 'Northern Ireland', 'code' => 'NIR', 'shipping_fee' => 35.00],
        ];

        foreach ($ukRegions as $region) {
            ShippingState::create([
                'shipping_country_id' => $uk->id,
                'name' => $region['name'],
                'code' => $region['code'],
                'shipping_fee' => $region['shipping_fee'],
                'is_active' => true
            ]);
        }

        // Create Canada (International)
        $canada = ShippingCountry::create([
            'name' => 'Canada',
            'code' => 'CA',
            'default_shipping_fee' => 35.00,
            'is_active' => true,
            'is_international' => true
        ]);

        // Add some Canadian provinces
        $canadianProvinces = [
            ['name' => 'Ontario', 'code' => 'ON', 'shipping_fee' => 32.00],
            ['name' => 'Quebec', 'code' => 'QC', 'shipping_fee' => 34.00],
            ['name' => 'British Columbia', 'code' => 'BC', 'shipping_fee' => 38.00],
            ['name' => 'Alberta', 'code' => 'AB', 'shipping_fee' => 36.00],
        ];

        foreach ($canadianProvinces as $province) {
            ShippingState::create([
                'shipping_country_id' => $canada->id,
                'name' => $province['name'],
                'code' => $province['code'],
                'shipping_fee' => $province['shipping_fee'],
                'is_active' => true
            ]);
        }

        // Create International (catch-all for other countries)
        ShippingCountry::create([
            'name' => 'International',
            'code' => 'INT',
            'default_shipping_fee' => 50.00,
            'is_active' => true,
            'is_international' => true
        ]);
    }
} 