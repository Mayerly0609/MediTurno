<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Accesorio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccesorioController extends Controller
{
    public function index(): JsonResponse
    {
        $accesorios = Accesorio::query()
            ->select([
                'id',
                'articulo',
                'inventario',
            ])
            ->orderBy('articulo')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $accesorios,
        ]);
    }
}
