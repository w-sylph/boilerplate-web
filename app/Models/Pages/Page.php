<?php

namespace App\Models\Pages;

use App\Extenders\Models\BaseModel as Model;

use Storage;
use Illuminate\Support\Str;
use App\Traits\Pages\MetaTrait;

use App\Models\Pages\PageItem;

class Page extends Model
{
    use MetaTrait;

    protected static $logAttributes = ['name', 'slug', 'data'];

    public function getDescriptionForEvent(string $eventName): string {
        return "{$this->name} has been {$eventName}";
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
            'slug' => $this->slug,
        ];
    }

    /**
     * @Relationships
     */
    public function page_items() {
        return $this->hasMany(PageItem::class, 'page_id');
    }

    /**
     * @Getters
     */

    /**
     * Assemble Page Data
     * @return array Page Data
     */
    public function getData() {

        $data = [
            'page' => $this,
            'page_slug' => $this->slug,
            'pageItems' => $this->getPageItems(),
            'view' => "frontend.{$this->slug}"
        ];

        $data = $this->getExtraPageData($data);

        return $data;
    }

    /**
     * Get additional page data
     * @param  App\Page $page
     * @return array
     */
    public function getExtraPageData($data) {
        $array = [];

        switch($this->slug) {
            case 'home':
                    $data['view'] = "frontend.home";
                break;

            default:
                    //
                break;
        }

        return array_merge($data, $array);
    }

    /**
     * Rename Properties for easier access
     * @return stdClass       Collection of modified page items
     */
    public function getPageItems() {

        $items = [];

        foreach ($this->page_items as $pageItem) {
            $items[$pageItem->slug . 'ID'] = $pageItem->id;
            $items[$pageItem->slug] = $pageItem->renderContent();
        }

        return $items;
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null)
    {
        $vars = $request->only(['name', 'slug']);

        if (isset($vars['slug'])) {
            $vars['slug'] = Str::slug($vars['slug'], '-');
        }

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);

            if ($request->filled('content')) {
                foreach ($request->input('content') as $key => $value) {
                    $pageItem = PageItem::find($key);
                    $pageItem->content = $value;

                    if ($pageItem->isDirty()) {
                        $pageItem->save();
                    }
                }
            }

            if ($request->hasFile('content')) {
                foreach ($request->file('content') as $key => $value) {
                    $pageItem = PageItem::find($key);

                    if($pageItem && Storage::exists('public/' . $pageItem->content)) {
                        Storage::delete('public/' . $pageItem->content);
                    }

                    $pageItem->content = $value->store('page-items', config('web.filesystem'));
                    $pageItem->save();
                }
            }
        }

        $item->storeMeta($request);

        return $item;
    }

    public function archiveRestore() {
        $message = 'You have successfully archive area #' . $this->id;
        $action = 1;

        if ($this->trashed()) {
            $message = 'You have successfully restored area #' . $this->id;
            $action = 0;
            $this->restore();
        } else {
            $this->delete();
        }

        return [
            'message' => $message,
            'action' => $action,
        ];
    }

    /**
     * @Render
     */
    public function renderName() {
        return $this->name;
    }

    public function renderShowUrl() {
        return route('admin.pages.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('admin.pages.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('admin.pages.restore', $this->id);
    }

    /* Default page name */
    public function renderMetaTitle() {
        return $this->renderName();
    }
}
