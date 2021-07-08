@extends('admin.master')

@section('meta:title', 'Activity Logs')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activity Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Activity Logs</li>
                </ol>
            </div>
        </div>
    </section>

    

    <!-- Main content -->
    <section class="content">
        <activity-log-table 
        fetch-url="{{ route('admin.activity-logs.fetch') }}"
        :filter-types="{{ $filterTypes }}"
        :filter-causers="{{ $filterCausers }}"
        actionable
        show-subject
        ></activity-log-table>
    </section>
</div>

@endsection