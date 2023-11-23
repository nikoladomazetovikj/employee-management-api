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
use App\Notifications\UserCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::with('addressable', 'phones', 'company')->whereNot('id', $request->user()->id)->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $data = $request->safe();

        $user = new User();

        $password = '123456789';

        DB::transaction(function () use ($data, &$user, $request, $password) {

            $user->fill($data->except('address', 'phone', 'vacation_days'));

            $user->password = Hash::make($password);

            $user->save();

            if ($request->has('address')) {
                $address = $request->input('address');
                $user->addressable()->create($address);
            }

            if ($request->has('phone')) {
                $phone = $request->input('phone');
                $user->phones()->create($phone);
            }

            $company = $request->user()->load('company');

            $user->company()
                ->attach($company->company[0]->id,
                    ['role_id' => Role::EMPLOYEE->value, 'vacation_days' => $request->vacation_days]);


            $user->notify(new UserCreatedNotification($password, $user, $company->company[0]->name));
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

    public function deletedUsers()
    {
        $users = User::with('addressable', 'phones', 'company')->onlyTrashed()->get();

        return UserResource::collection($users);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        $user->restore();

        return response()->noContent();
    }
}
