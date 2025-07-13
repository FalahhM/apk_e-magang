<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-success sidebar sidebar-dark accordion shadow-sm" id="accordionSidebar" style="background-color: #2e7d32 !important;">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/kampusdashboard') }}">
            <div class="sidebar-brand-icon">
                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nmJ8vS3qrBIAo-Vf48vC4M-dL8TrT8rSjWtBWCJV9Y8zE=s900-c-k-c0x00ffffff-no-rj" alt="Logo" height="40" class="rounded-circle">
            </div>
            <div class="sidebar-brand-text mx-3 text-light">E-Magang</div>
        </a>

        <hr class="sidebar-divider my-0">

        <!-- Dashboard -->
        <li class="nav-item {{ Request::is('kampusdashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/kampusdashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading text-white">
            Menu Utama
        </div>

        <!-- Link Kampus -->
        <li class="nav-item {{ Request::is('kampus') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/kampus') }}">
                <i class="fas fa-university"></i>
                <span>Data Kampus</span></a>
        </li>

        <!-- Link Pengajuan -->
        <li class="nav-item {{ Request::is('formpengajuan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/formpengajuan') }}">
                <i class="fas fa-file-alt"></i>
                <span>Pengajuan Magang</span></a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <!-- Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0 bg-light" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow-sm border-bottom">

                <!-- Sidebar Toggle -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-success">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Page Title -->
                <h5 class="text-success mt-2">E-Magang PTPN IV Regional IV</h5>

                <!-- Navbar Right -->
                <ul class="navbar-nav ml-auto">

                    <!-- Divider -->
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                {{ Auth::user()->name }}
                            </span>
                            <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2e7d32&color=fff&size=100" height="35">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <h6 class="dropdown-header">
                                {{ Auth::user()->email }}
                            </h6>
                            <div class="dropdown-divider"></div>
                            <form action="/logout" method="POST" class="d-inline">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>

            </nav>
            <!-- End Topbar -->

                    <!-- Content Row -->
                    <div class="row">


                    <!-- Content Row -->

                    <div class="row">

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->
