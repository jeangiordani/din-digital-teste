<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|max:255|string",
            "email" => "required|unique:users,email|max:255|email",
            "password" => "required|min:6"
        ]);

        if ($validator->fails()) {
            $validator = $validator->errors()->messages();
            return response()->json(['errors' => $validator], 422);
        } else {
            $data = [
                "name" => $request->get('name'),
                "password" => bcrypt($request->get('password')),
                "email" => $request->get('email')
            ];
        }

        $user = $this->model->create($data);
        return response()->json($user, 201);
    }
}
