@extends('layouts.master')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        @if(session('login_success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('login_success') }}
            </div>
        @endif
    </div>
</div>




<!-- Stats Cards Row -->
<div class="row g-4">

    <!-- Total Users Card -->
    <div class="col-md-3">
        <div class="card shadow hover-effect border-primary" style="min-height: 220px;">
            <div class="card-body d-flex flex-column justify-content-between h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="text-primary">Total Users</h5>
                        <h2 class="fw-bold" id="usersCount">{{ $users }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x text-primary"></i>
                </div>
                <canvas id="usersChart" height="50"></canvas>
                <a href="/users" class="btn btn-primary btn-sm w-100 mt-3">
                    <i class="fas fa-users me-1"></i> View Users
                </a>
            </div>
        </div>
    </div>

    <!-- Total Tables Card -->
    <div class="col-md-3">
        <div class="card shadow hover-effect border-warning" style="min-height: 220px;">
            <div class="card-body d-flex flex-column justify-content-between h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="text-warning">Total Tables</h5>
                        <h2 class="fw-bold" id="tablesCount">{{ $tables }}</h2>
                    </div>
                    <i class="fas fa-chair fa-3x text-warning"></i>
                </div>
                <canvas id="tablesChart" height="50"></canvas>
                <a href="/bookingtables" class="btn btn-warning btn-sm w-100 text-white mt-3">
                    <i class="fas fa-chair me-1"></i> View Tables
                </a>
            </div>
        </div>
    </div>

    <!-- Total Products Card -->
    <div class="col-md-3">
        <div class="card shadow hover-effect border-success" style="min-height: 220px;">
            <div class="card-body d-flex flex-column justify-content-between h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="text-success">Total Products</h5>
                        <h2 class="fw-bold" id="productsCount">{{ $products }}</h2>
                    </div>
                    <i class="fas fa-box-open fa-3x text-success"></i>
                </div>
                <canvas id="productsChart" height="50"></canvas>
                <a href="/products" class="btn btn-success btn-sm w-100 mt-3">
                    <i class="fas fa-box-open me-1"></i> View Products
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Today Card -->
    <div class="col-md-3">
        <div class="card shadow hover-effect border-danger" style="min-height: 220px;">
            <div class="card-body d-flex flex-column justify-content-between h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="text-danger">Orders Today</h5>
                        <h2 class="fw-bold" id="ordersCount">{{ $ordersToday }}</h2>
                    </div>
                    <i class="fas fa-receipt fa-3x text-danger"></i>
                </div>
                <canvas id="ordersChart" height="50"></canvas>
                <a href="/orders" class="btn btn-danger btn-sm w-100 mt-3">
                    <i class="fas fa-receipt me-1"></i> View Orders
                </a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const usersChart = new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: {
        labels: ['Users'],
        datasets: [{ data: [{{ $users }}], backgroundColor: '#0d6efd' }]
    },
    options: { scales: { y: { beginAtZero: true, suggestedMax: Math.max({{ $users }}, 5) } }, plugins: { legend: { display: false } } }
});

const tablesChart = new Chart(document.getElementById('tablesChart'), {
    type: 'bar',
    data: {
        labels: ['Tables'],
        datasets: [{ data: [{{ $tables }}], backgroundColor: '#ffc107' }]
    },
    options: { scales: { y: { beginAtZero: true, suggestedMax: Math.max({{ $tables }}, 5) } }, plugins: { legend: { display: false } } }
});

const productsChart = new Chart(document.getElementById('productsChart'), {
    type: 'bar',
    data: {
        labels: ['Products'],
        datasets: [{ data: [{{ $products }}], backgroundColor: '#198754' }]
    },
    options: { scales: { y: { beginAtZero: true, suggestedMax: Math.max({{ $products }}, 5) } }, plugins: { legend: { display: false } } }
});

const ordersChart = new Chart(document.getElementById('ordersChart'), {
    type: 'bar',
    data: {
        labels: ['Orders'],
        datasets: [{ data: [{{ $ordersToday }}], backgroundColor: '#dc3545' }]
    },
    options: { scales: { y: { beginAtZero: true, suggestedMax: Math.max({{ $ordersToday }}, 5) } }, plugins: { legend: { display: false } } }
});

// Auto Update Every 5 Seconds
setInterval(() => {
    fetch('/dashboard-data')
        .then(res => res.json())
        .then(data => {
            usersChart.data.datasets[0].data = [data.users];
            usersChart.options.scales.y.suggestedMax = Math.max(data.users, 5);
            usersChart.update();
            document.getElementById('usersCount').innerText = data.users;

            tablesChart.data.datasets[0].data = [data.tables];
            tablesChart.options.scales.y.suggestedMax = Math.max(data.tables, 5);
            tablesChart.update();
            document.getElementById('tablesCount').innerText = data.tables;

            productsChart.data.datasets[0].data = [data.products];
            productsChart.options.scales.y.suggestedMax = Math.max(data.products, 5);
            productsChart.update();
            document.getElementById('productsCount').innerText = data.products;

            ordersChart.data.datasets[0].data = [data.ordersToday];
            ordersChart.options.scales.y.suggestedMax = Math.max(data.ordersToday, 5);
            ordersChart.update();
            document.getElementById('ordersCount').innerText = data.ordersToday;
        });
}, 5000);
</script>



@endsection
