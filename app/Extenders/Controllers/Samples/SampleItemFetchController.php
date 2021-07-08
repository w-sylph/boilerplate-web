<?php

namespace App\Extenders\Controllers\Samples;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Samples\SampleItem;
use App\Models\Users\User;

class SampleItemFetchController extends Controller
{
    /**
     * Set object class of fetched data
     *
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SampleItem;
    }

    /**
     * Custom filtering of query
     *
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         *
         */
        if ($this->request->filled('status')) {
            $query = $query->where('status', $this->request->input('status'));
        }

        return $query;
    }

    /**
     * Custom formatting of data
     *
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'created_at' => $item->renderDate(),
                'status' => $item->renderStatus(),
                'status_class' => $item->renderStatus('class'),
                'canApprove' => $item->canApprove(),
                'canDeny' => $item->canDeny(),
                'trashed' => $item->trashed(),
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     *
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'approveUrl' => $item->renderApproveUrl(),
            'denyUrl' => $item->renderDenyUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $users = User::formatList(User::select(User::LIST_COLUMN)->get());
        $statuses = SampleItem::getStatus();
        $tags = SampleItem::getTags();
        $images = [];

        if ($id) {
            $item = SampleItem::withTrashed()->findOrFail($id);
            $images = $item->getFiles();
            $item->path = $item->renderFilePath();
            $item->status_label = $item->renderStatus();
            $item->status_class = $item->renderStatus('class');
            $item->canApprove = $item->canApprove();
            $item->canDeny = $item->canDeny();
            $item->trashed = $item->trashed();
            $item->tag_ids = $item->getTagIds();

            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'users' => $users,
            'images' => $images,
            'tags' => $tags,
            'statuses' => $statuses,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->removeImageUrl = $item->renderRemoveFileUrl();
        $item->approveUrl = $item->renderApproveUrl();
        $item->denyUrl = $item->renderDenyUrl();

        return $item;
    }

    public function fetchMedia($id = null) {
        $response = SampleItem::getMediaLibrary($id);

        return response()->json($response);
    }
}
