<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            $baseURL = config('app.frontend_url');
            $key = $notifiable->getKey();
            $emailHash = sha1($notifiable->getEmailForVerification());

            $signedUrl = URL::temporarySignedRoute(
                'verification.verify', now()->addMinutes(60), ['id' => $key, 'hash' => $emailHash], absolute: false);

            return $baseURL.$signedUrl;
        });

        //
    }
}
