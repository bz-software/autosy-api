<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\SubscriptionRepository;
use App\Enums\SubscriptionStatus;
use Carbon\Carbon;

class EnsureSubscriptionIsActive
{
    public function __construct(
        private SubscriptionRepository $rSubscription
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $trialDays = config('services.autosy.trial_days');

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $subscription = $this->rSubscription->findByUser($user->id);

        /**
         * NÃO TEM ASSINATURA → verificar trial de 30 dias
         */
        if (!$subscription) {
            $trialLimit = Carbon::parse($user->created_at)->addDays($trialDays);

            if (Carbon::now()->lessThanOrEqualTo($trialLimit)) {
                return $next($request);
            }

            return response()->json([
                'message' => 'Trial expired'
            ], 403);
        }

        /**
         * TEM ASSINATURA → validar período pago
         */
        if (!$subscription->current_period_end) {
            return response()->json([
                'message' => 'Subscription period not defined'
            ], 403);
        }

        $periodEnd = Carbon::parse($subscription->current_period_end);

        if (Carbon::now()->lessThanOrEqualTo($periodEnd)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Subscription expired'
        ], 403);
    }
}