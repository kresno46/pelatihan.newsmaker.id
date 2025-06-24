<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ebook;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        // Reset table untuk hasil konsisten (opsional)
        \DB::table('ebooks')->delete();

        for ($i = 1; $i <= 30; $i++) {
            $title = "Ebook Pemrograman Level $i";

            Ebook::create([
                'title'     => $title,
                'deskripsi' => "Deskripsi singkat untuk $title. Ebook ini membahas konsep penting dalam pemrograman.",
                'cover'     => 'default-cover.jpg',
                'file'      => "ebook-sample.pdf",
            ]);
        }
    }
}
