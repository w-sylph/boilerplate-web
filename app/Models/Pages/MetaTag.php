<?php

namespace App\Models\Pages;

use App\Extenders\Models\BaseModel as Model;

use App\Traits\FileTrait;

class MetaTag extends Model
{
	use FileTrait;

    public function page() {
        return $this->morphTo()->withTrashed();
    }

    public function renderName() {
    	return optional($this->page)->renderName();
    }

    public function renderShowUrl() {
    	return optional($this->page)->renderShowUrl();
    }
}
