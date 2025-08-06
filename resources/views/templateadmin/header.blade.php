<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Magang</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('dashboard/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('dashboard/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <!-- Di templateadmin.header -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <style>
    .ptpn-header {
    background-color: #00B96F;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
}

.ptpn-title {
    font-weight: bold;
    color: #005B3C;
}

.ptpn-table thead {
    background-color: #008655;
    color: white;
}

.ptpn-badge-success {
    background-color: #e0f7ef;
    color: #008655;
    border: 1px solid #b2dfdb;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
}

.ptpn-badge-fail {
    background-color: #fbe9e7;
    color: #d84315;
    border: 1px solid #ffccbc;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
}

.ptpn-btn-detail {
    background-color: #00B96F;
    border: none;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    transition: background-color 0.3s ease;
}

.ptpn-btn-detail:hover {
    background-color: #008655;
}

</style>

</head>

    @include('templateadmin.navbar')

    @yield('content')

    @include('templateadmin.footer')