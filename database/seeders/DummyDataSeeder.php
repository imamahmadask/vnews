<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@vnews.id'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        $categories = [
            ['name' => 'Sports', 'description' => 'Latest news from the sports world.'],
            ['name' => 'Politics', 'description' => 'Updates on political events.'],
            ['name' => 'Technology', 'description' => 'Tech news, gadgets and software.'],
            ['name' => 'Entertainment', 'description' => 'Movies, music, and pop culture.'],
            ['name' => 'Travel', 'description' => 'Beautiful destinations around the globe.'],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $category = Category::firstOrCreate([
                'slug' => Str::slug($cat['name'])
            ], $cat);
            $catIds[] = $category->id;
        }

        Storage::disk('public')->makeDirectory('news-images');

        $this->command->info('Creating 10 dummy posts with images (this might take a few seconds)...');

        for ($i = 1; $i <= 10; $i++) {
            $title = fake()->sentence(6);
            
            $images = [];
            $numImages = rand(2, 4);
            
            for ($j = 0; $j < $numImages; $j++) {
                $imageName = 'news-images/dummy-' . uniqid() . '.jpg';
                try {
                    $response = Http::withOptions(['verify' => false])->timeout(10)->get('https://picsum.photos/seed/' . uniqid() . '/800/600');
                    if ($response->successful()) {
                        Storage::disk('public')->put($imageName, $response->body());
                        $images[] = $imageName;
                    }
                } catch (\Exception $e) {
                    // ignore
                }
            }

            Post::create([
                'user_id' => $admin->id,
                'category_id' => fake()->randomElement($catIds),
                'title' => $title,
                'slug' => Str::slug($title) . '-' . uniqid(),
                'image' => count($images) > 0 ? $images : null,
                'content' => '<p>' . implode('</p><p>', fake()->paragraphs(4)) . '</p>',
                'status' => 'published',
            ]);
        }

        $this->command->info('Dummy data generated successfully.');
    }
}
