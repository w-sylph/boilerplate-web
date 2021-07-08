@extends('admin.master')

@section('meta:title', $item->id)

@section('content')

<div class="container">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $item->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousels</a></li>
                        <li class="breadcrumb-item active">{{ $item->renderName() }}</li>
                    </ol>
                </div>
            </div>
        </section>

        <page-pagination fetch-url="{{ route('admin.carousels.fetch-pagination', $item->id) }}"></page-pagination>

        <section class="content">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">Information</a></li>
                <li class="nav-item"><a @click="initList('table-1')" class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Activity Logs</a></li>
            </ul>
            <div class="tab-content mt-3 mb-5">
                <div class="tab-pane show active" id="tab1">
                    <carousel-view
                    fetch-url="{{ route('admin.carousels.fetch-item', $item->id) }}"
                    submit-url="{{ route('admin.carousels.update', $item->id) }}"
                    ></carousel-view>
                </div>
                <div class="tab-pane" id="tab2">
                    <activity-log-table
                    ref="table-1"
                    disabled
                    no-action
                    fetch-url="{{ route('admin.activity-logs.fetch.carousels', $item->id) }}"
                    ></activity-log-table>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection