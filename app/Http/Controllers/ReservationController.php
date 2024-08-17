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
        $reservations = Reservation::whereIn('status', ['open', 'close'])->with('quarto', 'cliente')->get();
        $quartos = Quarto::all();
        $clientes = Cliente::all();
        return view('Reservas', compact('reservations', 'quartos', 'clientes'));
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
    
    // Calculando o horário de entrada e saída como timestamps
    $horarioEntrada = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->format('Y-m-d H:i:s');
    $horarioSaida = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->addHours($horasContratadas)->format('Y-m-d H:i:s');

    // Criação da reserva
    $reservation = Reservation::create([
        'quarto_id' => $request->input('quarto_id'),
        'cliente_id' => $request->input('cliente_id'),
        'horario_entrada' => $horarioEntrada,
        'horas_contratadas' => $horasContratadas,
        'horario_saida' => $horarioSaida,
        'valor_final' => $valor_final,
        'status' => 'open',
    ]);

    // Atualiza o status do quarto para 'Ocupado'
    $quarto->status_id = 2; // 2 é o valor para 'Ocupado'
    $quarto->save();

    // Criação da comanda associada
    Comanda::create([
        'reservation_id' => $reservation->id,
        'status' => 'open',
        'valor_comanda' => $valor_final, // Inicialmente, o valor da comanda é o valor final da reserva
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

        $horasContratadas = (int)$request->input('horas_contratadas');

        // Calculando o horário de entrada e saída como timestamps
        $horarioEntrada = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->format('Y-m-d H:i:s');
        $horarioSaida = Carbon::createFromFormat('H:i', $request->input('horario_entrada'))->addHours($horasContratadas)->format('Y-m-d H:i:s');

        $reservation->update([
            'quarto_id' => $request->input('quarto_id'),
            'cliente_id' => $request->input('cliente_id'),
            'horario_entrada' => $horarioEntrada,
            'horas_contratadas' => $horasContratadas,
            'horario_saida' => $horarioSaida,
            'valor_final' => $valor_final,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva atualizada com sucesso.');
    }

    public function finalizar($id)
{
    $reservation = Reservation::find($id);

    // Atualize o status do quarto para 'Disponível'
    $reservation->quarto->status_id = 1; // Supondo que 1 seja 'Disponível'
    $reservation->quarto->save();

    // Marque a reserva como finalizada (status close)
    $reservation->status = 'close';
    $reservation->save();

    // Atualize o status da comanda relacionada
    $comanda = Comanda::where('reservation_id', $id)->first();
    if ($comanda) {
        $comanda->status = 'closed'; // Supondo que 'closed' é o status para quando a comanda está finalizada
        $comanda->save();
    }

    return redirect()->route('reservas.index')->with('success', 'Reserva finalizada com sucesso!');
}
}
