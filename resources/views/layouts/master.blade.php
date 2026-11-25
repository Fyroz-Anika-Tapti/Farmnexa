<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard') | FarmNexa</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
   @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Dashboard</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button">
          <i class="far fa-user"></i>
          <span class="ml-1">{{ auth()->user()->name ?? 'User' }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item" style="border:none;background:none;width:100%;text-align:left;cursor:pointer;">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">FarmNexa</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=007bff&color=fff" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name ?? 'User' }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- Farm Administration -->
          <li class="nav-header">FARM ADMINISTRATION</li>
          @can('farm.view')
          <li class="nav-item {{ request()->routeIs('farms.*') || request()->routeIs('sections.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('farms.*') || request()->routeIs('sections.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tractor"></i>
              <p>Farm Setup <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('farms.index') }}" class="nav-link {{ request()->routeIs('farms.*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Farms</p>
                </a>
              </li>
              <li class="nav-item">
        <a href="{{ route('sections.index') }}" class="nav-link {{ request()->routeIs('sections.*') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Sections</p>
        </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('cost-centres.index') }}" class="nav-link {{ request()->routeIs('cost-centres.*') ? 'active' : '' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>Cost Centres</p>
          </a>
      </li>
            </ul>
          </li>
          @endcan

          <!-- Livestock -->
          <li class="nav-header">OPERATIONS</li>
          @can('animal.view')
            <li class="nav-item">
                <a href="{{ route('animals.index') }}" class="nav-link {{ request()->routeIs('animals.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cow"></i>
                    <p>Livestock</p>
                </a>
            </li>
            @endcan
            <li class="nav-item">
    <a href="{{ route('animal-expenses.page') }}" class="nav-link {{ request()->routeIs('animal-expenses.*') ? 'active' : '' }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Animal Expenses</p>
    </a>
</li>
            
            @can('animal.view')
          <li class="nav-item">
              <a href="{{ route('animal-sales.index') }}" class="nav-link {{ request()->routeIs('animal-sales.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-money-bill-wave"></i>
                  <p>Animal Sales</p>
              </a>
          </li>
          @endcan

            @can('animal.view')
            <li class="nav-item">
                <a href="{{ route('poultry-batches.index') }}" class="nav-link {{ request()->routeIs('poultry-batches.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-egg"></i>
                    <p>Poultry Batches</p>
                </a>
            </li>
            @endcan

            <li class="nav-item">
              <a href="{{ route('milk-productions.index') }}" class="nav-link {{ request()->routeIs('milk-productions.*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-glass-whiskey"></i>
                  <p>Milk Production</p>
              </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('milk-processings.index') }}" class="nav-link {{ request()->routeIs('milk-processings.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-blender"></i>
                <p>Milk Processing</p>
            </a>
        </li>

          <li class="nav-item">
            <a href="{{ route('egg-productions.index') }}" class="nav-link {{ request()->routeIs('egg-productions.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-egg"></i>
                <p>Egg Production</p>
            </a>
        </li>

          <!-- Inventory -->
@can('farm.view')
<li class="nav-item {{ request()->routeIs('inventory-*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('inventory-*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-boxes"></i>
        <p>
            Inventory
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('inventory-items.index') }}" class="nav-link {{ request()->routeIs('inventory-items.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Items</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Locations</p>
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>Stock Movements</p>
          </a>
      </li>
    </ul>
</li>
@endcan
    <li class="nav-header">PROCUREMENT</li>
      <li class="nav-item">
          <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>Suppliers</p>
          </a>
      </li>
    <li class="nav-item">
        <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Purchase Orders</p>
        </a>
    </li>

    <!-- SALES -->
@can('farm.view')
<li class="nav-item {{ request()->routeIs('customers.*') || request()->routeIs('products.*') || request()->routeIs('sales-invoices.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('customers.*') || request()->routeIs('products.*') || request()->routeIs('sales-invoices.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cash-register"></i>
        <p>Sales <i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Products</p>
            </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('sales-invoices.index') }}" class="nav-link {{ request()->routeIs('sales-invoices.*') ? 'active' : '' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>Sales Invoices</p>
          </a>
      </li>
    </ul>
</li>
@endcan

          <!-- ADMIN Section -->
          @role('Owner')
          <li class="nav-header">SYSTEM ADMIN</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Roles & Permissions</p>
            </a>
          </li>
          @endrole
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Flash Messages -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
          </div>
        @endif

        @yield('content')
        
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">FarmNexa</a>.</strong>
    All rights reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

@stack('scripts')
</body>
</html>