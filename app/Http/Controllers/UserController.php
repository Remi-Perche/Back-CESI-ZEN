<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
    * @OA\Get(
    *     path="/users",
    *     summary="Lister les utilisateurs",
    *     tags={"User"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(response=200, description="Liste des utilisateurs"),
    *     @OA\Response(response=401, description="Non autorisé")
    * )
    */

    public function index()
    {
        try {
            $user = User::all();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $user;
    }

    /**
    * @OA\Get(
    *     path="/users/{id}",
    *     summary="Afficher un utilisateur",
    *     tags={"User"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(response=200, description="Utilisateur trouvé"),
    *     @OA\Response(response=404, description="Non trouvé"),
    *     @OA\Response(response=401, description="Non autorisé")
    * )
    */

    public function show(Request $request, $id)
    {
        if ($request->user()->id != $id && $request->user()->role != "Super-Administrateur") {
            return response()->json(['code' => 401, 'error' => 'Vous n\'êtes pas autorisés à faire cette modification']);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $user;
    }

    /**
    * @OA\Get(
    *     path="/auth/user",
    *     summary="Afficher les informations de l'utilisateur connecté",
    *     tags={"Auth"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(response=200, description="Utilisateur trouvé"),
    *     @OA\Response(response=404, description="Non trouvé"),
    *     @OA\Response(response=401, description="Non autorisé")
    * )
    */

    public function showMe(Request $request)
    {
        try {
            $user = User::findOrFail($request->user()->id);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return $user;
    }

    /**
    * @OA\Post(
    *      path="/users",
    *      tags={"User"},
    *      summary="Créer un utilisateur",
    *      security={{ "bearerAuth":{} }},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"firstname","lastname","email","password","role","actif"},
    *              @OA\Property(property="firstname", type="string"),
    *              @OA\Property(property="lastname", type="string"),
    *              @OA\Property(property="email", type="string"),
    *              @OA\Property(property="password", type="string"),
    *              @OA\Property(property="role", type="string"),
    *              @OA\Property(property="actif", type="boolean")
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

    /**
    * @OA\Post(
    *      path="/auth/register",
    *      tags={"Auth"},
    *      summary="Créer un compte pour l'utilisateur",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"firstname","lastname","email","password"},
    *              @OA\Property(property="firstname", type="string"),
    *              @OA\Property(property="lastname", type="string"),
    *              @OA\Property(property="email", type="string"),
    *              @OA\Property(property="password", type="string")
    *          )
    *      ),
    *      @OA\Response(response=201, description="Créé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

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

    /**
    * @OA\Post(
    *      path="/auth/login",
    *      tags={"Auth"},
    *      summary="Connecte l'utilisateur",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"email","password"},
    *              @OA\Property(property="email", type="string"),
    *              @OA\Property(property="password", type="string")
    *          )
    *      ),
    *      @OA\Response(response=200, description="Connecté"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

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

    /**
    * @OA\Post(
    *      path="/auth/logout",
    *      tags={"Auth"},
    *      summary="Déconnecte l'utilisateur",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Response(response=200, description="Déconnecté"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'error' => $e->getMessage()], 404);
        }
        return response()->json(['code' => 200, 'message' => 'Déconnexion réussie']);
    }

    /**
    * @OA\Put(
    *      path="/users/{id}",
    *      tags={"User"},
    *      summary="Met à jour un utilisateur",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="firstname", type="string"),
    *              @OA\Property(property="lastname", type="string"),
    *              @OA\Property(property="email", type="string"),
    *              @OA\Property(property="password", type="string"),
    *              @OA\Property(property="role", type="string"),
    *              @OA\Property(property="actif", type="boolean")
    *          )
    *      ),
    *      @OA\Response(response=200, description="Mis à jour"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

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

    /**
    * @OA\Put(
    *      path="/users",
    *      tags={"User"},
    *      summary="Met à jour l'utilisateur connecté",
    *      security={{ "bearerAuth":{} }},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              @OA\Property(property="firstname", type="string"),
    *              @OA\Property(property="lastname", type="string"),
    *              @OA\Property(property="email", type="string"),
    *              @OA\Property(property="password", type="string"),
    *              @OA\Property(property="role", type="string"),
    *              @OA\Property(property="actif", type="boolean")
    *          )
    *      ),
    *      @OA\Response(response=200, description="Mis à jour"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function updateMe(Request $request) 
    {
        UserController::update($request, $request->user()->id);
    }


    /**
    * @OA\Delete(
    *      path="/users/{id}",
    *      tags={"User"},
    *      summary="Supprime un utilisateur",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Supprimé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

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

    /**
    * @OA\Delete(
    *      path="/users",
    *      tags={"User"},
    *      summary="Supprime l'utilisateur connecté",
    *      security={{ "bearerAuth":{} }},
    *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *      @OA\Response(response=200, description="Supprimé"),
    *      @OA\Response(response=400, description="Bad request")
    * )
    */

    public function destroyMe(Request $request)
    {
        UserController::destroy($request, $request->user()->id);
    }
}