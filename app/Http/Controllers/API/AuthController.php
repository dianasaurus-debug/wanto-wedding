<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
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
                'alamat' => $request->alamat,
                'profile_photo_path' => 'user.png',
                'fcm_token' => $request->fcm_token,
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        if($validator->fails()){
            return response()
                ->json(['success' => false,'message' => 'Pastikan data dimasukkan dengan lengkap!'], 401);
        }

        try {
            $user = User::where('id', auth()->user()->id)->first();
            if($request->hasfile('photo')) {
                $old_photo = public_path('/images/uploads/'.$user->profile_photo_path);
                if(File::exists($old_photo)) {
                    File::delete($old_photo);
                }
                $file_foto = $request->file('photo');
                $file_ext = $file_foto->getClientOriginalExtension();
                $file_name = 'foto_profil_'.time().'.'.$file_ext;
                $file_foto->move(public_path().'/images/uploads/', $file_name);
                $user->update([
                    'profile_photo_path' => $file_name,
                    'nama_depan' => $request->nama_depan,
                    'nama_belakang' => $request->nama_belakang,
                    'alamat' => $request->alamat,
                ]);

            } else {
                 $user->update([
                    'nama_depan' => $request->nama_depan,
                    'nama_belakang' => $request->nama_belakang,
                    'alamat' => $request->alamat,
                ]);
            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil update profil!',
                    'data' => $user
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal update profil! Error : '.$e->getMessage(),
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
                ->first();

            $user->update(['fcm_token'=>$request->fcm_token]);

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

    public function change_password(Request $request){
        if(!auth()){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
        }
        try {
            $user = auth()->user();
            if(Hash::check($request->password, $user->password)){
                $found_user = User::where('id', $user->id)->first();
                $found_user->update(['password'=>Hash::make($request->new_password)]);
                return response()
                    ->json([
                        'success' => true,
                        'message' => 'Berhasil merubah password!',
                        'data' => $user
                    ]);
            } else {
                return response()
                    ->json([
                        'success' => false,
                        'message' => 'Password yang dimasukkan tidak tepat',
                    ]);
            }

        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal menampilkan profil! Error : '.$e->getMessage(),
                ]);
        }
    }
    public function get_notifications(Request $request){
        if(!auth()){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ]);
        }
        try {
            $notifications = Notification::where('user_id', auth()->user()->id)->get();
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Berhasil menampilkan notifikasi!',
                    'data' => $notifications
                ]);
        } catch (\Exception $e){
            return response()
                ->json([
                    'success' => false,
                    'message' => 'Gagal menampilkan notifikasi! Error : '.$e->getMessage(),
                ]);
        }
    }
}
