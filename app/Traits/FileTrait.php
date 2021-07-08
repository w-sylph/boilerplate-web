<?php

namespace App\Traits;

use App\Actions\Files\FileUploader;
use App\Actions\Files\BinaryImageUploader;

use Storage;
use App\Helpers\FileHelper;

trait FileTrait
{
    /* Default image column */
    public function getDefaultFileColumn() {
        return 'file_path';
    }

    /* Store Image */
	public function storeFile($file, $fileColumn = 'file_path', $directory = 'images', $imageSize = 0) {
        $action = new FileUploader;
        $action->setCompressionSize($imageSize);
        $this[$fileColumn] = $action->execute($file, $directory);

        $this->save();
	}

    /* Store base64 image to storage and column */
    public function storeBinaryImage($file, $fileColumn = 'file_path', $directory = 'images', $imageSize = 0) {
        $action = new BinaryImageUploader;
        $action->setCompressionSize($imageSize);
        $this[$fileColumn] = $action->execute($file, $directory);
        $this->save();
    }

    /* Store image and return image path */
    public static function saveFile($file, $directory = 'images', $imageSize = 0) {
        $action = new FileUploader;
        $action->setCompressionSize($imageSize);
        return $action->execute($file, $directory);
    }

    /* Return image path */
    public function renderFilePath($fileColumn = null) {
        if (!$fileColumn) {
            $fileColumn = $this->getDefaultFileColumn();
        }

        $path = $this->getDefaultFile();

        if ($this[$fileColumn]) {
            $path = $this->getFileUrl($this[$fileColumn]);
        }

        return $path;
    }

    /* Return default image if not image is available */
    protected function getDefaultFile() {
        return null;
    }

    protected function getFileUrl($path) {
        $url = url('/');

        switch (config('web.filesystem')) {
            case 's3':
                    $url = null;
                break;
        }

        return $url . Storage::url($path);
    }
}

