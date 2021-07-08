<?php

namespace App\Http\Controllers\Admin\Samples;

use App\Extenders\Controllers\Samples\SampleItemController as Controller;

use App\Traits\Controllers\HasPermissionTrait;

class SampleItemController extends Controller
{
    use HasPermissionTrait;

    protected $indexView = 'admin.samples.index';
    protected $createView = 'admin.samples.create';
    protected $showView = 'admin.samples.show';

    public function __construct()
    {
        $this->checkPermissions();
    }

    protected function getPermissions()
    {
        return [
            'admin.sample-items.crud'
        ];
    }
}
