<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()
                ->json(['success' => false,'message' => 'Pastikan data dimasukkan dengan lengkap!'], 401);
        }

        try {
            $user = User::create([
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'is_admin' => 0, //Kalau 0 itu customer kalau 1 itu admin
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil register!',
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal register! Error : '.$e->getMessage(),
                ]);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['success' => false,'message' => 'Unauthorized'], 401);
        }
        try {
            $user = User::where('email', $request['email'])
                ->where('is_admin', 0)
                ->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil login!',
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal login! Error : '.$e->getMessage(),
                ]);
        }
    }

    // method for user logout and delete token
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil logout!',
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal logout! Error : '.$e->getMessage(),
                ]);
        }
    }

    public function profile(Request $request){
        if(!auth()){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
        }
        try {
            $user = auth()->user();
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil menampilkan profil!',
                    'data' => $user
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal menampilkan profil! Error : '.$e->getMessage(),
                ]);
        }
    }
}
