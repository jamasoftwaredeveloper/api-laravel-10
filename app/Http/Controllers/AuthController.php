<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyJob;
use App\Jobs\ProcessDataJob;
use App\Models\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registro de un nuevo usuario.
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Registro de un nuevo usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="token"),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación de datos fallida"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'token' => $token, 'token_type' => 'Bearer']);
    }

    /**
     * Inicio de sesión de usuario.
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Inicio de sesión de usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com")
     *             ),
     *             @OA\Property(property="token", type="string", example="token"),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validación de datos fallida"
     *     )
     * )
     */
    public function login(Request $request)
    {
        NotifyJob::dispatch();
           
        // Despachar el trabajo a la cola
        ProcessDataJob::dispatch("Cola ejecutada");

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $auth = Auth::attempt($request->only('email', 'password'));

        if ($auth) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => 'El usuario no existe.'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['data' => $user, 'token' => $token, 'token_type' => 'Bearer']);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Cierre de sesión de usuario.
     *
     * @OA\Post(
     *     path="/logout",
     *     summary="Cierre de sesión de usuario",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cierre de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        // Logout the user
        if (auth()->user()) {

            $user = User::find(auth()->user()->id);
            $user->tokens()->delete();

            return response()->json(['message' => 'Logged out', 'status' => true], 200);
        } else {

            return response()->json(['message' => 'No tokens available', 'status' => true], 200);
        }
    }
    /**
     * Perfil del usuario autenticado.
     *
     * @OA\Get(
     *     path="/v2/user/profile",
     *     summary="Perfil del usuario autenticado",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil del usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function userProfile()
    {
        return response()->json(Auth::user());
    }
}
