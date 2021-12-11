<?php

namespace App\Http\Controllers\Vault;

use Inertia\Inertia;
use App\Models\Vault;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Vault\ManageVault\CreateVault;
use App\Http\Controllers\Vault\ViewHelpers\VaultIndexViewHelper;
use App\Http\Controllers\Vault\ViewHelpers\VaultCreateViewHelper;

class VaultController extends Controller
{
    public function index()
    {
        return Inertia::render('Vault/Index', [
            'layoutData' => VaultIndexViewHelper::layoutData(),
            'data' => VaultIndexViewHelper::data(Auth::user()->account),
        ]);
    }

    public function create()
    {
        return Inertia::render('Vault/Create', [
            'layoutData' => VaultIndexViewHelper::layoutData(),
            'data' => VaultCreateViewHelper::data(),
        ]);
    }

    public function store(Request $request)
    {
        $data = [
            'account_id' => Auth::user()->account_id,
            'author_id' => Auth::user()->id,
            'type' => Vault::TYPE_PERSONAL,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        (new CreateVault)->execute($data);

        return response()->json([
            'data' => route('vault.index'),
        ], 201);
    }

    public function show(Request $request, int $vaultId)
    {
        $vault = Vault::find($vaultId);

        return Inertia::render('Vault/Show', [
            'layoutData' => VaultIndexViewHelper::layoutData($vault),
            'data' => VaultCreateViewHelper::data(),
        ]);
    }
}
