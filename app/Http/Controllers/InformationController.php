<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Information;

class InformationController extends Controller
{
    /**
    * @OA\Get(
    *      path="/informations",
    *      tags={"Information"},
    *      summary="Liste toutes les informations",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Response(response=200, description="OK"),
    *      @OA\Response(response=404, description="Not found")
    * )
    */
    public function index()
    {
        try {
            $informations = Information::with(['menu'])->get();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $informations;
    }

    /**
    * @OA\Get(
    *     path="/informations/{id}",
    *     summary="Afficher une information",
    *     tags={"Information"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(response=200, description="Information trouvée"),
    *     @OA\Response(response=404, description="Non trouvée"),
    *     @OA\Response(response=401, description="Non autorisé")
    * )
    */

    public function show(Request $request, $id)
    {
        try {
            $information = Information::with(['menu'])->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $information;
    }

    /**
    * @OA\Post(
    *      path="/informations",
    *      tags={"Information"},
    *      summary="Créer une information",
    *      security={{ "bearerAuth":{} }},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"title","description","menu_id"},
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="description", type="string"),
    *              @OA\Property(property="menu_id", type="integer")
    *          )
    *      ),
    *      @OA\Response(response=201, description="Créée"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'menu_id' => 'required|exists:menus,id'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        return Information::create($request->all());
    }

    /**
    * @OA\Put(
    *      path="/informations/{id}",
    *      tags={"Information"},
    *      summary="Met à jour une information",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="title", type="string"),
    *              @OA\Property(property="description", type="string"),
    *              @OA\Property(property="menu_id", type="integer")
    *          )
    *      ),
    *      @OA\Response(response=200, description="Mise à jour"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'menu_id' => 'sometimes|required|exists:menus,id'
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

    /**
    * @OA\Delete(
    *      path="/informations/{id}",
    *      tags={"Information"},
    *      summary="Supprime une information",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Supprimée"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

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