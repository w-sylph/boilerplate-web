<?php

namespace App\Traits;

trait DeviceTokenTrait
{
	/* Device token used for storing push notification identifier and platform */
    public function device_tokens() {
        return $this->morphMany(DeviceToken::class, 'user');
    }
}