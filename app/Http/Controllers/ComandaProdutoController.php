<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\ComandaProduto;
use App\Models\Produto;
use Illuminate\Http\Request;

class ComandaProdutoController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'comanda_id' => 'required|exists:comandas,id',
        'produto_id' => 'required|exists:produtos,id',
        'quantidade' => 'required|integer|min:1',
    ]);

    $comanda = Comanda::findOrFail($request->comanda_id);
    $produto = Produto::findOrFail($request->produto_id);

    if ($produto->estoque < $request->quantidade) {
        return redirect()->back()->with('error', 'Estoque insuficiente para o produto.');
    }

    ComandaProduto::create([
        'comanda_id' => $comanda->id,
        'produto_id' => $produto->id,
        'quantidade' => $request->quantidade,
    ]);

    // Atualiza o estoque do produto
    $produto->estoque -= $request->quantidade;
    $produto->save();

    // Atualiza o valor da comanda
    $comanda->valor_comanda += $produto->valor * $request->quantidade;
    $comanda->save();

    return redirect()->route('comandas.index')->with('success', 'Produto adicionado à comanda com sucesso.');
}


public function destroy($comandaId, $produtoId)
{
    $comandaProduto = ComandaProduto::where('comanda_id', $comandaId)
                                   ->where('produto_id', $produtoId)
                                   ->firstOrFail();

    $produto = $comandaProduto->produto;

    // Reverte o estoque do produto
    $produto->estoque += $comandaProduto->quantidade;
    $produto->save();

    // Atualiza o valor da comanda
    $comanda = $comandaProduto->comanda;
    $comanda->valor_comanda -= $produto->valor * $comandaProduto->quantidade;
    $comanda->save();

    $comandaProduto->delete();

    return redirect()->route('comandas.show', $comanda->id)->with('success', 'Produto removido da comanda com sucesso.');
}

}
