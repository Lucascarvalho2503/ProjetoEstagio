<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarto;
use Illuminate\Support\Facades\Log;

class QuartoController extends Controller
{
    public function index()
    {
        $quartos = Quarto::all();
        return view('GerenciarReserva', compact('quartos'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called');
        Log::info('Request data: ', $request->all());

        $validatedData = $request->validate([
            'numero' => 'required|string',  
            'hora_entrada' => 'required|date_format:H:i',
            'hora_saida' => 'required|date_format:H:i',
            'status' => 'required|string'
        ]);

        Log::info('Validated data: ', $validatedData);

        try {
            Quarto::create($validatedData);
            Log::info('Quarto created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating Quarto: '.$e->getMessage());
            return redirect()->route('adicionar-quarto-form')->with('error', 'Erro ao adicionar quarto.');
        }

        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto adicionado com sucesso!');
    }

    public function edit($id)
    {
        $quarto = Quarto::where('id', $id)->first();
        return view('EditarQuarto', ['quartos'=>$quarto]);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'numero' => $request->numero,  
            'hora_entrada' => $request->hora_entrada,
            'hora_saida' => $request->hora_saida,
            'status' => $request->status,
        ];

        Quarto::where('id', $id)->update($data);
        return redirect()->route('gerenciar-reserva');
    }

    public function destroy($id)
    {
        Quarto::where('id', $id)->delete();
        return redirect()->route('gerenciar-reserva');
    }


}
