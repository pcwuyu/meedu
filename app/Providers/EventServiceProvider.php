<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) 杭州白书科技有限公司
 */

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PaymentSuccessEvent' => [
            '\App\Listeners\PaymentSuccessEvent\OrderPaidDeliverListener',
            '\App\Listeners\PaymentSuccessEvent\OrderPaidNotificationListener',
            '\App\Listeners\PaymentSuccessEvent\OrderPaidStatusChangeListener',
            '\App\Listeners\PaymentSuccessEvent\PromoCodeListener',
            '\App\Listeners\PaymentSuccessEvent\InviteUserRewardListener',
            '\App\Listeners\PaymentSuccessEvent\Credit1RewardListener',
        ],
        'App\Events\OrderCancelEvent' => [
            '\App\Listeners\OrderCancelEvent\PromoCodeResumeListener',
            '\App\Listeners\OrderCancelEvent\InviteBalanceResumeListener',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\\QQ\\QqExtendSocialite@handle',
        ],
        'App\Events\UserRegisterEvent' => [
            'App\Listeners\UserRegisterEvent\WelcomeMessageListener',
            'App\Listeners\UserRegisterEvent\RegisterIpRecordListener',
            'App\Listeners\UserRegisterEvent\RegisterCredit1RewardListener',
            'App\Listeners\UserRegisterEvent\RegisterSendVipListener',
        ],
        'App\Events\UserLoginEvent' => [
            'App\Listeners\UserLoginEvent\LoginRecordListener',
        ],
        'App\Events\UserInviteBalanceWithdrawHandledEvent' => [
            'App\Listeners\UserInviteBalanceWithdrawHandledEvent\NotifyListener',
            'App\Listeners\UserInviteBalanceWithdrawHandledEvent\RefundBalanceListener',
        ],
        'App\Events\UserVideoWatchedEvent' => [
            'App\Listeners\UserVideoWatchedEvent\UserVideoWatchedListener',
            'App\Listeners\UserVideoWatchedEvent\UserVideoWatchedCredit1RewardListener',
        ],
        'App\Events\UserCourseWatchedEvent' => [
            'App\Listeners\UserCourseWatchedEvent\UserCourseWatchedListener',
            'App\Listeners\UserCourseWatchedEvent\UserCourseWatchedCredit1RewardListener',
        ],
        'App\Events\AppConfigSavedEvent' => [],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
