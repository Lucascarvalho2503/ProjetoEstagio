<?php

namespace App\Http\Controllers;

use App\Models\Quarto;
use App\Models\Status;
use Illuminate\Http\Request;

class QuartoController extends Controller
{
    // Exibe a lista de quartos e todos os status disponíveis
    public function index()
    {
        $quartos = Quarto::with('status')->get(); // Inclui o relacionamento de status
        $statuses = Status::all(); // Busca todos os status
        return view('Quartos', compact('quartos', 'statuses'));
    }

    // Cria um novo quarto
    public function store(Request $request)
    {
        $request->validate([
            'tipo_de_quarto' => 'required|string|max:255',
            'valor_hora' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id', // Verifica se o status existe
        ]);

        Quarto::create([
            'tipo_de_quarto' => $request->tipo_de_quarto,
            'valor_hora' => $request->valor_hora,
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('quartos.index')->with('success', 'Quarto criado com sucesso.');
    }

    // Atualiza um quarto
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_de_quarto' => 'required|string|max:255',
            'valor_hora' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id', // Valida o status
        ]);

        $quarto = Quarto::findOrFail($id);
        $quarto->update([
            'tipo_de_quarto' => $request->tipo_de_quarto,
            'valor_hora' => $request->valor_hora,
            'status_id' => $request->status_id, // Atualiza o status
        ]);

        return response()->json(['success' => 'Quarto atualizado com sucesso.']);
    }

    public function destroy($id)
    {
        $quarto = Quarto::findOrFail($id);
        $quarto->delete();

        return redirect()->route('quartos.index')->with('success', 'Quarto excluído com sucesso.');
    }
}
