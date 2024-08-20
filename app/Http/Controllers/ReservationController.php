<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Quarto;
use App\Models\Cliente;
use App\Models\Comanda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $quartos = Quarto::with(['currentReservation.cliente'])->get();
        $clientes = Cliente::all();

        return view('Reservas', compact('quartos', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quarto_id' => 'required|exists:quartos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'horario_entrada' => 'required|date_format:H:i',
            'horas_contratadas' => 'required|integer|min:1',
        ]);

        $quarto = Quarto::find($request->input('quarto_id'));
        $valor_final = $quarto->valor_hora * (int)$request->input('horas_contratadas');
        
        $horasContratadas = (int)$request->input('horas_contratadas');
        
        $horarioEntrada = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->format('Y-m-d H:i:s');
        $horarioSaida = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->addHours($horasContratadas)->format('Y-m-d H:i:s');

        $reservation = Reservation::create([
            'quarto_id' => $request->input('quarto_id'),
            'cliente_id' => $request->input('cliente_id'),
            'horario_entrada' => $horarioEntrada,
            'horas_contratadas' => $horasContratadas,
            'horario_saida' => $horarioSaida,
            'valor_final' => $valor_final,
            'status' => 'open',
        ]);

        $quarto->status_id = 2; // Ocupado
        $quarto->save();

        Comanda::create([
            'reservation_id' => $reservation->id,
            'status' => 'open',
            'valor_comanda' => $valor_final,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva e comanda criadas com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quarto_id' => 'required|exists:quartos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'horario_entrada' => 'required|date_format:H:i',
            'horas_contratadas' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::findOrFail($id);
        $quarto = Quarto::find($request->input('quarto_id'));
        $valor_final = $quarto->valor_hora * (int)$request->input('horas_contratadas');

        $horarioEntrada = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->format('Y-m-d H:i:s');
        $horarioSaida = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->addHours((int)$request->input('horas_contratadas'))->format('Y-m-d H:i:s');

        $reservation->update([
            'quarto_id' => $request->input('quarto_id'),
            'cliente_id' => $request->input('cliente_id'),
            'horario_entrada' => $horarioEntrada,
            'horas_contratadas' => $request->input('horas_contratadas'),
            'horario_saida' => $horarioSaida,
            'valor_final' => $valor_final,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva atualizada com sucesso.');
    }

    public function finalizar($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->quarto->status_id = 1; // Disponível
        $reservation->quarto->save();

        $reservation->status = 'close';
        $reservation->save();

        $comanda = Comanda::where('reservation_id', $id)->first();
        if ($comanda) {
            $comanda->status = 'closed';
            $comanda->save();
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva finalizada com sucesso!');
    }

    public function destroy($id)
    {
        $reserva = Reservation::findOrFail($id);

        $quarto = $reserva->quarto;
        $reserva->delete();

        $quarto->status_id = 1; // Disponível
        $quarto->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva excluída com sucesso.');
    }

    public function detalhes($id)
    {
        $reserva = Reservation::with('quarto', 'cliente')->findOrFail($id);

        $dados = [
            'cliente' => [
                'nome' => $reserva->cliente->nome,
            ],
            'quarto' => [
                'valor_hora' => $reserva->quarto->valor_hora,
            ],
            'horas_contratadas' => $reserva->horas_contratadas,
            'horario_entrada' => Carbon::parse($reserva->horario_entrada)->format('H:i'),
            'horario_saida' => Carbon::parse($reserva->horario_saida)->format('H:i'),
            'valor_total' => $reserva->valor_final,
        ];

        return response()->json($dados);
    }

    public function edit($id)
    {
        $reserva = Reservation::findOrFail($id);

        return response()->json($reserva);
    }
}
