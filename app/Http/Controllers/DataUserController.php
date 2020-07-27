<?php

namespace App\Http\Controllers;

use App\User;

class DataUserController extends Controller
{
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
        $post = User::find($id);
        
        if (!$post) {
            abort(404);
        }
        return response()->json($post, 200);
    }
}