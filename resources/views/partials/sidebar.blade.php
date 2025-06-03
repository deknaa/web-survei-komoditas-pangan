 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
         <div class="sidebar-brand-icon rotate-n-15">
             <i class="fas fa-laugh-wink"></i>
         </div>
         <div class="sidebar-brand-text mx-3">
             {{ Auth::user()->role === 'eksekutif' ? 'Dashboard Eksekutif' : 'Dashboard Petugas' }}</div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard -->
     <li
         class="nav-item {{ Route::currentRouteName() === 'dashboard' || Route::currentRouteName() === 'dashboard.eksekutif' ? 'active' : '' }}">
         <a class="nav-link"
             href="{{ Auth::user()->role === 'eksekutif' ? route('dashboard.eksekutif') : route('dashboard') }}">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Heading -->
     <div class="sidebar-heading">
         Komoditas
     </div>

     <li class="nav-item {{ Route::currentRouteName() === 'komoditas.index' ? 'active' : '' }}">
         <a class="nav-link" href="{{ route('komoditas.index') }}">
             <i class="fas fa-fw fa-box"></i>
             <span>Kelola Komoditas</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Heading -->
     <div class="sidebar-heading">
         Neraca
     </div>

     <!-- Nav Item - Charts -->
     <li class="nav-item">
         <a class="nav-link" href="charts.html">
             <i class="fas fa-fw fa-chart-area"></i>
             <span>Neraca Komoditas</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">

     <!-- Heading -->
     <div class="sidebar-heading">
         Opsi
     </div>

     <!-- Nav Item - Charts -->
     {{-- <li class="nav-item">
         <a class="nav-link" href="#" >
             <i class="fas fa-fw fa-door-open"></i>
             <span>Logout</span></a>
     </li> --}}
     <li class="nav-item">
         <form method="POST" action="{{ route('logout') }}">
             @csrf
             <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                 <i class="fas fa-sign-out-alt fa-sm fa-fw "></i>
                 Logout
             </a>
         </form>
     </li>

     {{-- <!-- Nav Item - Tables -->
     <li class="nav-item">
         <a class="nav-link" href="tables.html">
             <i class="fas fa-fw fa-table"></i>
             <span>Tables</span></a>
     </li> --}}

     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>

 </ul>
 <!-- End of Sidebar -->
