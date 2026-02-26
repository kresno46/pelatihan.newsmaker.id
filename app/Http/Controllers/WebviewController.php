<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebviewController extends Controller
{
    private const TOOLS = [
        'aisg' => [
            'title' => 'AiSG',
            'url' => 'https://aisg23.newsmaker.id/login',
        ],
        'nmai23' => [
            'title' => 'NMAi 23',
            'url' => 'https://gwenstacy.newsmaker.id/',
        ],
        'bias23' => [
            'title' => 'BIAS23',
            'url' => 'https://bias23.com/',
        ],
        'risk-guard' => [
            'title' => 'Risk Guard',
            'url' => 'https://martingalerg.newsmaker.id/',
        ],
    ];

    public function show(Request $request, string $tool)
    {
        abort_unless(isset(self::TOOLS[$tool]), 404);

        return view('webview.show', [
            'toolKey' => $tool,
            'title' => self::TOOLS[$tool]['title'],
            'url' => self::TOOLS[$tool]['url'],
        ]);
    }
}
