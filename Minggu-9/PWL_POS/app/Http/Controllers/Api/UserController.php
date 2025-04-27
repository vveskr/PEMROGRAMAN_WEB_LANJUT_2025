<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel; // Ganti dengan model Anda yang sesuai
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return UserModel::all();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:m_user', // Ganti 'users' jika nama tabel berbeda
            'nama' => 'required',
            'password' => 'required|min:5',
            'level_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id,
        ]);

        return response()->json($user, 201);
    }

    public function show(UserModel $user)
    {
        return response()->json($user);
    }

    
    public function update(Request $request, UserModel $user)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|unique:users,username,' . $user->id,  // Ganti 'users' jika nama tabel berbeda
            'nama' => 'sometimes|required',
            'password' => 'nullable|min:6',
            'level_id' => 'sometimes|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $userData = [];
        if($request->filled('username')){
           $userData['username'] = $request->username;
        }
        if($request->filled('nama')){
            $userData['nama'] = $request->nama;
        }

        if($request->filled('level_id')){
            $userData['level_id'] = $request->level_id;
        }
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        return response()->json($user);
    }

    public function destroy(UserModel $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}