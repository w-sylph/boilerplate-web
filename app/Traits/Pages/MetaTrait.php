<?php

namespace App\Traits\Pages;

use Illuminate\Support\Str;
use App\Actions\Facebook\ScrapePage;

use App\Models\Pages\MetaTag;

trait MetaTrait
{
    /* Relationship */
    public function meta() {
        return $this->morphOne(MetaTag::class, 'page');
    }

    /* Get Meta Tag */
    public function getMeta() {
    	$item = $this->meta;
    	$result = null;

    	if ($item) {
    		$result = [
	    		'title' => $item->title,
	    		'description' => $item->description,
	    		'keywords' => $item->keywords,
	    		'og_title' => $item->og_title,
	    		'og_description' => $item->og_description,
	    		'path' => $item->renderFilePath(),
                'slug' => $this->slug,
	    	];
    	}

    	return $result;
    }

    /* Store Meta Tags */
    public function storeMeta($request) {
    	$vars = [
    		'title' => $request->input('meta_title'),
    		'description' => $request->input('meta_description'),
    		'keywords' => $request->input('meta_keywords'),
    		'og_title' => $request->input('meta_og_title'),
    		'og_description' => $request->input('meta_og_description'),
    	];

        if ($request->filled('slug')) {
            $this->slug = Str::slug($request->input('slug'), '-');
            $this->save();
        }

    	$item = $this->meta;

    	if ($item) {
    		$item->update($vars);
            $message = 'Meta tags has been updated';
    	} else {
    		$item = $this->meta()->create($vars);
            $message = 'Meta tags has been created';
    	}

    	if ($request->hasFile('meta_og_image')) {
            $item->storeFile($request->file('meta_og_image'), 'file_path', 'meta-tags-images');
        }

        if ($url = $item->renderShowUrl()) {
            $ogScrapeSuccess = false;

            try {
                $ogScrapeSuccess = (new ScrapePage)->execute($url);
            } catch (Exception $e) {
                $ogScrapeSuccess = false;

                activity()
                    ->causedBy($request->user())
                    ->performedOn($this)
                    ->log('Error occured, was not able to scrape the Open Graph details');
            }

            if ($ogScrapeSuccess) {
                activity()
                        ->causedBy($request->user())
                        ->performedOn($this)
                        ->log('Open Graph details has been scraped');
            }
        }

        activity()
            ->causedBy($request->user())
            ->performedOn($this)
            ->log($message);

        return $item;
    }

    /* Render Meta Columns */
    public function renderMeta($column) {
        $result = null;

        if ($meta = $this->meta) {
            $result = $meta[$column];
        }

        if (!$result) {
            $result = $this->getDefaultMetaData($column);
        }

        return $result;
    }

    /* Display meta image */
    public function renderMetaImage() {
        $result = null;

        if ($meta = $this->meta) {
            $result = $meta->renderFilePath();
        }

        if (!$result) {
            $result = $this->getDefaultMetaImage();
        }

        return $result;
    }

    /* Default meta data when no values are available on specific columns */
    public function getDefaultMetaData($column) {
        $result = null;

        switch ($column) {
            case 'title':
            case 'og_title':
                    $result = $this->renderMetaTitle();
                break;
            case 'description':
            case 'og_description':
                    $result = $this->renderMetaDescription();
                break;
        }

        return $result;
    }

    /* Default page name */
    public function renderMetaTitle() {
        return null;
    }

    /* Default page description */
    public function renderMetaDescription() {
        return null;
    }

    /* Default meta image when no image is available */
    public function getDefaultMetaImage() {
        return null;
    }
}