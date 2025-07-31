<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        #wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar-wrapper {
            width: 250px;
            background-color: #343a40;
            color: white;
        }

        .sidebar-heading {
            padding: 1.5rem;
            font-size: 1.25rem;
            background-color: #212529;
            text-align: center;
            border-bottom: 1px solid #495057;
        }

        .list-group-item {
            background-color: #343a40;
            color: #adb5bd;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .list-group-item:hover,
        .list-group-item.active {
            background-color: #495057;
            color: #fff;
            transform: scale(1.03);
        }

        .list-group-item i {
            width: 20px;
        }

        #page-content-wrapper {
            flex: 1;
            padding: 20px;
        }

        .hover-effect:hover {
            transform: translateY(-5px);
            transition: 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .list-group-item {
            background-color: #2e3031;
            color: #adb5bd;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .list-group-item:hover,
        .list-group-item.active {
            background-color: #8b8585;
            /* Darker black hover/active */
            color: #fff;
            transform: scale(1.03);
        }

        .list-group-item i {
            width: 20px;
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="bg-dark">
            <div class="sidebar-heading text-white py-4 px-3 fs-4">
                <i class="fas fa-utensils me-2"></i>Restaurant System
            </div>
            <div class="list-group list-group-flush">
                <a href="/"
                    class="list-group-item list-group-item-action {{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line me-2"></i> Dashboard
                </a>
                <a href="/users"
                    class="list-group-item list-group-item-action {{ Request::is('users*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
                <a href="/bookingtables"
                    class="list-group-item list-group-item-action {{ Request::is('bookingtables*') ? 'active' : '' }}">
                    <i class="fas fa-chair me-2"></i>Restaurant Tables
                </a>
                <a href="/categories"
                    class="list-group-item list-group-item-action {{ Request::is('categories*') ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i> Menu Categories
                </a>
                <a href="/products"
                    class="list-group-item list-group-item-action {{ Request::is('products') ? 'active' : '' }}">
                    <i class="fas fa-box-open me-2"></i> Products
                </a>
                <a href="/orders"
                    class="list-group-item list-group-item-action {{ Request::is('orders*') ? 'active' : '' }}">
                    <i class="fas fa-receipt me-2"></i> Orders
                </a>
                <a href="/payments"
                    class="list-group-item list-group-item-action {{ Request::is('payments*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i> Payments
                </a>
                <a href="/profile"
                    class="list-group-item list-group-item-action {{ Request::is('profile') ? 'active' : '' }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
                <a href="/product-sales-report"
                    class="list-group-item list-group-item-action {{ Request::is('product-sales-report*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i> Product Sales Report
                </a>
                <a href="/customer-feedback"
                    class="list-group-item list-group-item-action {{ Request::is('customer-feedback*') ? 'active' : '' }}">
                    <i class="fas fa-comments me-2"></i> Customer Feedback
                </a>
                <a href="/expenses"
                    class="list-group-item list-group-item-action {{ Request::is('expenses*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Expenses
                </a>
                <a href="/stock-items"
                    class="list-group-item list-group-item-action {{ Request::is('stock-items*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> stockInventory
                </a>
                 <a href="/restaurant-assets"
                    class="list-group-item list-group-item-action {{ Request::is('restaurant-assets*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Restauant Assets
                </a>
                 <a href="/damaged_items"
                    class="list-group-item list-group-item-action {{ Request::is('damaged_items*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Damaged Items
                </a>
                 <a href="/purchases"
                    class="list-group-item list-group-item-action {{ Request::is('purchases*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Purchases
                </a>
                 <a href="/variations"
                    class="list-group-item list-group-item-action {{ Request::is('variations*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Variations
                </a>
                 <a href="/mess_menus"
                    class="list-group-item list-group-item-action {{ Request::is('mess_menus*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Mess Menus
                </a>
                 <a href="/menu-materials"
                    class="list-group-item list-group-item-action {{ Request::is('menu-materials*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Menu Materials
                </a>
                 <a href="/mess_items_purchases"
                    class="list-group-item list-group-item-action {{ Request::is('mess_items_purchases*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Mess Items Purchases
                </a>
                 <a href="/mess-distributions"
                    class="list-group-item list-group-item-action {{ Request::is('mess-distributions*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Mess Distributions
                </a>
                 <a href="/mess-finances"
                    class="list-group-item list-group-item-action {{ Request::is('mess-finances*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Mess Finances
                </a>
                 <a href="/dish_variations"
                    class="list-group-item list-group-item-action {{ Request::is('dish_variations*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Dishes Variations
                </a>
                 {{-- <a href="/staff-salaries"
                    class="list-group-item list-group-item-action {{ Request::is('staff-salaries*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Staff Salaries
                </a> --}}

            </div>
        </div>

        <!-- Main Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-dark" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle fw-bold text-white" type="button"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'User' }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown"
                                style="min-width: 200px;">
                                <li>
                                    <a class="dropdown-item text-primary fw-bold" href="/profile">
                                        <i class="fas fa-user me-2 text-primary"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-success fw-bold" href="/settings">
                                        <i class="fas fa-cog me-2 text-success"></i> Settings
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold">
                                            <i class="fas fa-sign-out-alt me-2 text-danger"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <!-- Include Select2 for search dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    @stack('scripts')
    <script>
        const toggleButton = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar-wrapper");
        toggleButton.addEventListener("click", () => sidebar.classList.toggle("d-none"));
    </script>

</body>

</html>
