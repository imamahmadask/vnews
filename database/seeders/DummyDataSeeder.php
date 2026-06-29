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
                'role' => 'admin',
                'is_admin' => true
            ]
        );
        $admin->update([
            'role' => 'admin',
            'is_admin' => true
        ]);

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
                $success = false;
                try {
                    $response = Http::withOptions(['verify' => false])->timeout(10)->get('https://picsum.photos/seed/' . uniqid() . '/800/600');
                    if ($response->successful()) {
                        Storage::disk('public')->put($imageName, $response->body());
                        $images[] = $imageName;
                        $success = true;
                    }
                } catch (\Exception $e) {
                    // ignore
                }

                if (!$success) {
                    $this->generateFallbackImage($imageName);
                    $images[] = $imageName;
                }
            }

            Post::create([
                'user_id' => $admin->id,
                'category_id' => fake()->randomElement($catIds),
                'title' => $title,
                'slug' => Str::slug($title) . '-' . uniqid(),
                'image' => $images,
                'content' => '<p>' . implode('</p><p>', fake()->paragraphs(4)) . '</p>',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $this->command->info('Dummy data generated successfully.');
    }

    private function generateFallbackImage(string $path): void
    {
        if (extension_loaded('gd')) {
            $im = imagecreatetruecolor(800, 600);
            $bg = imagecolorallocate($im, 220, 220, 220);
            imagefill($im, 0, 0, $bg);
            $textColor = imagecolorallocate($im, 100, 100, 100);
            imagestring($im, 5, 350, 280, "vNews Placeholder", $textColor);
            ob_start();
            imagejpeg($im);
            $data = ob_get_clean();
            imagedestroy($im);
            Storage::disk('public')->put($path, $data);
            return;
        }

        $base64 = '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA=';
        Storage::disk('public')->put($path, base64_decode($base64));
    }
}
