<?php

namespace App\Http\Controllers\Admin\Concerns;

/**
 * Restricts a query to the logged-in user's company, via a dot-relation
 * path to a model carrying company_id (Vehicle or Driver). No-ops for
 * Super Admin (companyId() returns null), matching the multi-tenant
 * scoping already applied to Driver/Vehicle/Shop/DeliverySchedule.
 */
trait ScopesToCompany
{
    private function scopeToCompany($query, string $relationPath): void
    {
        if ($companyId = auth()->user()->companyId()) {
            $query->whereHas($relationPath, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }
    }
}
