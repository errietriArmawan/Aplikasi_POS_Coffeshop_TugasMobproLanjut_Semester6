<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Fungsi untuk login dan menghasilkan token
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek apakah user ada berdasarkan username
        $user = User::where('username', $credentials['username'])->first();

        // Validasi password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Opsional: Periksa apakah user memiliki role yang valid
        if (!$user->role || !in_array($user->role, ['admin', 'kasir'])) {
            return response()->json(['message' => 'User role is invalid or not assigned properly'], 403);
        }

        // Buat token baru
        $token = $user->createToken('YourAppName')->plainTextToken;

        // Kirim response dengan token dan data user
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    // Fungsi untuk logout dan mencabut token
    public function logout(Request $request)
    {
        // Pastikan user sudah terautentikasi
        if (!$request->user()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Ambil token saat ini dan hapus token yang sedang aktif
        $request->user()->currentAccessToken()->delete();

        // Respons logout berhasil
        return response()->json(['message' => 'Logout successful'], 200);
    }

        
}
