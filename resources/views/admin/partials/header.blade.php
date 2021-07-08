<nav class="main-header navbar navbar-expand border-bottom navbar-dark bg-danger">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                <i class="fa fa-bell mr-2 text-white"></i>
                <count-listener
                class="badge-warning navbar-badge text-white"
                fetch-url="{{ route('admin.counts.fetch.notifications') }}"
                event="update-notification-count"
                ></count-listener>
            </a>
        </li>

        <li class="nav-item border-right">
            <a class="nav-link text-white" href="{{ route('admin.profiles.show') }}">
                <i class="fas fa-user-circle mr-2 text-white"></i>
                <span class="d-none d-md-inline">My Account</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('admin.logout') }}">
                <i class="fas fa-sign-out-alt mr-1"></i>
                <span class="d-none d-md-inline">Logout</span>
            </a>
        </li>

    </ul>
</nav>
