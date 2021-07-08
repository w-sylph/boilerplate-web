<?php

namespace App\Models\Users;

use App\Extenders\Models\BaseModel as Model;
use Illuminate\Notifications\Notifiable;

class Guest extends Model
{
    use Notifiable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    /**
     * @Getters
     */

    public static function getGuest($email) {
    	$guest = static::where('email', $email)->first();

        if (!$guest) {
            $guest = static::create(['email' => $email]);
        }

        return $guest;
    }

    /**
     * @Renders
     */
    public function renderName() {
        return $this->name ?: $this->email;
    }

    /* Return guest label or class style */
    public function renderStatus($column = 'label') {
        $result = 'Guest';

        switch ($column) {
            case 'label':
                $result = 'Guest';
                break;
            case 'class':
                $result = 'bg-warning';
                break;
        }

        return $result;
    }

    public function renderShowUrl() {
        return;
    }
}
