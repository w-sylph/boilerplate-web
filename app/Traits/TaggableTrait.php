<?php

namespace App\Traits;

use App\Models\Mutators\Tag;

trait TaggableTrait 
{
    public function tags() {
        return $this->morphMany(Tag::class, 'parent');
    }

    public function storeTags($request, $column = 'tags') {
        $count = 0;

        if ($request->filled($column)) {
            foreach ($request->input($column) as $item) {
                $result = $this->tags()->where('id', $item)->where('parent_type', static::class)->first();

                if (!$result) {
                    $this->tags()->create([
                        'name' => $item,
                    ]);

                    $count++;
                }
            }
        }

        return $count;
    }

    public static function getTags() {
        return Tag::where('parent_type', static::class)->get();
    }

    public function getTagIds() {
        return $this->tags()->pluck('id')->toArray();
    }
}