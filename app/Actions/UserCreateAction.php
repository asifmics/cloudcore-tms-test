<?php

namespace App\Actions;

use App\Models\User;
use App\Traits\CanUploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCreateAction
{
    use CanUploadFile;
    public function execute(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' =>['required','string','email','unique:users'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required', 'confirmed:password'],
            'image' => ['required', 'image:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $data['image'] = $this->uploadFile($request->file('image'), '', 'users');

        $user = User::create($data);

        return $user ? response()->json([
            'message' => 'Successfully created user!'
        ], 201) : response()->json(['error' => 'Provide proper details']);

    }
}
