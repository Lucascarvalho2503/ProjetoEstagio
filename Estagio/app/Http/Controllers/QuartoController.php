<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quarto;
use App\Models\Cliente; 
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuartoController extends Controller
{
    
    // Exibe a lista de todos os quartos
    public function index()
    {
        $quartos = Quarto::all();
        return view('GerenciarReserva', compact('quartos'));
    }

    //-------------------------------------------------------------------------------------------------------
    // Armazena um novo quarto no banco de dados

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'numero' => 'required|string',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_contratada' => 'required|date_format:H:i',
            'status' => 'required|string'
        ]);
        
        $horaEntrada = Carbon::parse($request->input('hora_entrada'));
        $horaContratada = Carbon::parse($request->input('hora_contratada'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        $validatedData['hora_saida'] = $horaSaida->format('H:i');

        try {
            Quarto::create($validatedData);
        } catch (\Exception $e) {
            return redirect()->route('adicionar-quarto-form')->with('error', 'Erro ao adicionar quarto.');
        }
        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto adicionado com sucesso!');
    }

    //-------------------------------------------------------------------------------------------------------
    // Exibe o formulário de edição para um quarto específico

    public function edit($id)
    {
        $quarto = Quarto::where('id', $id)->first();
        return view('EditarQuarto', ['quartos' => $quarto]);
    }

    //-------------------------------------------------------------------------------------------------------
    // Atualiza um quarto específico no banco de dados

    public function update(Request $request, $id)
    {
        $horaEntrada = Carbon::parse($request->input('hora_entrada'));
        $horaContratada = Carbon::parse($request->input('hora_contratada'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        $data = [
            'numero' => $request->numero,
            'hora_entrada' => $request->hora_entrada,
            'hora_contratada' => $request->hora_contratada,
            'hora_saida' => $horaSaida->format('H:i'), 
            'status' => $request->status,
        ];

        Quarto::where('id', $id)->update($data);
        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto atualizado com sucesso!');
    }

    //-------------------------------------------------------------------------------------------------------
    // Remove um quarto específico do banco de dados

    public function destroy($id)
    {
        Quarto::where('id', $id)->delete();
        return redirect()->route('gerenciar-reserva');
    }

    //-------------------------------------------------------------------------------------------------------
    // Exibe o formulário de reserva para um quarto específico

    public function showReservaForm($id)
    {
        $quarto = Quarto::findOrFail($id);
        return view('ReservarQuarto', compact('quarto'));
    }

    //-------------------------------------------------------------------------------------------------------
    // Reserva um quarto específico e cria um registro de cliente

    public function reservarQuarto(Request $request, $id)
    {
        $quarto = Quarto::findOrFail($id);
        $cliente = Cliente::create([
            'nome' => $request->input('nome_cliente'),
            'cpf' => $request->input('cpf'),
        ]);

        $horaEntrada = Carbon::parse($request->input('hora_entrada'));
        $horaContratada = Carbon::parse($request->input('hora_saida'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        $quarto->update([
            'hora_entrada' => $request->input('hora_entrada'),
            'hora_saida' => $horaSaida->format('H:i'),
            'status' => 'ocupado',
        ]);

        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto reservado com sucesso!');
    }
}
