<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        try {
            $menus = Menu::all();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $menus;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|text'
            ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        return Menu::create($request->all());
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|text'
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