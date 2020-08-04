<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'username'=>'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6'
        ]);
        
        $username= $request->input("username");
        $email = $request->input("email");
        $password = $request->input("password");

        $hashPwd = Hash::make($password);

        $data = [
            "username"=>$username,
            "email" => $email,
            "password" => $hashPwd
        ];



        if (User::create($data)) {
            $out = [
                "success" => true,
                "message" => "create_users_success",
                "data" => $data,
                "code"    => 201,
            ];
        } else {
            $out = [
                "success" => false,
                "message" => "failed_create",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function index()
    {
        $getUser = User::OrderBy("id", "ASC")->paginate(10);

        $out = [
            "message" => "list_post",
            "results" => $getUser
        ];

        return response()->json($out, 200);
    }

    public function show ($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            abort(404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request)
    {
        if ($request->isMethod('patch')) {
            $this->validate($request, [
                'id'=>'required',
                'username' => 'required',
                'email'  => 'required|unique:users|max:255',
                'password' => 'required|min:6',
            ]);
            $id = $request->input('id');
            $username = $request->input('username');
            $email = $request->input('email');
            $password= $request->input('password');
 
            $post = User::find($id);
            $hashPwd = Hash::make($password);
 
            $data = [
                'username' => $username,
                'email' => $email,
                'password'=> $hashPwd
            ];
 
            $update = $post->update($data);
 
            if ($update) {
                $out  = [
                    "message" => "success_update_data",
                    "results" => $data,
                    "code"  => 200
                ];
            } else {
                $out  = [
                    "message" => "failed_update_data",
                    "results" => $data,
                    "code"   => 404,
                ];
            }
 
            return response()->json($out, $out['code']);
        }
        
    }
}