<?php

namespace App\Handlers\Events;

use App\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Access;

class AuthLoginEventHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  User $user
     * @param  $remember
     * @return void
     */
		public function handle(User $user, $remember)
    {
			$access = new Access;
			$access->user_id = $user->id;
			$access->accessed_at = Carbon::now()->toDateTimeString();
			$access->ip = $this->request->getClientIp();
			$access->save();
    }
}
