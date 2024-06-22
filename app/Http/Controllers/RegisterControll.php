<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Firebase\JWT\JWT; // Pastikan untuk mengimpor JWT
use Illuminate\Support\Facades\Hash; // Mengimpor Hash untuk enkripsi password

class RegisterControll extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'contact' => 'required|string|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id', // Validasi role_id
            'teacher_id' => 'required|exists:teachers,id' // Validasi teacher_id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $payload = $validator->validated();

        $user = User::create([
            'name' => $payload['name'],
            'contact' => $payload['contact'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'role_id' => $payload['role_id'],
            'teacher_id' => $payload['teacher_id']
        ]);

        $tokenPayload = [
            'id' => $user->id,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'teacher_id' => $user->teacher_id,
            'iat' => time(),
            'exp' => time() + 3600 // Token expired in 1 hour
        ];

        $token = JWT::encode($tokenPayload, env('JWT_SECRET_KEY'), 'HS256');

        return response()->json([
            'msg' => 'Account successfully created',
            'token' => 'Bearer ' .$token
        ], 200);
    }
}
