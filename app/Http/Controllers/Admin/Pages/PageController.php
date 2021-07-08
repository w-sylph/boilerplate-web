<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Controllers\HasPermissionTrait;
use App\Http\Requests\Admin\Pages\PageStoreRequest;

use App\Models\Pages\Page;

class PageController extends Controller
{
    use HasPermissionTrait;

    protected $indexView = 'admin.pages.index';
    protected $createView = 'admin.pages.create';
    protected $showView = 'admin.pages.show';
    protected $guard = 'admin';

    public function __construct()
    {
        $this->checkPermissions();
    }

    public function getPermissions()
    {
        return [
            'admin.pages.crud'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view($this->indexView, [
            //
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->createView, [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageStoreRequest $request)
    {
        $item = Page::store($request);

        $message = "You have successfully created {$item->renderName()}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id, $slug = null)
    {
        $item = Page::withTrashed()->findOrFail($id);

        if ($this->guard == 'guest' && !$slug) {
            return redirect()->to($item->renderShowUrl('guest'));
        }

        return view($this->showView, [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageStoreRequest $request, $id)
    {
        $item = Page::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";

        $item = Page::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function archive($id)
    {
        $item = Page::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->renderName()}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $item = Page::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->renderName()}",
        ]);
    }
}
