<?php

namespace App\Models\Articles;

use App\Extenders\Models\BaseModel as Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Rutorika\Sortable\SortableTrait;

use App\Traits\Pages\MetaTrait;
use App\Traits\FileTrait;
use App\Traits\ManyFilesTrait;

class Article extends Model
{
    use MetaTrait, FileTrait, ManyFilesTrait, HasSlug, SortableTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    	'published_at',
    ];

    protected static $sortableField = 'order_column';

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

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
     * @Setters
     */
    public static function store($request, $item = null)
    {
        $vars = $request->only(['name', 'description', 'published_at']);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        $item->storeMeta($request);

        if ($request->hasFile('file_path')) {
            $item->storeFile($request->file('file_path'), 'file_path', 'article-images');
        }

        if ($request->hasFile('images')) {
            $item->addFiles($request->file('images'));
        }

        return $item;
    }

    /**
     * @Render
     */

    public function renderName() {
        return $this->name;
    }

    public function renderShowUrl($prefix = 'admin') {
        $route = $this->id;

        if ($prefix == 'guest') {
            $route = [$this->id, $this->slug];
        }

        return route($prefix . '.articles.show', $route);
    }

    public function renderArchiveUrl($prefix = 'admin') {
        return route($prefix . '.articles.archive', $this->id);
    }

    public function renderRestoreUrl($prefix = 'admin') {
        return route($prefix . '.articles.restore', $this->id);
    }
}
