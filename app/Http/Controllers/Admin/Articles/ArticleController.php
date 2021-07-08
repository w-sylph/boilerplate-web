<?php

namespace App\Http\Controllers\Admin\Articles;

use App\Extenders\Controllers\Articles\ArticleController as Controller;

use App\Traits\Controllers\HasPermissionTrait;

class ArticleController extends Controller
{
    use HasPermissionTrait;

    protected $indexView = 'admin.articles.index';
    protected $createView = 'admin.articles.create';
    protected $showView = 'admin.articles.show';

    public function __construct()
    {
        $this->checkPermissions();
    }

    public function getPermissions()
    {
        return [
            'admin.articles.crud'
        ];
    }
}
