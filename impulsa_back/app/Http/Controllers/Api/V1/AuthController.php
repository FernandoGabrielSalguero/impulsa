<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Http\Resources\UserAuthResource;
use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\PasswordResetService;
use App\Support\AuthDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Throwable;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = UserAuth::query()
            ->where('correo', $request->validated('correo'))
            ->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'correo' => ['Credenciales incorrectas.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => UserAuthResource::make($user->loadMissing('info'))->resolve(),
        ]);
    }

    public function register(RegisterRequest $request, EmailVerificationService $emailVerificationService): JsonResponse
    {
        $rol = AuthDashboard::roleForProfile($request->validated('perfil'));

        if ($rol === null) {
            throw ValidationException::withMessages([
                'perfil' => ['Perfil de registro inválido.'],
            ]);
        }

        $verificationToken = bin2hex(random_bytes(32));

        $user = DB::transaction(function () use ($request, $rol, $verificationToken) {
            $user = UserAuth::query()->create([
                'correo' => $request->validated('correo'),
                'password' => $request->validated('password'),
                'rol' => $rol,
                'verification_token' => $verificationToken,
                'usuario_tipo' => 'externo',
            ]);

            UserContacto::query()->create([
                'user_auth_id' => $user->id,
                'correo' => $request->validated('correo'),
                'check_correo' => false,
                'permison_correo' => true,
            ]);

            UserInfo::query()->create([
                'user_auth_id' => $user->id,
                'nombre' => $request->validated('nombre'),
            ]);

            return $user->load('info');
        });

        $emailSent = $emailVerificationService->sendVerificationEmail($user, $verificationToken);

        if (! $emailSent) {
            Log::warning('No se pudo enviar el correo de verificación', [
                'user_auth_id' => $user->id,
                'correo' => $user->correo,
            ]);
        }

        return response()->json([
            'message' => 'Registro creado. Te enviamos un correo para verificar tu dirección.',
            'email_sent' => $emailSent,
        ], 201);
    }

    public function verifyEmail(VerifyEmailRequest $request, EmailVerificationService $emailVerificationService): JsonResponse
    {
        try {
            $result = $emailVerificationService->verifyToken($request->validated('token'));

            return response()->json($result);
        } catch (InvalidArgumentException) {
            throw ValidationException::withMessages([
                'token' => ['El enlace de verificación no es válido o ya fue utilizado.'],
            ]);
        } catch (Throwable $exception) {
            Log::error('Error al verificar correo', [
                'message' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'token' => ['No pudimos verificar el correo en este momento.'],
            ]);
        }
    }

    public function me(Request $request): UserAuthResource
    {
        return new UserAuthResource($request->user()->loadMissing('info'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function checkEmail(CheckEmailRequest $request): JsonResponse
    {
        $exists = UserAuth::query()
            ->where('correo', $request->validated('correo'))
            ->exists();

        return response()->json([
            'valido' => ! $exists,
            'mensaje' => $exists ? 'Ya existe un usuario registrado con ese correo.' : '',
        ]);
    }

    public function forgotPassword(
        ForgotPasswordRequest $request,
        PasswordResetService $passwordResetService,
    ): JsonResponse {
        try {
            $result = $passwordResetService->requestReset($request->validated('correo'));

            return response()->json([
                'message' => $result['message'],
            ]);
        } catch (InvalidArgumentException $exception) {
            if ($exception->getMessage() === 'not_found') {
                throw ValidationException::withMessages([
                    'correo' => ['No encontramos una cuenta registrada con ese correo.'],
                ]);
            }

            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Error al solicitar recuperación de contraseña', [
                'message' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'correo' => ['No pudimos procesar la solicitud en este momento.'],
            ]);
        }
    }

    public function resetPassword(
        ResetPasswordRequest $request,
        PasswordResetService $passwordResetService,
    ): JsonResponse {
        try {
            $result = $passwordResetService->resetPassword(
                $request->validated('token'),
                $request->validated('password'),
            );

            return response()->json($result);
        } catch (InvalidArgumentException $exception) {
            $message = match ($exception->getMessage()) {
                'expired' => 'El enlace de recuperación expiró. Solicitá uno nuevo.',
                default => 'El enlace de recuperación no es válido o ya fue utilizado.',
            };

            throw ValidationException::withMessages([
                'token' => [$message],
            ]);
        } catch (Throwable $exception) {
            Log::error('Error al restablecer contraseña', [
                'message' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'token' => ['No pudimos restablecer la contraseña en este momento.'],
            ]);
        }
    }
}