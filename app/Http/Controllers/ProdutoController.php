<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return view('Produtos', compact('produtos'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'valor' => 'required|numeric',
        'estoque' => 'required|integer|min:0',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $imagemPath = null;
    if ($request->hasFile('imagem')) {
        $imagemPath = $request->file('imagem')->store('produtos', 'public');
    }

    Produto::create([
        'nome' => $request->nome,
        'valor' => $request->valor,
        'estoque' => $request->estoque,
        'imagem' => $imagemPath
    ]);

    return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'valor' => 'required|numeric',
        'estoque' => 'required|integer|min:0',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $produto = Produto::findOrFail($id);
    $imagemPath = $produto->imagem;

    if ($request->hasFile('imagem')) {
        if ($imagemPath) {
            Storage::disk('public')->delete($imagemPath);
        }
        $imagemPath = $request->file('imagem')->store('produtos', 'public');
    }

    $produto->update([
        'nome' => $request->nome,
        'valor' => $request->valor,
        'estoque' => $request->estoque,
        'imagem' => $imagemPath
    ]);

    return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso.');
}

public function destroy($id)
{
    $produto = Produto::findOrFail($id);
    if ($produto->imagem) {
        Storage::disk('public')->delete($produto->imagem);
    }
    $produto->delete();

    return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso.');
}

}
