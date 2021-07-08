@extends('admin.master')

@section('meta:title', $item->renderName())

@section('content')

<div class="container">
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.page-items.index') }}">Page Items</a></li>
                        <li class="breadcrumb-item active">{{ $item->renderName() }}</li>
                    </ol>
                </div>
            </div>
        </section>

        <page-pagination fetch-url="{{ route('admin.page-items.fetch-pagination', $item->id) }}"></page-pagination>

        <section class="content mt-3 mb-5">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">Information</a></li>
                <li class="nav-item"><a @click="initList('table-1')" class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Activity Logs</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="tab1">
                    <page-item-view
                    fetch-url="{{ route('admin.page-items.fetch-item', $item->id) }}"
                    submit-url="{{ route('admin.page-items.update', $item->id) }}"
                    ></page-item-view>
                </div>
                <div class="tab-pane" id="tab2">
                    <activity-log-table
                    ref="table-1"
                    disabled
                    fetch-url="{{ route('admin.activity-logs.fetch.page-items', $item->id) }}"
                    ></activity-log-table>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection