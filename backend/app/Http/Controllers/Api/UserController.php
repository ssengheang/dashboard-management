<?php

namespace App\Http\Controllers\Api;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
class UserController extends Controller
{
    /**
     * @return Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(
            User::query()->orderBy('id', 'desc')->paginate(8)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt(($data['password']));
        $user = User::create($data);
        return response(new UserResource($user), 201);
    }

    /**
    *
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validate([
        'name' => 'required|string',
        'email' => [
            'required',
            'email',
            // Exclude the current user from the unique check on the email field
            Rule::unique('users')->ignore($id),
        ],
        ]);

        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // if(isset($data['password'])) {
        //     $data['password'] = bcrypt(($data['password']));
        // }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User and associated orders deleted successfully']);

    }
}
