@extends('admin.master')

@section('meta:title', 'Create User')

@section('content')

<div class="container">
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	    <!-- Content Header (Page header) -->
	    <section class="content-header">
	        <div class="row mb-2">
	            <div class="col-sm-6">
	                <h1>Create User</h1>
	            </div>
	            <div class="col-sm-6">
	                <ol class="breadcrumb float-sm-right">
	                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
	                    <li class="breadcrumb-item active">Create</li>
	                </ol>
	            </div>
	        </div>
	    </section>

	    <!-- Main content -->
	    <section class="content">
	        <user-view
	        fetch-url="{{ route('admin.users.fetch-item') }}"
	        submit-url="{{ route('admin.users.store') }}"
	        ></user-view>
	    </section>
	</div>
</div>


@endsection