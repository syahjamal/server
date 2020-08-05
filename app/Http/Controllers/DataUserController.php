<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{   
    //create
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

    //get
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

    //Update
    public function update(Request $request, $id)
    {
        
        if($request->isMethod('patch')){

            $username= $request->input("username");
            $email = $request->input("email");
            $password = $request->input("password");

            $this->validate($request, [
                'username'=>'required|unique:users,username,'.$id,
                'email' => 'required|max:255|unique:users,email,'.$id,
                'password' => 'required|min:6',
            ]);

            $post=User::find($id);
            $hashPwd = Hash::make($password);
            $data = [
                "username"=>$username,
                "email" => $email,
                "password" => $hashPwd
            ];

            $update = $post->update(
                $data
            );

            if ($update) {
                $out = [
                    "success" => true,
                    "message" => "update_success",
                    "data" => $data,
                    "code"    => 201,
                ];
            } else {
                $out = [
                    "success" => false,
                    "message" => "update_failed",
                    "code"   => 404,
                ];
            }

            return response()->json($out, $out['code']);
        }
    }   

    //Delete
    public function destroy($id)
    {
        $posts =  User::find($id);

        if (!$posts) {
            $data = [
                "message" => "id not found",
            ];
        } else {
            $posts->delete();
            $data = [
                "message" => "success_deleted"
            ];
        }

        return response()->json($data, 200);
    }

}
