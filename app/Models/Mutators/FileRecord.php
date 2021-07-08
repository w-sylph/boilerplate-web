<?php

namespace App\Models\Mutators;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Rutorika\Sortable\SortableTrait;

use App\Traits\FileTrait;
use App\Traits\ListTrait;

class FileRecord extends Model
{
    use HasFactory, FileTrait, ListTrait, SortableTrait;

    protected $guarded = [];

    protected static $sortableField = 'order_column';

    /**
     * Relationships
     */

    public function parent() {
        return $this->morphTo()->withTrashed();
    }

    /**
     * Scopes
     */
    public function scopeUniqueHash($query) {
        $ids = static::select(['id', 'md5'])->groupBy('md5')->pluck('id')->toArray();
        return $query->whereIn('id', $ids)->orWhereNull('md5');
    }

    /**
     * Helpers
     */

    public static function formatItem($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'mime' => $item->mime,
            'size' => $item->size,
            'extension' => $item->extension,
            'order_column' => $item->order_column,
            'path' => $item->renderFilePath(),
        ];
    }
}
