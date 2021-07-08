<?php

namespace App\Events\Users;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Avatar;
use Storage;
use Illuminate\Support\Str;

class BaseUserCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        if (! $user->file_path) {
            $name = $user->first_name . ' ' . $user->last_name;
            $public_path = public_path();
            $directory_path = 'tmp';

            if (! Storage::exists('public/' . $directory_path)) {
                Storage::makeDirectory('public/' . $directory_path);
            }

            $path = 'tmp/' . Str::random(40) . '.jpg';
            Avatar::create($name)->setShape('square')->save($public_path . '/storage/' . $path);
            $user->file_path = $path;
        }
    }
}
