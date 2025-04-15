<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;

class InformationController extends Controller
{
    public function index()
    {
        try {
            $informations = Information::with(['menu'])->get();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $informations;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|text',
                'menu_id' => 'required|exists:menu,id'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        return Information::create($request->all());
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|text',
                'menu_id' => 'required|exists:menu,id'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        try {
            $information = Information::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        $information->update($request->all());
        return $information;
    }

    public function destroy($id)
    {
        try {
            $information = Information::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        Information::destroy($id);
        return response()->json(['code' => 200, 'message' => 'Information supprimée avec succès'], 200);
    }
}