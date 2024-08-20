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
            'status_id' => 'required|exists:statuses,id',
            'numero' => 'required|integer|unique:quartos,numero' // Adiciona validação para 'numero'
        ]);

        Quarto::create([
            'tipo_de_quarto' => $request->tipo_de_quarto,
            'valor_hora' => $request->valor_hora,
            'status_id' => $request->status_id,
            'numero' => $request->numero, // Inclui o campo 'numero'
        ]);

        return redirect()->route('quartos.index')->with('success', 'Quarto criado com sucesso.');
    }

    // Atualiza um quarto existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_de_quarto' => 'required|string|max:255',
            'valor_hora' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id',
            'numero' => 'required|integer|unique:quartos,numero,' . $id, // Adiciona validação para 'numero'
        ]);

        $quarto = Quarto::findOrFail($id);
        $quarto->update([
            'tipo_de_quarto' => $request->tipo_de_quarto,
            'valor_hora' => $request->valor_hora,
            'status_id' => $request->status_id,
            'numero' => 'required|integer|unique:quartos,numero,' . $id, // Atualiza o campo 'numero'
        ]);

        return response()->json(['success' => 'Quarto atualizado com sucesso.']);
    }

    // Exclui um quarto existente
    public function destroy($id)
    {
        $quarto = Quarto::findOrFail($id);
        $quarto->delete();

        return redirect()->route('quartos.index')->with('success', 'Quarto excluído com sucesso.');
    }
}
