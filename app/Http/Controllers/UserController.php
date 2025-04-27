<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{
     // Menampilkan semua pengguna
     public function index()
     {
         $users = User::all();
         return response()->json($users);
     }
 
     // Menyimpan pengguna baru
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'username' => 'required|string|max:255|unique:users',
             'address' => 'nullable|string|max:255',
             'contact' => 'nullable|string|max:15',
             'role' => 'required|string|max:50',
             'password' => 'required|string|min:6',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'username' => $request->username,
             'address' => $request->address,
             'contact' => $request->contact,
             'role' => $request->role,
             'password' => Hash::make($request->password),
         ]);
 
         return response()->json($user, 201);
     }
 
     // Menampilkan pengguna berdasarkan ID
     public function show($id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User  not found'], 404);
         }
         return response()->json($user);
     }
 
     // Memperbarui pengguna
     public function update(Request $request, $id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User  not found'], 404);
         }
 
         $validator = Validator::make($request->all(), [
             'name' => 'sometimes|required|string|max:255',
             'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
             'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
             'address' => 'nullable|string|max:255',
             'contact' => 'nullable|string|max:15',
             'role' => 'sometimes|required|string|max:50',
             'password' => 'sometimes|required|string|min:6',
         ]);
 
         if ($validator->fails()) {
             return response()->json($validator->errors(), 422);
         }
 
         $user->update($request->only(['name', 'email', 'username', 'address', 'contact', 'role', 'password']));
 
         return response()->json($user);
     }
 
     // Menghapus pengguna
     public function destroy($id)
     {
         $user = User::find($id);
         if (!$user) {
             return response()->json(['message' => 'User  not found'], 404);
         }
 
         $user->delete();
         return response()->json(['message' => 'User  deleted successfully']);
     }
}
