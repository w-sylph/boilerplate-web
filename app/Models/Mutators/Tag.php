<?php

namespace App\Models\Mutators;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $guarded = [];
	
    public function parent() {
    	return $this->morphTo();
    }
}
