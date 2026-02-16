<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AcceptTermsAction
{
    public function execute(User $user): void
    {
        DB::transaction(fn () => $user->update([
            'terms_accepted_at' => now(),
        ]));
    }
}