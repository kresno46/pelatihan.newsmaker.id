<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ebook;
use App\Models\PostTestSession;

class PostTestSessionSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('post_test_sessions')->delete();

        $ebooks = Ebook::all();

        foreach ($ebooks as $ebook) {
            PostTestSession::create([
                'ebook_id' => $ebook->id,
                'title'    => "Post Test untuk {$ebook->title}",
                'duration' => 10,
            ]);
        }
    }
}
