@extends('admin.master')

@section('meta:title', 'My Account')

@section('content')

<div class="container">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Account</h1>
                </div>
            </div>
        </section>

        <section class="content">
            <ul class="nav--hashable nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Change Password</a></li>
                 <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#tab3" data-toggle="tab">Activity Logs</a></li>
            </ul>
            <div class="tab-content mt-3 mb-5">
                <div class="tab-pane show active" id="tab1">
                   <admin-user-view
                   :editable="false"
                   fetch-url="{{ route('admin.profiles.fetch') }}"
                   submit-url="{{ route('admin.profiles.update') }}"
                   ></admin-user-view>
                </div>
                <div class="tab-pane" id="password">
                    <change-password-form
                    submit-url="{{ route('admin.profiles.change-password') }}"
                    ></change-password-form>
                </div>
                <div class="tab-pane" id="tab3">
                    <activity-log-table
                    ref="table-1"
                    disabled
                    fetch-url="{{ route('admin.activity-logs.fetch.profiles') }}"
                    ></activity-log-table>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection