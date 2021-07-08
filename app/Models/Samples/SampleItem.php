<?php

namespace App\Models\Samples;

use App\Extenders\Models\BaseModel as Model;

use Rutorika\Sortable\SortableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\ManyFilesTrait;
use App\Traits\FileTrait;
use App\Traits\TaggableTrait;

use App\Models\Permissions\Permission;
use App\Models\Users\Admin;

use App\Events\Samples\SampleChanged;

class SampleItem extends Model
{
    use HasFactory, ManyFilesTrait, FileTrait, SortableTrait, TaggableTrait;

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_DENIED = 'DENIED';

    protected static $sortableField = 'order_column';

    protected static $logAttributes = ['name', 'description', 'date', 'dates', 'data'];
    protected static $ignoreChangedAttributes = ['updated_at', 'status', 'reason', 'order_column'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'dates' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    /**
     * @Getters
     */
    public static function getStatus() {
    	return [
    		['value' => static::STATUS_PENDING, 'label' => 'Pending', 'class' => 'bg-info'],
    		['value' => static::STATUS_APPROVED, 'label' => 'Approved', 'class' => 'bg-success'],
            ['value' => static::STATUS_DENIED, 'label' => 'Denied', 'class' => 'bg-danger'],
    	];
    }

    public static function formatItem($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
        ];
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null)
    {
        $vars = $request->only([
            'name', 'description',
            'user_id', 'data',
            'date', 'dates',
            'status',
            'mobile_number', 'telephone_number',
            'order_column',
            'color',
            'zip_code'
        ]);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        if ($request->hasFile('file_path')) {
            $item->storeFile($request->file('file_path'), 'file_path', 'sample-item-images');
        }

        if ($request->hasFile('images')) {
            $item->addFiles($request->file('images'));
        }

        if ($request->filled('files')) {
            // Save the file dropzone as binary to prevent unused files
            // $item->addBinaryImage($request->input('files'));

            // Associate uploaded files based on id
            $item->associateFiles($request->input('files'));
        }

        if ($request->filled('media_files')) {
            $item->addMedia($request->input('media_files'));
        }

        if ($request->filled('tags')) {
            $item->storeTags($request);
        }

        $item->broadcast();

        return $item;
    }

    public function broadcast() {
        if (config('socket.enabled')) {
            broadcast(new SampleChanged($this));
        }
    }

    /**
     * @Checkers
     */
    public function canApprove() {
        if ($this->trashed()) {
            return false;
        }

        switch ($this->status) {
            case static::STATUS_PENDING:
                return true;
            case static::STATUS_APPROVED:
            case static::STATUS_DENIED:
                return false;
        }
    }

    public function canDeny() {
        if ($this->trashed()) {
            return false;
        }

        switch ($this->status) {
            case static::STATUS_PENDING:
                return true;
            case static::STATUS_APPROVED:
            case static::STATUS_DENIED:
                return false;
        }
    }

    /**
     * @Render
     */

    public function renderName() {
        return $this->name;
    }

    public function renderStatus($column = 'label') {
        return static::renderConstants(static::getStatus(), $this->status, $column);
    }

    public function renderApproveUrl($prefix = 'admin') {
        return route($prefix . '.sample-items.approve', $this->id);
    }

    public function renderDenyUrl($prefix = 'admin') {
        return route($prefix . '.sample-items.deny', $this->id);
    }

    public function renderShowUrl($prefix = 'admin') {
        return route($prefix . '.sample-items.show', $this->id);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        return route($prefix . '.sample-items.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        return route($prefix . '.sample-items.restore', $this->id);
    }
}
