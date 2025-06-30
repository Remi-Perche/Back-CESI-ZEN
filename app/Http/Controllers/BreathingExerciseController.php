<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreathingExercise;

class BreathingExerciseController extends Controller
{
    /**
    * @OA\Get(
    *      path="/breathingExercises",
    *      tags={"Breathing Exercices"},
    *      summary="Liste toutes les exercices de respiration",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Response(response=200, description="OK"),
    *      @OA\Response(response=404, description="Not found")
    * )
    */
    public function index()
    {
        try {
            $breathingExercises = BreathingExercise::all();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $breathingExercises;
    }

    /**
    * @OA\Post(
    *      path="/breathingExercises",
    *      tags={"Breathing Exercices"},
    *      summary="Créer un exercice de respiration",
    *      security={{ "bearerAuth":{} }},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"title","inspirationDuration","apneaDuration","expirationDuration"},
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="inspirationDuration", type="integer"),
    *              @OA\Property(property="apneaDuration", type="integer"),
    *              @OA\Property(property="expirationDuration", type="integer")
    *          )
    *      ),
    *      @OA\Response(response=201, description="Créé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */
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

    /**
    * @OA\Put(
    *      path="/breathingExercises/{id}",
    *      tags={"Breathing Exercices"},
    *      summary="Met à jour un exercice de respiration",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="inspirationDuration", type="integer"),
    *              @OA\Property(property="apneaDuration", type="integer"),
    *              @OA\Property(property="expirationDuration", type="integer")
    *          )
    *      ),
    *      @OA\Response(response=200, description="Mis à jour"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */
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

    /**
    * @OA\Delete(
    *      path="/breathingExercises/{id}",
    *      tags={"Breathing Exercices"},
    *      summary="Supprime un exercice de respiration",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Supprimé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */
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