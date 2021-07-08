<?php

namespace App\Actions\Files;

use Storage;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

use App\Rules\Image as ImageRule;

class FileUploader
{
	protected $name;
	protected $size;
	protected $mime;
	protected $extension;
	protected $md5;
    protected $path;
    protected $defaultResize = 700;

	/**
	 * Resize and store image to storage
	 * @param  Illuminate\Http\UploadedFile $file uploaded file
	 * @param  string      $directory directory to upload file
	 * @param  int|integer $resizeTo  compression size of image
	 * @return string                 file storage path
	 */
	public function execute($file, $directory) {
        $this->setAttributes($file);
        $this->setFileHash($file);

        if ($this->shouldCompress($file)) {
            $this->path = $this->store($file, $directory);
        } else if (method_exists($file, 'store')) {
            $this->path = $file->store($directory, config('web.filesystem'));
        } else {
            $this->path = $this->store($file, $directory);
        }

        return $this->path;
    }

    /**
     * Compress image using intervention
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     * @return string file storage path
     */
    public function store($file, $directory) {
        $shouldResize = false;
        $extension = $this->extension;

        /* Resize image dimension to declared */
        $defaultResize = $this->defaultResize;

        $resizeWidth = null;
        $resizeHeight = null;

        $optimizedImage = $this->getImage($file);

        $width = $optimizedImage->getWidth();
        $height = $optimizedImage->getHeight();

        /* Check if whether height or width should be resize depending which is larger */
        if ($width >= $height) {
            if ($width > $defaultResize) {
                $resizeWidth = $defaultResize;
                $shouldResize = true;
            }
        } else {
            if ($height > $defaultResize) {
                $resizeHeight = $defaultResize;
                $shouldResize = true;
            }
        }

        /* Determine whether if the image should be resized */
        if ($shouldResize) {
            $optimizedImage->resize($resizeWidth, $resizeHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        /* Set root depending on file system */
        switch (config('web.filesystem')) {
            case 's3':
                    $root = null;
                break;

            default:
                    $root = 'public/';
                break;
        }

        /* Generate random name */
        $filename = uniqid() . Str::random(40) . '.' . $extension;

        if (! $this->name) {
            $this->name = $filename;
        }

        /* Store request image */
        $directory_path = $root . $directory;

        if (! Storage::exists($directory_path)) {
            Storage::makeDirectory($directory_path);
        }

        /* File storage path */
        $filePath = $directory . '/' . $filename;

        /* Path to store image */
        $storePath = 'storage/' . $directory . '/' . $filename;

        /* Save image intervention with modification applied */
        $optimizedImage->save($storePath);

        return $filePath;
    }

    /**
     * Check if file should be compressed
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     * @return boolean
     */
    protected function shouldCompress($file) {
        $validator = Validator::make(['file' => $file], [
            'file' => [ new ImageRule ]
        ]);

        return $validator->passes();
    }

    /**
     * Get image attribute such as filename, size, mime and extension
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     */
    public function setAttributes($file) {
        if (method_exists($file, 'getClientOriginalName')) {
            $this->name = $file->getClientOriginalName();
        }

        if (method_exists($file, 'getMimeType')) {
            $this->mime = $file->getMimeType();
        }

        if (method_exists($file, 'getSize')) {
            $this->size = $file->getSize();
        }

        /* Set image extension */
        if (method_exists($file, 'getClientOriginalExtension') && $file->getClientOriginalExtension()) {
            $this->extension = $file->getClientOriginalExtension();
        }
    }

    /**
     * Set default image compression dimesion size
     * @param int $value compress to dimesion size
     */
    public function setCompressionSize(int $value) {
        if ($value > 0) {
            $this->defaultResize = $value;
        }
    }

    /**
     * Set md5 of uploaded file to give a unique identifier
     * @param Illuminate\Http\UploadedFile $file uploaded file uploaded file
     * @return  string md5 hash of uploaded file
     */
    public function setFileHash($file) {
        $result = null;
        $validator = Validator::make(['file' => $file], [
            'file' => 'file'
        ]);

        if ($validator->passes()) {
            $result = md5_file($file);
        } else {
            $result = md5($file);
        }

        return $this->md5 = $result;
    }

    /**
     * Get encoded image
     * @param  Illuminate\Http\UploadedFile $file uploaded file
     * @return Intervention\Image\Facades\Image intervention image
     */
    public function getImage($file) {
        $extension = $this->extension ?? 'jpg';
        return Image::make($file)->encode($extension);
    }

    /* Get file attributes such as filename, size, mime and extension */
    public function getAttributes() {
    	return [
    		'name' => $this->getOrignalName(),
    		'size' => $this->getSize(),
    		'extension' => $this->getExtension(),
    		'mime' => $this->getMimeType(),
            'md5' => $this->getMd5(),
    	];
    }

    /* Get original filename */
    public function getOrignalName() {
    	return $this->name;
    }

    /* Get original file size */
    public function getSize() {
    	return $this->size;
    }

    /* Get file mime ex. image/jpeg, text/csv */
    public function getMimeType() {
    	return $this->mime;
    }

    /* Get file extension ex. jpg, png, gif, docx, pdf */
    public function getExtension() {
    	return $this->extension;
    }

    /* Get file md5 hash */
    public function getMd5() {
        return $this->md5;
    }

    /* Get file storage path */
    public function getFilePath() {
    	return $this->path;
    }
}