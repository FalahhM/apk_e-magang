<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
<ul class="navbar-nav bg-success sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/dospemdashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="https://yt3.googleusercontent.com/ytc/AIdro_nmJ8vS3qrBIAo-Vf48vC4M-dL8TrT8rSjWtBWCJV9Y8zE=s900-c-k-c0x00ffffff-no-rj"
                 alt="Logo PTPN IV"
                 style="width: 35px; height: 35px; border-radius: 50%;">
        </div>
        <div class="sidebar-brand-text mx-3">E-Magang</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('dospemdashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/dospemdashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Menu</div>

    <!-- Data Absensi -->
    <li class="nav-item {{ request()->routeIs('dospem.absensi') || request()->routeIs('dospem.absensi.detail') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dospem.absensi') }}">
            <i class="fas fa-file-signature"></i>
            <span>Data Absensi</span>
        </a>
    </li>


    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars text-success"></i>
                </button>

                <!-- Topbar Title -->
                <h5 class="ml-3 text-success font-weight-bold">PTPN IV Regional IV</h5>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Logout -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle text-success" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline font-weight-bold">
                                {{ auth()->user()->name ?? 'Dospem' }}
                            </span>
                            <i class="fas fa-user-circle fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <form action="/logout" method="POST" class="px-3 py-2 m-0">
                                @csrf
                                <button class="btn btn-link text-danger p-0" type="submit" style="width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->

            <div class="container-fluid">
                {{-- Konten halaman akan ditempatkan di sini --}}
