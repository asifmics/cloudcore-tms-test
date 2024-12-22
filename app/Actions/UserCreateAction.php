<?php

namespace App\Actions;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Traits\CanUploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCreateAction
{
    public function execute(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        return $user ? response()->json([
            'message' => 'Successfully created user!'
        ], 201) : response()->json(['error' => 'Provide proper details']);

    }
}
