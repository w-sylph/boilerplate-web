<?php

namespace App\Traits;

use Storage;
use Validator;

use App\Actions\Files\FileUploader;
use App\Actions\Files\BinaryImageUploader;

use App\Models\Mutators\FileRecord;

trait ManyFilesTrait
{
    /* Images */
    public function files() {
        return $this->morphMany(FileRecord::class, 'parent');
    }

    /* Get formatted list of files */
    public function getFiles() {
        return FileRecord::formatList($this->files()->orderBy('order_column', 'asc')->get());
    }

    /* Get file attributes such as storage path, filename, size, mime and extension */
    public function getFileVars($file, $directory = 'files', $fileColumn = 'file_path', $dimension = 0) {
        $vars = [];

        $action = new FileUploader;
        $action->setCompressionSize($dimension);
        $action->execute($file, $directory);
        $vars = $action->getAttributes();
        $vars[$fileColumn] = $action->getFilePath();

        return $vars;
    }

    /* Add files to storage and relate to model */
    public function addFiles($files, $directory = 'files', $fileColumn = 'file_path', $dimension = 0) {
        $result = 0;

        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $vars = $this->getFileVars($file, $directory, $fileColumn, $dimension);

                /* Create the image data */
                $file = $this->files()->create($vars);
            }

            $result++;
        }

        return $result;
    }

    public function associateFiles($fileIds) {
        $result = 0;
        $files = FileRecord::whereIn('id', $fileIds)->whereNull('parent_id')->whereNull('parent_type')->get();

        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $file->update([
                    'parent_id' => $this->id,
                    'parent_type' => static::class
                ]);

                $result++;
            }
        }

        return $result;
    }

    public function addMedia($fileIds) {
        $result = 0;
        $files = FileRecord::whereIn('id', $fileIds)->get();

        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $this->files()->create($file->replicate()->toArray());
                $result++;
            }
        }

        return $result;
    }

    /* Add base64 files to storage and relate to model */
    public function addBinaryImage($files, $fileColumn = 'file_path', $directory = 'files', $imageSize = 0) {
        $result = 0;

        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $action = new BinaryImageUploader;
                $action->setCompressionSize($imageSize);
                $action->execute($file, $directory);
                $vars = $action->getAttributes();
                $vars[$fileColumn] = $action->getFilePath();
                $this->files()->create($vars);
            }

            $result++;
        }

        return $result;
    }

    /* Add an image and save image path to multiple picture model */
    public static function batchAddExistingFiles($files, $items, $directory = 'files', $fileColumn = 'file_path') {
        $count = 0;
        $fileVars = [];

        if ($files && count($files) > 0) {
            foreach ($files as $file) {
                $vars = $this->getFileVars($file, $directory, $dimension);
                $fileVars[] = $vars;
            }
        }

        foreach ($items as $item) {
            $result = $item->addExistingFiles($fileVars);

            if ($result) {
                $count++;
            }
        }

        return $count;
    }

    /* Add an image and save image path to multiple picture model */
    public function addExistingFiles(array $files) {
        $result = false;

        if ($files && count($files) > 0) {

            foreach ($files as $file) {

                /* Create the image data */
                $this->files()->create($file);

            }

            $result = true;
        }

        return $result;
    }

    /* Return controller url for removing of files */
    public function renderRemoveFileUrl() {
        return route('file.remove', $this->id);
    }

    /* Return controller url for sorting of files */
    public function renderSortUrl() {
        return route('sort.file');
    }


    /* Return thumbnail of the picture collection of item */
    public function renderThumbnailPath() {
        $result = $this->getDefaultThumbnail();

        if ($file = $this->getFirstFile()) {
            $result = $file->renderFilePath();
        }

        return $result;
    }

    /* Return default image when no picture is available */
    protected function getDefaultThumbnail() {
        return null;
    }

    public function getFirstFile() {
        return $this->files()->orderBy('order_column', 'asc')->first();
    }

    public function getFirstFileUrl() {
        $result = $this->getFirstFile();

        if ($result) {
            $result = $result->file_path;
        }

        return $result;
    }

    public static function getMediaLibrary($id = null) {
        $result = [];
        $items = FileRecord::uniqueHash()->where('parent_type', static::class);

        $collections = $items->paginate(12);
        $items = $collections->items();

        foreach ($items as $item) {
            $result[] = FileRecord::formatItem($item);
        }

        $pagination = collect($collections)->only([
            'per_page',
            'prev_page_url',
            'next_page_url',
            'first_page_url',
            'last_page_url',
            'current_page',
            'last_page',
            'path',
            'total',
            'from',
            'to',
        ]);

        return [
            'items' => $result,
            'pagination' => $pagination
        ];
    }
}