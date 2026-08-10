<div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            {{-- <a href="{{route('dashboard')}}" class="logo">
              <img
                src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a> --}}
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">

              <li class="nav-item">
                <a href="{{ route('dashboard') }}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('employees.index') }}">
                  <i class="fas fa-user"></i>
                  <p>Employees</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('departments.index') }}">
                  <i class="fas fa-building"></i>
                  <p>Departments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('jobs.index') }}">
                  <i class="fas fa-briefcase"></i>
                  <p>jobs</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ route('qualification-details.index') }}">
                  <i class="fas fa-graduation-cap"></i>
                  <p>Qualification-details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('references.index') }}">
                  <i class="fas fa-address-book"></i>
                  <p>References</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bank-accounts.index') }}">
                  <i class="fas fa-university"></i>
                  <p>Bank Accounts</p>
                </a>
              </li>
             <li class="nav-item">
                <a href="{{ route('attendance.index') }}">
                  <i class="fas fa-calendar-check"></i>
                  <p>Attendance</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
