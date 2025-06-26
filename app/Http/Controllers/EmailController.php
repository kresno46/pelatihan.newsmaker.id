<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolder('INBOX');

        $messages = $folder->messages()
            ->all()
            ->limit(10)
            ->get()
            ->sortByDesc(function ($message) {
                $date = $message->getDate()->get();
                return is_array($date) ? $date[0] : $date;
            });

        // Tambahkan pemrosesan emailData agar tidak error di view
        $emailData = $messages->map(function ($message) {
            $date = $message->getDate()->get();
            $formattedDate = is_array($date) ? $date[0]->format('d M Y H:i') : $date->format('d M Y H:i');

            return [
                'subject' => $message->getSubject() ?? '(Tanpa Subjek)',
                'from' => isset($message->getFrom()[0]) ? $message->getFrom()[0]->mail : '-',
                'date' => $formattedDate,
                'body' => Str::limit(strip_tags($message->getTextBody()), 1000),
            ];
        });

        return view('emails.index', compact('messages', 'emailData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
