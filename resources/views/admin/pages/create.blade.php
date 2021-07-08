@extends('admin.master')

@section('meta:title', 'Create Page')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Page</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <page-view
        fetch-url="{{ route('admin.pages.fetch-item') }}"
        submit-url="{{ route('admin.pages.store') }}"
        ></page-view>
    </section>
</div>

@endsection