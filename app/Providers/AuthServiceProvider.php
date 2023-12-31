<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerPolicies();

        $frontEndUrl = env('FRONTEND_URL');
        $this->setFrontEndUrlInResetPasswordEmail($frontEndUrl);

        // Implicitly grant "Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user) {
            return $user->hasAllAccess() ? true : null;
        });

        Validator::extend('eng_pdn_ac_lk_email', function ($attribute, $value, $parameters, $validator) {
            // Check if the email address ends with "@eng.pdn.ac.lk"
            return str_ends_with(strtolower($value), '@eng.pdn.ac.lk');
        });

        // Learn when to use this instead: https://docs.spatie.be/laravel-permission/v3/basic-usage/super-admin/#gate-after
//        Gate::after(function ($user) {
//            return $user->hasAllAccess();
//        });

        Gate::define('view-order', function ($user, $order) {
            return $user->id === $order->user_id;
        });
    }

    protected function setFrontEndUrlInResetPasswordEmail($frontEndUrl = '')
    {
        // update url in ResetPassword Email to frontend url
        ResetPassword::createUrlUsing(function ($user, string $token) use ($frontEndUrl) {
            return $frontEndUrl . '/auth/password/email/reset?token=' . $token;
        });
    }
}
