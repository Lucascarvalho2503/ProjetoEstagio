<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarto;
use App\Models\Cliente; 
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        $horaEntrada = Carbon::parse($request->input('hora_entrada'));
        $horaContratada = Carbon::parse($request->input('hora_contratada'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        $data = [
            'numero' => $request->numero,
            'hora_entrada' => $request->hora_entrada,
            'hora_saida' => $horaSaida->format('H:i'), // Usar a hora de saída calculada
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

    public function showReservaForm($id)
    {
        $quarto = Quarto::findOrFail($id);
        return view('ReservarQuarto', compact('quarto'));
    }

    public function reservarQuarto(Request $request, $id)
    {
        $quarto = Quarto::findOrFail($id);

        // Criar cliente
        $cliente = Cliente::create([
            'nome' => $request->input('nome_cliente'),
            'cpf' => $request->input('cpf'),
        ]);

        $horaEntrada = Carbon::parse($request->input('hora_entrada'));
        $horaContratada = Carbon::parse($request->input('hora_saida'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        // Atualizar quarto
        $quarto->update([
            'hora_entrada' => $horaEntrada->format('H:i'),
            'hora_saida' => $horaSaida->format('H:i'), // Atualizar com a hora de saída calculada
            'status' => 'ocupado',
        ]);

        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto reservado com sucesso!');
    }

}
