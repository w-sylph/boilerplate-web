@extends('admin.master')

@section('meta:title', 'Pages')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pages</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Pages</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="mb-4">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus mr-1"></i>
                Create
            </a>
        </div>

        <ul class="nav nav-pills">
            <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">Active</a></li>
            <li class="nav-item"><a @click="initList('table-2')" class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Archived</a></li>
            <li class="nav-item"><a @click="initList('table-3')" class="nav-link" data-target="#tab3" href="#tab3" data-toggle="tab">Activity Logs</a></li>
        </ul>
        <div class="tab-content mt-3 mb-5">
            <div class="tab-pane show active" id="tab1">
                <page-table
                ref="table-1"
                fetch-url="{{ route('admin.pages.fetch') }}"
                ></page-table>
            </div>
            <div class="tab-pane" id="tab2">
                <page-table
                ref="table-2"
                disabled
                fetch-url="{{ route('admin.pages.fetch-archive') }}"
                ></page-table>
            </div>
            <div class="tab-pane" id="tab3">
                <activity-log-table
                ref="table-3"
                disabled
                show-subject
                fetch-url="{{ route('admin.activity-logs.fetch.pages') }}"
                ></activity-log-table>
            </div>
        </div>
    </section>
</div>

@endsection