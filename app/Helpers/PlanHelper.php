<?php

use App\Models\SubscriptionFeatureSnapshot;
use App\Models\SubscriptionPermissionSnapshot;

function plan_has_feature($userId, $featureSlug)
{
    return SubscriptionFeatureSnapshot::whereHas('feature', function($q) use ($featureSlug) {
        $q->where('slug', $featureSlug);
    })
    ->whereHas('subscription', function($q) use ($userId) {
        $q->where('user_id', $userId)->where('status', 'active');
    })
    ->exists();
}

function plan_has_permission($userId, $permissionSlug)
{
    return SubscriptionPermissionSnapshot::whereHas('permission', function($q) use ($permissionSlug) {
        $q->where('slug', $permissionSlug);
    })
    ->whereHas('subscription', function($q) use ($userId) {
        $q->where('user_id', $userId)->where('status', 'active');
    })
    ->exists();
}
