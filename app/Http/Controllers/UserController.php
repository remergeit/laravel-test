<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Get Users list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = User::latest()
                    ->paginate(10);

        return response()->json($list, 200);
    }

    /**
     * Get the User.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * Create a User.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json($user, 200);
    }

    /**
     * Update the User.
     *
     * @param  User  $user
     * @param  App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserRequest $request)
    {
        $user->update($request->validated());

        return response()->json($user, 200);
    }

    /**
     * Remove the User.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        if ($user['error']) {
            return response()->json(['errors' => $user['error']], 405);
        }

        return response()->json($user, 200);
    }
}
