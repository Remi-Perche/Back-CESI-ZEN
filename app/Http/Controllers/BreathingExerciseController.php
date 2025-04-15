<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreathingExercise;

class BreathingExerciseController extends Controller
{
    public function index()
    {
        try {
            $breathingExercises = BreathingExercise::with(['resource', 'user'])->get();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $breathingExercises;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'inspirationDuration' => 'required|integer',
                'apneaDuration' => 'required|integer',
                'expirationDuration' => 'required|integer'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        return BreathingExercise::create($request->all());
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'inspirationDuration' => 'required|integer',
                'apneaDuration' => 'required|integer',
                'expirationDuration' => 'required|integer'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        try {
            $breathingExercise = BreathingExercise::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        $breathingExercise->update($request->all());
        return $breathingExercise;
    }

    public function destroy($id)
    {
        try {
            $breathingExercise = BreathingExercise::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        BreathingExercise::destroy($id);
        return response()->json(['code' => 200, 'message' => 'Exercice de respiration supprimé avec succès'], 200);
    }
}