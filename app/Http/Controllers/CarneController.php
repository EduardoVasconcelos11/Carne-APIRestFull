<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarneRequest;
use App\Models\Carne;
use App\Models\Parcela;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class CarneController extends Controller
{
    public function create(CarneRequest $request): JsonResponse
    {
        try {

            DB::beginTransaction();

            $carne = Carne::create([
                'valor_total' => $request->input('valor_total'),
                'valor_entrada' => $request->input('valor_entrada', 0),
                'qtd_parcelas' => $request->input('qtd_parcelas'),
                'data_primeiro_vencimento' => $request->input('data_primeiro_vencimento'),
                'periodicidade' => $request->input('periodicidade'),
            ]);

            $parcelas = [];
            $somaParcelas = $carne->valor_total - $carne->valor_entrada;
            $valorParcela = round($somaParcelas / $carne->qtd_parcelas, 2);

            $dataPrimeiroVencimento = Carbon::parse($carne->data_primeiro_vencimento);

            if ($carne->valor_entrada > 0) {
                $parcelas[] = Parcela::create([
                    'carne_id' => $carne->id,
                    'data_vencimento' => $dataPrimeiroVencimento->format('Y-m-d'),
                    'valor' => $carne->valor_entrada,
                    'numero' => 1,
                    'entrada' => true,
                ]);
                $carne->qtd_parcelas--;
            }

            for ($i = 1; $i <= $carne->qtd_parcelas; $i++) {
                $dataVencimento = $dataPrimeiroVencimento->copy();

                if ($carne->periodicidade === 'mensal') {
                    $dataVencimento->addMonths($i - 1);
                } elseif ($carne->periodicidade === 'semanal') {
                    $dataVencimento->addWeeks($i - 1);
                }

                $parcelas[] = Parcela::create([
                    'carne_id' => $carne->id,
                    'data_vencimento' => $dataVencimento->format('Y-m-d'),
                    'valor' => $valorParcela,
                    'numero' => count($parcelas) + 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'total' => $carne->valor_total,
                'valor_entrada' => $carne->valor_entrada,
                'parcelas' => $parcelas,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao criar o carnê.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getParcelas($id): JsonResponse
    {
        try {
            $carne = Carne::with('parcelas')->find($id);

            if ($carne) {
                return response()->json($carne->parcelas);
            } else {
                return response()->json(['error' => 'Carnê não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar parcelas.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
