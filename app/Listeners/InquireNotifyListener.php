<?php

namespace App\Listeners;

use App\Enums\Role;
use App\Events\InquireNotifyEvent;
use App\Models\InquireType;
use App\Models\User;
use App\Notifications\InquireRequestNotification;
use Illuminate\Support\Facades\DB;

class InquireNotifyListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InquireNotifyEvent $event): void
    {
        $managersIds = DB::table('company_employees')
            ->where('company_id', $event->company)
            ->where('role_id', Role::MANAGER->value)
            ->pluck('user_id')->toArray();

        $usersToNotify = User::whereIn('id', $managersIds)->get();

        $inquireType = InquireType::find($event->inquire->type);

        foreach ($usersToNotify as $user) {
            $user->notify(new InquireRequestNotification($event->inquire, $event->company, $event->user, $user, $inquireType));
        }
    }
}
