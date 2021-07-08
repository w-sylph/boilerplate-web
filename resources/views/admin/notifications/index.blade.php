@extends('admin.master')

@section('meta:title', 'Notifications')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Notifications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <notification-table 
        read-all-url="{{ route('admin.notifications.read-all') }}"
        fetch-url="{{ route('admin.notifications.fetch') }}"
        ></notification-table>
    </section>
</div>

@endsection