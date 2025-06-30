<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    /**
    * @OA\Get(
    *     path="/menus",
    *     summary="Lister les menus",
    *     tags={"Menu"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(response=200, description="Liste des menus"),
    *     @OA\Response(response=401, description="Non autorisé")
    * )
    */
    public function index()
    {
        try {
            $menus = Menu::all();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $menus;
    }

    /**
    * @OA\Post(
    *      path="/menus",
    *      tags={"Menu"},
    *      summary="Créer un menu",
    *      security={{ "bearerAuth":{} }},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"name","description"},
    *              @OA\Property(property="name", type="string"),
    *              @OA\Property(property="description", type="string")
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
                'name' => 'required|string',
                'description' => 'required|string'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        return Menu::create($request->all());
    }

    /**
    * @OA\Put(
    *      path="/menus/{id}",
    *      tags={"Menu"},
    *      summary="Met à jour un menu",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="name", type="string"),
    *              @OA\Property(property="description", type="string")
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
                'name' => 'sometimes|required|string',
                'description' => 'sometimes|required|string'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        try {
            $menu = Menu::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        $menu->update($request->all());
        return $menu;
    }

    /**
    * @OA\Delete(
    *      path="/menus/{id}",
    *      tags={"Menu"},
    *      summary="Supprime un menu",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Supprimé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        Menu::destroy($id);
        return response()->json(['code' => 200, 'message' => 'Menu supprimé avec succès'], 200);
    }
}