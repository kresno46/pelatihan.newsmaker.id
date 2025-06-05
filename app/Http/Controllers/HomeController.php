<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $requiredFields = [
            'name',
            'email',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'warga_negara',
            'alamat',
            'no_id',
            'no_tlp',
            'pekerjaan',
        ];

        $isIncomplete = false;

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                $isIncomplete = true;
                break;
            }
        }

        return view('dashboard', compact('isIncomplete'));
    }
}
