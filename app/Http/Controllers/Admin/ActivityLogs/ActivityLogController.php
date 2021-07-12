<?php

namespace App\Http\Controllers\Admin\ActivityLogs;

use App\Extenders\Controllers\ActivityLogs\ActivityLogController as Controller;

use App\Traits\Controllers\HasPermissionTrait;

class ActivityLogController extends Controller
{
	use HasPermissionTrait;

    protected $indexView = 'admin.activity-logs.index';

	public function __construct()
    {
        $this->checkPermissions();
    }

    protected function getPermissions()
    {
        return [
            'admin.activity-logs.crud'
        ];
    }
}
