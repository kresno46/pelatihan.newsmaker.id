<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApupptUserController extends Controller
{
    public const NAMA_PERUSAHAAN = [
        'Trainer (SGB)' => 'PT Solid Gold Berjangka',
        'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
        'Trainer (EWF)' => 'PT Equity World Futures',
        'Trainer (BPF)' => 'PT Best Profit Futures',
        'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
    ];

    public const KANTOR_CABANG = [
        'Trainer (SGB)' => ['Semarang', 'Makassar', 'Jakarta', 'Jakarta – TCC Tower'],
        'Trainer (RFB)' => [
            'Medan', 'Palembang', 'Semarang', 'Pekanbaru', 'Bandung', 'Solo', 'Yogyakarta',
            'Balikpapan', 'Jakarta AXA 1', 'Jakarta AXA 2', 'Jakarta AXA 3', 'Jakarta DBS Tower',
            'Surabaya Pakuwon', 'Jakarta - AXA Tower 1', 'Jakarta - AXA Tower 2',
            'Jakarta - AXA Tower 3', 'Jakarta - DBS Bank Tower', 'Surabaya - Ciputra World Office Tower',
            'Surabaya - Pakuwon Tower',
        ],
        'Trainer (EWF)' => [
            'Surabaya Trillium', 'Surabaya Trilium', 'Manado', 'Jakarta', 'Semarang', 'Surabaya Praxis',
            'Cirebon', 'SCC Jakarta', 'Cyber 2 Jakarta', 'Jakarta Cyber 2',
        ],
        'Trainer (BPF)' => [
            'Jambi', 'Jakarta - Pacific Place Mall', 'Pontianak', 'Malang', 'Surabaya', 'Medan', 'Bandung',
            'Pekanbaru', 'Banjarmasin', 'Bandar Lampung', 'Semarang', 'Jakarta - Equity Tower', 'Equity Tower Jakarta',
        ],
        'Trainer (KPF)' => [
            'Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang', 'Jakarta - Plaza Marein', 'Jakarta',
        ],
    ];

    public function index(Request $request)
    {
        $user = auth()->user();
        $forcedRole = $user->isApupptAdmin() ? $user->apuppt_pt_scope : null;

        if ($user->isApupptAdmin() && ! $forcedRole) {
            abort(403, 'PT scope untuk Admin APUPPT belum diatur.');
        }

        $selectedRole = $forcedRole;
        if (! $forcedRole && $request->filled('company') && in_array($request->company, User::APUPPT_PT_ROLES, true)) {
            $selectedRole = $request->company;
        }

        $query = User::query()
            ->when($forcedRole, fn ($q) => $q->where('role', $forcedRole))
            ->when(! $forcedRole, fn ($q) => $q->whereIn('role', User::APUPPT_PT_ROLES))
            ->when(! $forcedRole && $selectedRole, fn ($q) => $q->where('role', $selectedRole));

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('cabang')) {
            $query->where('cabang', $request->cabang);
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'cabang_asc':
                $query->orderBy('cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->orderBy('cabang', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $users = $query->paginate($perPage)->withQueryString();

        $cabangOptions = self::KANTOR_CABANG[$selectedRole] ?? [];

        return view('apuppt.user.index', [
            'users' => $users,
            'forcedRole' => $forcedRole,
            'selectedRole' => $selectedRole,
            'ptOptions' => User::APUPPT_PT_ROLES,
            'namaPerusahaan' => self::NAMA_PERUSAHAAN,
            'cabangOptions' => $cabangOptions,
        ]);
    }
}
