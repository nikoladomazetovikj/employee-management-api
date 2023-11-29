<?php

namespace App\Http\Controllers\Api;

use App\Events\InquireNotifyEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inquire\CreateRequest;
use App\Http\Requests\Inquire\ListRequest;
use App\Http\Requests\Inquire\ShowRequest;
use App\Http\Requests\Inquire\UpdateRequest;
use App\Http\Resources\InquireResource;
use App\Models\Inquire;
use App\Models\InquireType;
use App\Models\Status;
use App\Models\User;
use App\Notifications\InquireRequestNotification;
use App\Notifications\NotifyInquireStatus;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Ulid;

class InquireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListRequest $request)
    {
        //get user company

        $managerCompanyId = $request->user()->company()->first()->pivot->company_id;

        // select from view
        $inquires = DB::table('inquire_details')->where('company_id', $managerCompanyId)->get();

        return response()->json($inquires);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $userId = $request->user()->id;

        $inquireId = (new Ulid)->toBase32();

        $inquire = Inquire::create([
            'inquire_id' => $inquireId,
            'user_id' => $userId,
            'status_id' => \App\Enums\Status::PENDING->value,
            'type' => $request->type,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        event(new InquireNotifyEvent($inquire, $request->user()->company[0]->id, $request->user()));

        return new InquireResource($inquire);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inquire $inquire, ShowRequest $request)
    {
        return new InquireResource($inquire);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Inquire $inquire)
    {
        $inquire->update(['status_id' => $request->status_id]);

        $user = User::find($inquire->user_id);

        $status = Status::find($request->status_id);

        $statusName = ucfirst(strtolower($status->name));

        $type = InquireType::find($inquire->type);

        $user->notify(new NotifyInquireStatus($inquire, $statusName, $user, $type));

        return new InquireResource($inquire);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquire $inquire)
    {
        //
    }
}
