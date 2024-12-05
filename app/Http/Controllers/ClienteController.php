<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
        // Método para exibir a lista de clientes
        public function index()
        {
            // Recupera todos os clientes e verifica se possuem reservas ativas
            $clientes = Cliente::with(['reservations' => function ($query) {
                $query->active(); // Usa o escopo 'active' para filtrar apenas reservas ativas
            }])->get()
            ->map(function ($cliente) {
                // Define 'possui_reserva' como true apenas se houver reservas ativas
                $cliente->possui_reserva = $cliente->reservations->isNotEmpty();
                return $cliente;
            });
        
            return view('Clientes', compact('clientes'));
        }
        
        

        


    // Método para criar um novo cliente
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:clientes,cpf',
            'celular' => 'required|string|max:20',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente criado com sucesso.');
    }

    // Método para atualizar um cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:clientes,cpf,'.$id,
            'celular' => 'required|string|max:20',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return response()->json(['success' => 'Cliente atualizado com sucesso.']);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso.');
    }
}
