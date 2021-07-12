<?php

namespace App\Extenders\Controllers\Samples;

use App\Http\Controllers\Controller;
use App\Extenders\Controllers\Samples\SampleItemFetchController;

use Illuminate\Http\Request;

use App\Models\Samples\SampleItem;

use App\Traits\Controllers\Exportable;
use App\Exports\Samples\SampleItemExport;

use App\Actions\Samples\SampleApprove;
use App\Actions\Samples\SampleDeny;

use App\Http\Requests\Admin\Samples\SampleItemStoreRequest;
use App\Http\Requests\Admin\Samples\SampleItemDenyRequest;

class SampleItemController extends Controller
{
    use Exportable;

    /**
     * Eloquent to export, fetch controller and export classes
     * @return array
     */
    protected function exportable(): array {
        return [
            'controller' => SampleItemFetchController::class,
            'model' => SampleItem::class,
            'export' => SampleItemExport::class,
        ];
    }

    protected $indexView;
    protected $createView;
    protected $showView;
    protected $guard = 'admin';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filterStatus = json_encode(SampleItem::getStatus());

        return view($this->indexView, [
            'filterStatus' => $filterStatus,
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
    public function store(SampleItemStoreRequest $request)
    {
        $item = SampleItem::store($request);

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
        $item = SampleItem::withTrashed()->findOrFail($id);

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
    public function update(SampleItemStoreRequest $request, $id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->renderName()}";

        $item = SampleItem::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function archive($id)
    {
        $item = SampleItem::withTrashed()->findOrFail($id);
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
        $item = SampleItem::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->renderName()}",
        ]);
    }

    /**
     * Set status as approved
     * @param  Request       $request [description]
     * @param  SampleApprove $action  [description]
     * @param  [type]        $id      [description]
     * @return [type]                 [description]
     */
    public function approve(Request $request, SampleApprove $action, $id)
    {
        $item = $action->execute($id, $request->user());
        $message = "You has been marked as approved {$item->renderName()}";

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Set status as denied
     * @param  SampleItemDenyRequest $request [description]
     * @param  SampleDeny            $action  [description]
     * @param  [type]                $id      [description]
     * @return [type]                         [description]
     */
    public function deny(SampleItemDenyRequest $request, SampleDeny $action, $id)
    {
        $item = $action->execute($id, $request->input('reason'), $request->user());
        $message = "You has been marked as denied {$item->renderName()}";

        return response()->json([
            'message' => $message,
        ]);
    }

    
}
