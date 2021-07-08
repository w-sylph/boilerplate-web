<?php

namespace App\Extenders\Controllers\Articles;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\Admin\Articles\ArticleStoreRequest;

use App\Models\Articles\Article;

class ArticleController extends Controller
{
    protected $indexView;
    protected $createView;
    protected $showView;
    protected $guard = 'admin';
    
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
    public function store(ArticleStoreRequest $request)
    {
        $item = Article::store($request);

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
        $item = Article::withTrashed()->findOrFail($id);

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
    public function update(ArticleStoreRequest $request, $id)
    {
        $item = Article::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";

        $item = Article::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function archive($id)
    {
        $item = Article::withTrashed()->findOrFail($id);
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
        $item = Article::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->renderName()}",
        ]);
    }
}
