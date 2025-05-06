<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'MEDICAL LABORATORY CONSUMABLES/DRUGS/EQUIPMENT',
            'FINANCIAL SERVICES PROVIDER',
            'BRANDING AND CORPORATE GIFT / PROMOTIONAL MATERIALS',
            'OFFICE FURNITURE/WINDOWS BLINDS/SCHOOL FURNITURE',
            'GRAPHIC DESIGN/PRINTING SERVICES',
            'GENERATOR COMPANIES AND REPAIR SERVICES',
            'SUPPLY OF HOME BASED CARE KITS',
            'CAR HIRE/SHUTTLE SERVICES AND TRUCK RENTAL',
            'RENOVATION/UPGRADING OF HEALTH FACILITIES',
            'SECURITY SERVICES',
            'VEHICLE MAINTENANCE / SERVICES',
            'SUPPLY AND INSTALLATION OF AC AND ITS ACCESSORIES / PARTS',
            'SUPPLY OF DIESEL/FUEL',
            'PROVISION OF OFFICE PARTITIONING',
            'IT EQUIPMENT & REPAIR',
            'CLEANING SERVICES /DOMESTIC STUFF',
            'CATERING SERVICES/FOOD ITEMS AND PROVISIONS',
            'OFFICE STATIONERY, CLEANING CONSUMABLES, OFFICE AND HOUSEHOLD EQUIPMENT',
            'ELECTRICAL INSTALLATIONS/SERVICES',
            'RECREATIONAL/SPORT EQUIPMENT FOR SCHOOLS, LEARNING MATERIALS FOR SCHOOLS, TEACHING MATERIALS , SCHOOL UNIFORMS AND SANDALS',
            'WASHING ITEMS, HYGIENE KITS',
            'AGRICULTURAL EQUIPMENT',
            'DIGNITY KITS, SAFETY KITS',
            'PLUMBING MATERIALS',
            'PROVISION OF FACILITY MAINTENANCE SERVICES',
            'HOTEL SERVICES',
            'WATER SUPPLY / BOREHOLE / WATER ENGINEER',
            'BUILDING MATERIALS/CIVIL STRUCTURE',
            'PHOTOCOPYING SERVICES',
            'FUMIGATION AND PEST CONTROL SERVICES/HORTICULTURIST',
            'SOLAR',
            'OTHERS',
            'MEDIA - COMMUNICATION & EDITING',
            'LOCATION / RENTAL SERVICES',
            'FOOD BASKET',
            'SECURITY AND FIRE EQUIPMENT',
            'LOGISTICS',
            'HOUSE AGENT',
            'CONSULTANT',
            'GENERAL SERVICE',
            'TELECOMMUNICATION',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
