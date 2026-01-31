<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Assignments', 'slug' => 'assignments'],
            ['name' => 'E-Books', 'slug' => 'e-books'],
            ['name' => 'Notes', 'slug' => 'notes'],
            ['name' => 'Question Papers', 'slug' => 'question-papers'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Banners
        \App\Models\Banner::create([
            'image_path' => 'banners/hero.jpg',
            'title' => 'Flash Sale!',
            'link' => '/products',
            'is_active' => true,
        ]);

        // Products
        $assignCat = \App\Models\Category::where('slug', 'assignments')->first();
        \App\Models\Product::updateOrCreate(
            ['slug' => 'engineering-drawing-assignment'],
            [
                'category_id' => $assignCat->id,
                'title' => 'Engineering Drawing Assignment',
                'description' => 'Complete assignment for first-year engineering drawing.',
                'price' => 299.00,
                'file_path' => 'products/drawing_assignment.pdf',
                'is_active' => true,
                'is_downloadable' => false,
            ]
        );
        
        \App\Models\Product::updateOrCreate(
            ['slug' => 'java-lab-manual'],
            [
                'category_id' => $assignCat->id,
                'title' => 'Java Programming Lab Manual',
                'description' => 'Detailed lab manual for Java programming.',
                'price' => 150.00,
                'file_path' => 'products/java_lab.pdf',
                'is_active' => true,
                'is_downloadable' => true,
            ]
        );
    }
}
