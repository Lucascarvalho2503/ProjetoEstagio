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
        $quartos = Quarto::orderBy('numero', 'asc')->get();
        return view('GerenciarReserva', compact('quartos'));
    }

    //-------------------------------------------------------------------------------------------------------
    // Armazena um novo quarto no banco de dados

    public function store(Request $request)
    {
       // Validação dos dados recebidos
    $validatedData = $request->validate([
        'numero' => 'required|integer',
        'tamanho' => 'required|string',
        'status' => 'required|string'
    ]);

    // Criação do novo quarto no banco de dados
    Quarto::create($validatedData);

    return redirect()->route('CriarQuartos')->with('success', 'Quarto adicionado com sucesso!');
    }

    //-------------------------------------------------------------------------------------------------------
    // Exibe o formulário de edição para um quarto específico

    public function edit($id, Request $request)
    {
        $quarto = Quarto::where('id', $id)->first();
        $view = $request->input('view'); // Captura o parâmetro 'view' da URL
        return view('EditarReserva', ['quartos' => $quarto, 'view' => $view]);
    }


    //-------------------------------------------------------------------------------------------------------
    // Atualiza um quarto específico no banco de dados

    public function update(Request $request, $id)
    {
        $data = [];
    
        // Se o request veio da view CriarQuartos
        if ($request->query('view') === 'CriarQuartos') {
            $data = [
                'numero' => $request->numero,
                'tamanho' => $request->tamanho, // Atualizando o campo 'tamanho'
                'status' => $request->status,
            ];
        } 
        // Se o request veio da view GerenciarReserva
        else if ($request->query('view') === 'GerenciarReserva') {
            $horaEntrada = Carbon::parse($request->input('hora_entrada'));
            $horaContratada = Carbon::parse($request->input('hora_contratada'));
            $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);
    
            $data = [
                'hora_entrada' => $request->hora_entrada,
                'hora_contratada' => $request->hora_contratada,
                'hora_saida' => $horaSaida->format('H:i'), 
                'status' => $request->status,
            ];
        }
    
        // Atualiza o quarto com os dados correspondentes
        Quarto::where('id', $id)->update($data);
    
        // Redireciona de volta para a view de gerenciamento de reservas
        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto atualizado com sucesso!');
    }
    

    //-------------------------------------------------------------------------------------------------------
    // Remove um quarto específico do banco de dados

    public function destroy($id)
    {
        Quarto::where('id', $id)->delete();
        return back()->with('success', 'Quarto excluído com sucesso!');
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
        $horaContratada = Carbon::parse($request->input('hora_contratada'));
        $horaSaida = $horaEntrada->copy()->addHours($horaContratada->hour)->addMinutes($horaContratada->minute);

        $quarto->update([
            'cliente_id' => $cliente->id,
            'hora_entrada' => $request->input('hora_entrada'),
            'hora_contratada' => $horaContratada->format('H:i'),
            'hora_saida' => $horaSaida->format('H:i'), 
            'status' => 'ocupado',
        ]);

        return redirect()->route('gerenciar-reserva')->with('success', 'Quarto reservado com sucesso!');
    }
    //-------------------------------------------------------------------------------------------------------
    //Vizualiza as informações do quarto
    public function visualizar($id)
    {
        $quarto = Quarto::findOrFail($id);
        return view('VisualizarQuarto', compact('quarto'));
    }

    //-------------------------------------------------------------------------------------------------------

    public function CriarQuartos()
    {
        $quartos = Quarto::orderBy('numero', 'asc')->get(['id', 'numero', 'tamanho', 'status']);
        return view('CriarQuartos', compact('quartos'));
    }

    //-------------------------------------------------------------------------------------------------------

    public function limparReserva($id)
    {
        // Atualiza o quarto, limpando os campos relacionados à reserva e deixando-o como 'disponível'
        Quarto::where('id', $id)->update([
            'cliente_id' => null,
            'hora_entrada' => null,
            'hora_contratada' => null,
            'hora_saida' => null,
            'status' => 'disponível',
        ]);

        return redirect()->route('gerenciar-reserva')->with('success', 'Reserva cancelada e quarto agora está disponível.');
    }

}
