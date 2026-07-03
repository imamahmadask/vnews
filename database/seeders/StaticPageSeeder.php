<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaticPage::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'Tentang vnews.id',
                'lead_text' => 'Visual News (vnews.id) adalah portal berita yang berfokus pada kekuatan visual dan jurnalisme foto.',
                'content' => '<p>Di era digital yang serba cepat ini, kami percaya bahwa satu foto sering kali dapat menceritakan kisah yang lebih mendalam dan emosional daripada ribuan kata. vnews.id hadir untuk memberikan pengalaman membaca berita yang berbeda, menawan, dan minimalis, di mana gambar adalah narator utama.</p><h3 class="text-2xl font-bold mt-12 mb-4 text-gray-900">Misi Kami</h3><p>Menyajikan informasi aktual, tajam, dan tepercaya dari seluruh penjuru dunia melalui sudut pandang lensa kamera. Kami memberi panggung bagi para jurnalis foto dan kontributor visual untuk berbagi mahakarya mereka kepada dunia.</p><h3 class="text-2xl font-bold mt-12 mb-4 text-gray-900">Bergabung Bersama Kami</h3><p>vnews.id selalu terbuka bagi para fotografer dan jurnalis yang memiliki mata tajam dalam menangkap momen. Jika Anda tertarik untuk menjadi kontributor, silakan hubungi tim redaksi kami.</p>',
                'contact_email' => 'redaksi@vnews.id',
                'contact_address' => 'Kota Mataram, Nusa Tenggara Barat, Indonesia',
            ]
        );
    }
}
