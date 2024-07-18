{{-- <link rel="stylesheet" href="{{ asset('app/resource/css/css/app.css') }}">
<style>
    [class*=sidebar-dark-] {
        background-color: blue; 
        color: #c2c7d0; 
    }
    [class*=sidebar-dark-] .nav-link {
        color: #adb5bd;
    }
    [class*=sidebar-dark-] .nav-link:hover {
        background-color: #495057;
        color: #ffffff;
    }
    .navbar-white {
        background-color: pink;
        color: #1f2d3d;
    }
    .main-footer {
        background-color: pink;
        border-top: 1px solid #dee2e6;
        color: #869099;
        padding: 1rem;
    }
</style> --}}
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('supplier.dashboard')}}" class="brand-link">
        <img src="{{ asset('admin-assets/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>EComEasy</b></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('supplier.dashboard')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Supplier Dashboard</p>
                    </a>																
                </li>
                <li class="nav-item">
                    <a href="{{route('categories.index')}}" class="nav-link">
                        <svg class="h-6 nav-icon w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        <p>Category</p>
                    </a>
                    <li class="nav-item">
                        {{-- {{route('sub-categories.index')}} --}}
                        <a href="{{route('sub-categories.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Sub Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('brands.index')}}" class="nav-link">
                            <svg class="h-6 nav-icon w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                              </svg>
                            <p>Brands</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('products.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-tag"></i>
                            <p>Products</p>
                        </a>
                    </li>
                    <!-- New dropdown menu -->
                    <ul class="dropdown-menu hidden" id="user-options">
                        <li><a href="#add-user" class="dropdown-item">Add User</a></li>
                        <li><a href="#all-users" class="dropdown-item">All Users</a></li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Supplier</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Admin</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <!-- <i class="nav-icon fas fa-tag"></i> -->
                        <i class="fas fa-truck nav-icon"></i>
                        <p>Shipping</p>
                    </a>
                </li>							
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon  fa fa-percent" aria-hidden="true"></i>
                        <p>Discount</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>							 --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>
 <style>
    /* Hide dropdown menu initially */
.hidden {
    display: none;
}

/* Style for dropdown menu */
.dropdown-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.dropdown-item {
    display: block;
    padding: 8px 16px;
    text-decoration: none;
    color: #000;
}

.dropdown-item:hover {
    background-color: #f0f0f0;
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userToggle = document.getElementById('user-toggle');
        const userOptions = document.getElementById('user-options');

        userToggle.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior
            userOptions.classList.toggle('hidden');
        });
    });
</script>
    