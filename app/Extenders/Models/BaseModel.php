<?php

namespace App\Extenders\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Traits\SearchableTrait;
use App\Traits\ArchiveableTrait;
use App\Traits\RenderTrait;
use App\Traits\DateTrait;
use App\Traits\PaginationTrait;
use App\Traits\ListTrait;

class BaseModel extends Model
{
    use ArchiveableTrait, SearchableTrait, RenderTrait, DateTrait, LogsActivity, PaginationTrait, ListTrait;

    protected $guarded = [];

    protected static $logAttributes = [];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = false;

    public function getDescriptionForEvent(string $eventName): string {
        return "{$this->renderLogName()} has been {$eventName}";
    }

    public function archiveErrorMessage() {
        $result = $this->renderLogName();

        if ($this->isArchiveable()) {
            $result .= ' has already been archived.';
        } else {
            $result .= ' cannot be archived.';
        }

        return $result;
    }

    public function restoreErrorMessage() {
        $result = $this->renderLogName();

        if ($this->isRestorable()) {
            $result .= ' has already been restored.';
        } else {
            $result .= ' cannot be restored.';
        }

        return $result;
    }
}
