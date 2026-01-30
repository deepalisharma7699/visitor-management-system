<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Mayfair Nexus',
                'location' => 'Dubai Marina, Dubai',
                'description' => 'Luxury 3 & 4 BHK apartments with modern amenities'
            ],
            [
                'name' => 'Mayfair Heights',
                'location' => 'Dubai Silicon Oasis, Dubai',
                'description' => '2 & 3 BHK premium apartments with lake view'
            ],
            [
                'name' => 'Mayfair Gardens',
                'location' => 'Dubai Land, Dubai',
                'description' => 'Spacious 2, 3 & 4 BHK flats with garden spaces'
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
