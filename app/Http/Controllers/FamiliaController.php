<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamiliaController extends Controller
{
    public function getTreeData(): JsonResponse
    {
        $categories = Familia::whereNull('idFamiliaPadre')->with('children')->limit(15)->get();
        
        return response()->json($this->formatTree($categories));
    }

    private function formatTree($categories)
    {
        $branch = [];
        foreach ($categories as $category) {
            $branch[] = [
                'id' => $category->id,
                'text' => $category->nombre,
                'icon' => false,
                'children' => $category->children->count() ? $this->formatTree($category->children) : false,
                
            ];
        }
        return $branch;
    }
}
