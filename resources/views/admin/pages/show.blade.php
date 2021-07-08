@extends('admin.master')

@section('meta:title', $item->renderName())

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $item->renderName() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active">{{ $item->renderName() }}</li>
                </ol>
            </div>
        </div>
    </section>

    <page-pagination fetch-url="{{ route('admin.pages.fetch-pagination', $item->id) }}"></page-pagination>

    <section class="content">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">Information</a></li>
            <li class="nav-item"><a @click="initList('table-1')" class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Page Items</a></li>
            <li class="nav-item"><a @click="initList('table-2')" class="nav-link" data-target="#tab3" href="#tab3" data-toggle="tab">Activity Logs</a></li>
        </ul>
        <div class="tab-content mt-3 mb-5">
            <div class="tab-pane show active" id="tab1">
                <page-view
                fetch-url="{{ route('admin.pages.fetch-item', $item->id) }}"
                submit-url="{{ route('admin.pages.update', $item->id) }}"
                ></page-view>
            </div>
            <div class="tab-pane" id="tab2">
                <page-item-table
                ref="table-1"
                hide-parent
                hide-buttons
                disabled
                fetch-url="{{ route('admin.page-items.fetch-page-items', $item->id) }}"
                ></page-item-table>
            </div>
            <div class="tab-pane" id="tab3">
                <activity-log-table
                ref="table-2"
                disabled
                show-subject
                fetch-url="{{ route('admin.activity-logs.fetch.pages', $item->id) }}"
                ></activity-log-table>
            </div>
        </div>
    </section>
</div>

@endsection