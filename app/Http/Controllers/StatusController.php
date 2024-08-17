<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    // Exibe todos os status
    public function index()
    {
        $statuses = Status::all();
        return response()->json($statuses);
    }

    // Cria um novo status
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Status::create([
            'name' => $request->name,
        ]);

        return redirect()->route('quartos.index')->with('success', 'Status criado com sucesso.');
    }
}
