@extends('admin.master')

@section('meta:title', 'Dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
        </div>
    </section>



    <section class="content">

        @if($self->hasPasswordExpired())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Important Reminder</h4>
                <p>
                    Your password is over {{ config('web.password_expiration_days') }} days old, we recommend that you update your password
                    <a href="{{ route('admin.profiles.show-password') }}">here</a> to secure your account.
                </p>
            </div>
        @endif

        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" data-target="#tab1" href="#tab1" data-toggle="tab">User Analytics</a></li>
            <li class="nav-item"><a class="nav-link" data-target="#tab2" href="#tab2" data-toggle="tab">Admin Analytics</a></li>
        </ul>
        <div class="tab-content mt-3 mb-5">
            <div class="tab-pane show active" id="tab1">
                <dashboard-analytics
                title="User Analytics"
                fetch-url="{{ route('admin.analytics.fetch.user') }}"
                ></dashboard-analytics>
            </div>
            <div class="tab-pane" id="tab2">
                <dashboard-analytics
                title="Admin Analytics"
                fetch-url="{{ route('admin.analytics.fetch.admin') }}"
                ></dashboard-analytics>
            </div>
        </div>
    </section>
</div>

@endsection