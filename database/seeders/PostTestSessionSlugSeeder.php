<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostTestSession;
use Illuminate\Support\Str;

class PostTestSessionSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing PostTestSession records to generate slugs if they don't have one
        PostTestSession::whereNull('slug')->orWhere('slug', '')->get()->each(function ($session) {
            $session->slug = Str::slug($session->title) . '-' . uniqid();
            $session->save();
        });
    }
}
