<?php

namespace App\Models\Users;

use App\Extenders\Models\BaseUser as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\Web\Auth\ResetPassword;
use App\Notifications\Web\Auth\VerifyEmail;
use Password;

use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    const FILLABLE = [
        'first_name', 'last_name',
        'email', 'username',
        'birthday', 'gender',
        'telephone_number', 'telephone_number_country_code',
        'mobile_number', 'mobile_number_country_code',
    ];

    const LIST_COLUMN = ['id', 'first_name', 'last_name'];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * @Mutators
     */
    public static function formatItem($item) {
        return [
            'id' => $item->id,
            'name' => $item->renderName(),
        ];
    }

    /**
     * @Relationships
     */

    /**
     * Overrides default reset password notification
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    /* Overrides default verification notification */
    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function device_tokens() {
        return $this->morphMany(DeviceToken::class, 'user');
    }

    public function providers() {
        return $this->morphMany(SocialiteProvider::class, 'user');
    }

    /* Overrides default forgot password */
    public function broker() {
        return Password::broker('users');
    }

    /**
     * JWT Configurations
     */
    public function getJWTCustomClaims(): array {
        return [
            'guard' => 'web',
        ];
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'gender' => $this->gender,
            'mobile_number' => $this->mobile_number,
            'telephone_number' => $this->telephone_number,
        ];
    }

    /**
     * @Getters
     */


    /**
     * @Renders
     */
    public function renderAge() {
        return $this->birthday->age;
    }

    public function renderMobileNumber() {
        return '(+' . $this->mobile_number_country_code . ')' . $this->mobile_number;
    }

    /* User Verification Status */
    public function renderStatus($column = 'label') {
        $result = $this->email_verified_at ? 'Verified' : 'Unverified';

        switch ($column) {
            case 'label':
                $result = $this->email_verified_at ? 'Verified' : 'Unverified';
                break;
            case 'class':
                $result = $this->email_verified_at ? 'bg-success' : 'bg-danger';
                break;
        }

        return $result;
    }

    public function renderShowUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return route($prefix . '.profiles.show');
        }

        return route($prefix . '.users.show', $this->id);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return;
        }

        return route($prefix . '.users.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        if (in_array($prefix, ['web'])) {
            return;
        }

        return route($prefix . '.users.restore', $this->id);
    }
}