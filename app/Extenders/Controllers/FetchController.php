<?php

namespace App\Extenders\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

abstract class FetchController extends Controller
{
    protected $user = null;

    protected $request = null;
    protected $class = '';

    protected $order = 'asc';
    protected $orderBy = 'id';

    protected $dateRangeColumn = 'created_at';

    protected $per_page;

    /**
     * Set the object to be used by the controller
     *
     * @var $class Class name of the object
     */
    abstract protected function setObjectClass();

    /**
     * Custom filtering of query
     *
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    abstract protected function filterQuery($query);

    /**
     * Build an array w/ all the needed fields
     *
     * @return array
     */
    abstract protected function formatData($items);

    protected function formatResponse() { return []; }
    protected function getResponse() { return []; }

    /**
     * Set all needed variables
     */
    protected function init($request)
    {
        /* Get default variable */
        $this->user = $request->user();
        $this->request = $request;

        /* Set object class */
        $this->setObjectClass();
    }

    /**
     * Fetch the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        /* Initialize needed vars */
        $this->init($request);

        /* Set default parameters */
        $this->setParameters($request);

        /* Set storage vars */
        $items = [];
        $pagination = null;

        /* Perform needed queries */
        $collections = $this->fetchQuery($request);

        if ($request->filled('ids_only')) {

            $items = $collections->pluck('id')->toArray();

        } else if($request->filled('nopagination')) {
            /* Check if pagination is disabled  */
            $items = $this->formatData($collections->get());

        } else {
            $collections = $collections->paginate($this->per_page);
            $items = $this->formatData($collections->items());
            $pagination = $this->getPagination($collections);
        }

        $this->formatResponse();

        return response()->json(array_merge($this->getResponse(), [
            'items' => $items,
            'pagination' => $pagination,
        ]));
    }

    /**
     * Create query
     *
     * @return Illuminate\Pagination\Paginator
     */
    protected function fetchQuery() {
        $query = $this->class;

        /* Fetch archived objects */
        $query = $this->queryArchived($query);

        /* Filter object by date */
        $query = $this->dateQuery($query);

        /* Run filters */
        $query = $this->filterQuery($query);

        /* Run search*/
        $query = $this->searchQuery($query);

        /* Run sorting */
        $query = $this->sortQuery($query);

        return $query;
    }

    protected function queryArchived($query) {
        if($this->request->filled('archived')) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }

    protected function dateQuery($query) {
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $startDate = Carbon::parse($this->request->input('start_date'))->format('Y-m-d') . " 00:00:00";
            $endDate = Carbon::parse($this->request->input('end_date'))->format('Y-m-d') . " 23:59:59";
            $query = $query->whereBetween($this->dateRangeColumn, [$startDate, $endDate]);
        }

        return $query;
    }

    protected function searchQuery($query) {
        if($this->request->filled('search')){
            if (config('web.tnt.refresh_on_query')) {
                $this->class::get()->searchable();
            }

            $query = $query->whereIn('id', $this->class::search($this->request->input('search'))->get()->pluck('id')->toArray());
        }

        return $query;
    }

    protected function sortQuery($query) {

        switch ($this->orderBy) {
            default:
                    $query = $query->orderBy($this->orderBy, $this->order);
                break;
        }

        return $query;
    }

    /**
     * Set general parameters
     */
    protected function setParameters()
    {
        /* Set column to sort  */
        if($this->request->filled('order')) {
            $this->order = $this->request->input('order');
        }

        /* Set column order  */
        if($this->request->filled('orderBy')) {
            $this->orderBy = $this->request->input('orderBy');
        }

        /* Set total no. of item per page  */
        if($this->request->filled('per_page')) {
            $this->per_page = $this->request->input('per_page');
        }
    }

    /**
     * Rename pagination keys
     *
     * @param json
     * @return array
     */
    protected function getPagination($pagination)
    {
        return collect($pagination)->only([
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
    }

    /**
     * Page Pagination
     * @param  Request $request
     * @param  int  $id      current item id
     * @return json           url for prev and next page
     */
    public function fetchPagePagination(Request $request, $id)
    {
        $this->init($request);

        $result = [
            'next_page' => null,
            'prev_page' => null,
        ];

        if (method_exists($this->class, 'generatePagePaginationUrls')) {
            $class = get_class($this->class);
            $result = $class::generatePagePaginationUrls($request, $id);
        }

        return response()->json($result);
    }
}