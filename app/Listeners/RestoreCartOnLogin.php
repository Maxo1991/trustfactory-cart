<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\CartBackup;

class RestoreCartOnLogin
{
    public function handle(Login $event): void
    {
        $backup = CartBackup::where('user_id', $event->user->id)->first();

        if ($backup && !empty($backup->items)) {
            session()->put('cart', $backup->items);
        }
    }
}

