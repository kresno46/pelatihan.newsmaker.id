<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostTestSession;
use App\Models\PostTest;

class PostTestSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('post_tests')->delete();

        $sessions = PostTestSession::all();

        foreach ($sessions as $session) {
            for ($i = 1; $i <= 2; $i++) {
                PostTest::create([
                    'session_id'     => $session->id,
                    'question'       => "Apa jawaban dari pertanyaan nomor $i untuk sesi {$session->title}?",
                    'option_a'       => 'Pilihan A',
                    'option_b'       => 'Pilihan B',
                    'option_c'       => 'Pilihan C',
                    'option_d'       => 'Pilihan D',
                    'correct_option' => collect(['A', 'B', 'C', 'D'])->random(),
                ]);
            }
        }
    }
}
