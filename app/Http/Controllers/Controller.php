<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API CESI-ZEN"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Url de base"
 * )
 * 
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Gestion de l'authentification"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="Gestion des utilisateurs"
 * )
 * @OA\Tag(
 *     name="Information",
 *     description="Gestion des informations"
 * )
 * @OA\Tag(
 *     name="Menu",
 *     description="Gestion des menus"
 * )
 * @OA\Tag(
 *     name="Breathing Exercices",
 *     description="Gestion des exercices de respiration"
 * )
 *
 */

abstract class Controller
{  
    //
}
