@extends('admin.master')

@section('meta:title', 'Admins')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Admins</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Admins</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="mb-4">
            <a href="{{ route('admin.admin-users.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus mr-1"></i>
                Create
            </a>
        </div>

        <ul class="nav nav-pills">
            <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">Active</a></li>
            <li class="nav-item"><a @click="initList('table-2')" class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Archive</a></li>
            <li class="nav-item"><a @click="initList('table-3')" class="nav-link" data-target="#tab3" href="#tab3" data-toggle="tab">Activity Logs</a></li>
        </ul>
        <div class="tab-content mt-3 mb-5">
            <div class="tab-pane show active" id="tab1">
                <admin-user-table
                ref="table-1"
                fetch-url="{{ route('admin.admin-users.fetch') }}"
                :filter-roles="{{ $filterRoles }}"
                ></admin-user-table>
            </div>
            <div class="tab-pane" id="tab2">
                <admin-user-table
                ref="table-2"
                disabled
                fetch-url="{{ route('admin.admin-users.fetch-archive') }}"
                :filter-roles="{{ $filterRoles }}"
                ></admin-user-table>
            </div>
            <div class="tab-pane" id="tab3">
                <activity-log-table
                ref="table-3"
                disabled
                show-subject
                fetch-url="{{ route('admin.activity-logs.fetch.admin-users') }}"
                ></activity-log-table>
            </div>
        </div>
    </section>
</div>

@endsection