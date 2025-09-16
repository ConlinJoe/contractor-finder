<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobTypes = [
            [
                'name' => 'Plumbing',
                'slug' => 'plumbing',
                'description' => 'Plumbing services including repairs, installations, and maintenance',
                'category' => 'Home Improvement',
                'keywords' => ['plumber', 'plumbing', 'pipe', 'drain', 'toilet', 'sink', 'faucet', 'water heater', 'sewer'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Electrical',
                'slug' => 'electrical',
                'description' => 'Electrical services including wiring, installations, and repairs',
                'category' => 'Home Improvement',
                'keywords' => ['electrician', 'electrical', 'wiring', 'outlet', 'switch', 'panel', 'lighting', 'circuit'],
                'sort_order' => 2,
            ],
            [
                'name' => 'HVAC',
                'slug' => 'hvac',
                'description' => 'Heating, ventilation, and air conditioning services',
                'category' => 'Home Improvement',
                'keywords' => ['hvac', 'heating', 'cooling', 'air conditioning', 'furnace', 'ac', 'ventilation', 'thermostat'],
                'sort_order' => 3,
            ],
            [
                'name' => 'Roofing',
                'slug' => 'roofing',
                'description' => 'Roof installation, repair, and maintenance services',
                'category' => 'Home Improvement',
                'keywords' => ['roofing', 'roof', 'shingle', 'gutter', 'siding', 'chimney', 'skylight'],
                'sort_order' => 4,
            ],
            [
                'name' => 'Flooring',
                'slug' => 'flooring',
                'description' => 'Floor installation, repair, and refinishing services',
                'category' => 'Home Improvement',
                'keywords' => ['flooring', 'floor', 'carpet', 'hardwood', 'tile', 'laminate', 'vinyl', 'refinishing'],
                'sort_order' => 5,
            ],
            [
                'name' => 'Painting',
                'slug' => 'painting',
                'description' => 'Interior and exterior painting services',
                'category' => 'Home Improvement',
                'keywords' => ['painting', 'painter', 'paint', 'interior', 'exterior', 'staining', 'wallpaper'],
                'sort_order' => 6,
            ],
            [
                'name' => 'Kitchen Remodeling',
                'slug' => 'kitchen-remodeling',
                'description' => 'Kitchen renovation and remodeling services',
                'category' => 'Home Improvement',
                'keywords' => ['kitchen', 'remodeling', 'renovation', 'cabinet', 'countertop', 'appliance', 'backsplash'],
                'sort_order' => 7,
            ],
            [
                'name' => 'Bathroom Remodeling',
                'slug' => 'bathroom-remodeling',
                'description' => 'Bathroom renovation and remodeling services',
                'category' => 'Home Improvement',
                'keywords' => ['bathroom', 'remodeling', 'renovation', 'shower', 'bathtub', 'vanity', 'tile'],
                'sort_order' => 8,
            ],
            [
                'name' => 'Landscaping',
                'slug' => 'landscaping',
                'description' => 'Landscape design, installation, and maintenance services',
                'category' => 'Outdoor',
                'keywords' => ['landscaping', 'landscape', 'lawn', 'garden', 'tree', 'shrub', 'irrigation', 'hardscape'],
                'sort_order' => 9,
            ],
            [
                'name' => 'Concrete & Masonry',
                'slug' => 'concrete-masonry',
                'description' => 'Concrete work, masonry, and stone services',
                'category' => 'Construction',
                'keywords' => ['concrete', 'masonry', 'stone', 'brick', 'block', 'patio', 'driveway', 'sidewalk'],
                'sort_order' => 10,
            ],
            [
                'name' => 'Windows & Doors',
                'slug' => 'windows-doors',
                'description' => 'Window and door installation and repair services',
                'category' => 'Home Improvement',
                'keywords' => ['window', 'door', 'replacement', 'installation', 'glass', 'frame', 'lock'],
                'sort_order' => 11,
            ],
            [
                'name' => 'Deck & Fence',
                'slug' => 'deck-fence',
                'description' => 'Deck and fence construction and repair services',
                'category' => 'Outdoor',
                'keywords' => ['deck', 'fence', 'railing', 'gate', 'patio', 'outdoor', 'wood', 'composite'],
                'sort_order' => 12,
            ],
            [
                'name' => 'Basement Finishing',
                'slug' => 'basement-finishing',
                'description' => 'Basement finishing and waterproofing services',
                'category' => 'Home Improvement',
                'keywords' => ['basement', 'finishing', 'waterproofing', 'insulation', 'drywall', 'flooring'],
                'sort_order' => 13,
            ],
            [
                'name' => 'General Contracting',
                'slug' => 'general-contracting',
                'description' => 'General construction and project management services',
                'category' => 'Construction',
                'keywords' => ['general contractor', 'construction', 'remodeling', 'renovation', 'project management'],
                'sort_order' => 14,
            ],
            [
                'name' => 'Handyman Services',
                'slug' => 'handyman-services',
                'description' => 'General handyman and repair services',
                'category' => 'Home Improvement',
                'keywords' => ['handyman', 'repair', 'maintenance', 'fix', 'small jobs', 'odd jobs'],
                'sort_order' => 15,
            ],
        ];

        foreach ($jobTypes as $jobType) {
            JobType::updateOrCreate(
                ['slug' => $jobType['slug']],
                $jobType
            );
        }
    }
}
