<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        try {
            $user = User::all();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $user;
    }

    public function show(Request $request, $id)
    {
        if ($request->user()->id != $id && $request->user()->role != "Super-Administrateur") {
            return response()->json(['code' => 403, 'error' => 'Vous n\'êtes pas autorisés à faire cette modification']);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $user;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'in:Citoyen,Modérateur,Administrateur,Super-Administrateur',
                'actif' => 'required|boolean'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }
        $request['password'] = Hash::make($request->password);
        return User::create($request->all());
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => "Citoyen",
            'actif' => true
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['code' => 404, 'error' => "Identifiants incorrects !"], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return response()->json(['code' => 200, 'message' => 'Déconnexion réussie']);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->id != $id && $request->user()->role != "Super-Administrateur") {
            return response()->json(['code' => 403, 'error' => 'Vous n\'êtes pas autorisés à faire cette modification']);
        }
        try {
            $request->validate([
                'firstname' => 'sometimes|required|string',
                'lastname' => 'sometimes|required|string',
                'email' => 'sometimes|required|email',
                'password' => 'sometimes|required|min:6',
                'role' => 'sometimes|in:Citoyen,Modérateur,Administrateur,Super-Administrateur',
                'actif' => 'sometimes|required|boolean'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['code' => 400, 'error' => $e->getMessage()], 400);
        }

        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => 'Utilisateur non trouvé'], 404);
        }

        if ($request->has('role') && $request->user()->role != "Super-Administrateur") {
            $request->request->remove('role');
        }

        if ($request->has('password')) {
            $request['password'] = Hash::make($request->password);
        } else {
            // Pour éviter de passer `null` et écraser le mot de passe
            $request->request->remove('password');
        }
        
        $user->update($request->all());

        return response()->json($user);
    }


    public function destroy(Request $request, $id)
    {
        if ($request->user()->id != $id && $request->user()->role != "Super-Administrateur") {
            return response()->json(['code' => 403, 'error' => 'Vous n\'êtes pas autorisés à faire cette suppression']);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        User::destroy($id);
        return response()->json(['code' => 200, 'message' => 'Utilisateur supprimé avec succès'], 200);
    }
}