@extends('admin.master')

@section('meta:title', 'Create Admin')

@section('content')

<div class="container">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.admin-users.index') }}">Admins</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <admin-user-view
            fetch-url="{{ route('admin.admin-users.fetch-item') }}"
            submit-url="{{ route('admin.admin-users.store') }}"
            ></admin-user-view>
        </section>
    </div>
</div>


@endsection