<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user->save();
            $data = [
                'token' => $user->createToken('carService')->plainTextToken,
                'user' => $user,
            ];
            return $this->ResponseSuccess($data, 'logged-in', 'Logged In Successfully', 200);

    }

    public function logout(Request $request)
    {
        auth()->user()->save();

        $request->user()->currentAccessToken()->delete();

        return $this->ResponseSuccess(null, '', 'User logged out successfully!', 200);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);


            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = $request->input('role') ?? 'client';
             $user->save();


            return $this->ResponseSuccess(UserResource::make($user), 'user-registered', 'Registration successful!', 201);
        } catch (\Exception $e) {
            // Return error response in case of failure
            return $this->ResponseError(500, $e->getMessage());
        }
    }
}
