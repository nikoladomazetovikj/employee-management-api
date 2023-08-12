<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $data = $request->safe();

        $user = new User();

        DB::transaction(function () use ($data, &$user, $request) {

            $user->fill($data->except('address', 'phone', 'vacation_days'));

            $user->password = Hash::make('12345678');

            $user->save();

            if ($request->has('address')) {
                $address = $request->input('address');
                $user->addressable()->create($address);
            }

            if ($request->has('phone')) {
                $phone = $request->input('phone');
                $user->phones()->create($phone);
            }

            $user->company()->attach($request->user()->id, ['role_id' => Role::EMPLOYEE->value, 'vacation_days' =>
                $request->vacation_days]);
        });

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, ShowRequest $request)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->has('address')) {
            $address = $request->input('address');
            $user->addressable()->update($address);
        }

        if ($request->has('phone')) {
            $phone = $request->input('phone');
            $user->phones()->update($phone);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteRequest $request)
    {
        $user->delete();

        return response()->noContent();
    }
}