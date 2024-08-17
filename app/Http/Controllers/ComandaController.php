<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Produto;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ComandaController extends Controller
{
    public function index()
    {
        $comandas = Comanda::with('reserva')->get();
        $produtos = Produto::all(); // Adiciona essa linha para obter todos os produtos
        return view('Comandas', compact('comandas', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        $reserva = Reservation::find($request->reservation_id);

        $comanda = Comanda::create([
            'reservation_id' => $request->reservation_id,
            'status' => 'open',
            'valor_comanda' => $reserva->valor_final,
        ]);

        return redirect()->route('comandas.index')->with('success', 'Comanda criada com sucesso.');
    }

    public function show(Comanda $comanda)
{
    $comanda->load('produtos.produto');
    $produtos = Produto::all(); // Certifica-se de que $produtos esteja disponível na view
    return view('Comandas', compact('comanda', 'produtos'));
}

    public function update(Request $request, Comanda $comanda)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $comanda->update([
            'status' => $request->status,
        ]);

        return redirect()->route('comandas.index')->with('success', 'Comanda atualizada com sucesso.');
    }

    public function destroy(Comanda $comanda)
    {
        $comanda->delete();
        return redirect()->route('comandas.index')->with('success', 'Comanda excluída com sucesso.');
    }
}
