<?php

namespace App\Imports\Pages;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Storage;
use Illuminate\Support\Str;

use App\Models\Pages\Page;
use App\Models\Pages\PageItem;

class PageItemImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * The collection method will receive a collection of rows. A row is an array filled with the cell values.
     * @param  Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
    	foreach ($rows as $row) {
    		$page = Page::where('slug', Str::slug($row['parent_slug'], '-'))->first();
            $pageItem = $page
                ->page_items()
                ->where('slug', Str::slug($row['slug'], '-'))
                ->first();

    		if ($page && ! $pageItem) {
	    		$page->page_items()->create([
	    			'slug' => Str::slug($row['slug'], '-'),
		            'name' => $row['name'],
	                'content' => $row['content'],
		            'type' => $row['type'],
	    		]);
    		}
    	}
    }

    /**
     * The most ideal situation (regarding time and memory consumption) you will find when combining batch inserts and chunk reading.
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Importing a large file can have a huge impact on the memory usage, as the library will try to load the entire sheet into memory.
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}