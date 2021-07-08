<?php

namespace App\Models\Mutators;

use App\Extenders\Models\BaseModel as Model;
use Illuminate\Notifications\Notifiable;

use App\Events\Addresses\AddressStoring;

class Address extends Model
{
	use Notifiable;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'creating' => AddressStoring::class,
        'updating' => AddressStoring::class,
    ];

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
            'telephone_number' => $this->telephone_number,
            'mobile_number' => $this->mobile_number,
            'unit_number' => $this->unit_number,
            'primary_address' => $this->primary_address,
            'building_number' => $this->building_number,
            'secondary_address' => $this->secondary_address,
            'province' => $this->province,
            'city' => $this->city,
            'barangay' => $this->barangay,
            'zip_code' => $this->zip_code,
            'company' => $this->company,
            'type' => $this->type,
        ];
    }

    /**
     * @Relationship
     */
    public function parent() {
    	return $this->morphTo('parent');
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $user = null) {
        $vars = $request->only([
            'type', 'first_name', 'last_name', 'email',
            'mobile_number_country_code', 'mobile_number', 'telephone_number',
            'unit_number', 'primary_address', 'building_number', 'secondary_address',
            'province_code', 'city_code', 'barangay_code',
            'zip_code',
            'company', 'notes',
        ]);

        if ($user) {
            $vars['parent_id'] = $user->id;
            $vars['parent_type'] = get_class($user);
        }

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /* Mark address as default */
    public function markAsDefault($user) {
        if (!$this->trashed()) {
            $this->update(['default' => 1]);
            $user->addresses()->withTrashed()->where('id', '!=', $this->id)->update(['default' => 0]);
        }
    }

    /**
     * Getters
     */
    public function getOptions() {
        $showForm = false;
        $showAddressList = true;
        $duplicateAddress = false;

        if ($this->duplicate) {
            $showForm = false;
            $showAddressList = false;
            $duplicateAddress = true;
        } else {
            $showForm = $this->address_id == false;
            $showAddressList = $this->address_id == true;
            $duplicateAddress = false;
        }

        return [
            'showForm' => $showForm,
            'showAddressList' => $showAddressList,
            'duplicateAddress' => $duplicateAddress,
        ];
    }

    public function getValues($prefix = null) {
        $vars = [];
        $addressVars = $this->only([
            'first_name', 'last_name',
            'mobile_number_country_code', 'mobile_number', 'telephone_number',
            'unit_number', 'primary_address', 'building_number', 'secondary_address',
            'province_code', 'city_code', 'barangay_code', 'zip_code',
            'company',
            'notes',
        ]);

        if ($prefix) {
            $prefix .= '_';
        }

        foreach ($addressVars as $key => $addressVar) {
            $vars[$prefix . $key] = $addressVar;
        }

        return $vars;
    }

    /**
     * @Checker
     */
    public function isDefault() {
        return $this->default ? true : false;
    }

    /**
     * @Renders
     */
    public function renderName() {
        return $this->primary_address;
    }

    public function renderUserName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function renderContactDetails() {
        $result = [];

        if ($value = $this->email) {
            $result[] = $value;
        }

        if ($value = $this->mobile_number) {
            $result[] = $value;
        }

        if ($value = $this->telephone_number) {
            $result[] = $value;
        }
        return implode(' / ', $result);
    }

    public function renderAddress() {
    	$result = [];

        if ($this->building_number) {
            $result[] = $this->building_number;
        }

    	if ($this->unit_number) {
    		$result[] = $this->unit_number;
    	}

    	if ($this->primary_address) {
    		$result[] = $this->primary_address;
    	}

        if ($this->secondary_address) {
            $result[] = $this->secondary_address;
        }

    	if ($this->barangay) {
    		$result[] = 'Brgy. ' . $this->barangay;
    	}

    	if ($this->city || $this->zip_code) {
            $sentence = '';

            if ($this->city) {
                $sentence .= $this->city . ' ';
            }

            if ($this->zip_code) {
                $sentence .= $this->zip_code . ' ';
            }

            if ($this->province) {
                $sentence .= $this->province . ' ';
            }

    		$result[] = preg_replace('/\s+/', ' ', $sentence);
    	}

    	return implode(', ', $result);
    }

    public function renderShowUrl($prefix = 'admin') {
        return optional($this->parent)->renderShowUrl($prefix);
    }

    public function renderDefaultUrl($prefix = 'admin') {
        return route($prefix . '.addresses.default', $this->id);
    }

    public function renderUpdateUrl($prefix = 'admin') {
        return route($prefix . '.addresses.update', $this->id);
    }

    public function renderFetchUrl($prefix = 'admin') {
        return route($prefix . '.addresses.fetch-item', $this->id);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        return route($prefix . '.addresses.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        return route($prefix . '.addresses.restore', $this->id);
    }
}
