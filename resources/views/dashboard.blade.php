@extends('layouts.master')

@section('content')

<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .stat-card .card-body {
        z-index: 1;
    }
    .stat-card .icon-bg {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 8rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
    .gradient-primary { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; }
    .gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .gradient-success { background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); color: white; }
    .gradient-danger { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); color: white; }
    
    .stat-value { font-size: 2.5rem; font-weight: 800; margin-bottom: 0; }
    .stat-label { font-size: 1rem; opacity: 0.8; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
    .view-btn { 
        background: rgba(255,255,255,0.2); 
        border: none; 
        color: white; 
        backdrop-filter: blur(5px);
        border-radius: 8px;
        transition: all 0.2s;
    }
    .view-btn:hover { background: rgba(255,255,255,0.3); color: white; }
</style>

<div class="row mb-4">
    <div class="col-12">
        @if(session('login_success'))
            <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('login_success') }}
            </div>
        @endif
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4">

    <!-- Total Users Card -->
    <div class="col-md-3">
        <div class="card stat-card gradient-primary shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex flex-column h-100">
                    <div class="mb-3">
                        <p class="stat-label mb-1">Total Users</p>
                        <h2 class="stat-value" id="usersCount">{{ $users }}</h2>
                    </div>
                    <div class="mt-auto">
                        <div style="height: 60px;">
                            <canvas id="usersChart"></canvas>
                        </div>
                        <a href="/users" class="btn view-btn btn-sm w-100 mt-3">
                            <i class="fas fa-users me-1"></i> View Details
                        </a>
                    </div>
                </div>
                <i class="fas fa-users icon-bg"></i>
            </div>
        </div>
    </div>

    <!-- Total Tables Card -->
    <div class="col-md-3">
        <div class="card stat-card gradient-warning shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex flex-column h-100">
                    <div class="mb-3">
                        <p class="stat-label mb-1">Total Tables</p>
                        <h2 class="stat-value" id="tablesCount">{{ $tables }}</h2>
                    </div>
                    <div class="mt-auto">
                        <div style="height: 60px;">
                            <canvas id="tablesChart"></canvas>
                        </div>
                        <a href="/bookingtables" class="btn view-btn btn-sm w-100 mt-3">
                            <i class="fas fa-chair me-1"></i> View Details
                        </a>
                    </div>
                </div>
                <i class="fas fa-chair icon-bg"></i>
            </div>
        </div>
    </div>

    <!-- Total Products Card -->
    <div class="col-md-3">
        <div class="card stat-card gradient-success shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex flex-column h-100">
                    <div class="mb-3">
                        <p class="stat-label mb-1">Total Products</p>
                        <h2 class="stat-value" id="productsCount">{{ $products }}</h2>
                    </div>
                    <div class="mt-auto">
                        <div style="height: 60px;">
                            <canvas id="productsChart"></canvas>
                        </div>
                        <a href="/products" class="btn view-btn btn-sm w-100 mt-3">
                            <i class="fas fa-box-open me-1"></i> View Details
                        </a>
                    </div>
                </div>
                <i class="fas fa-box-open icon-bg"></i>
            </div>
        </div>
    </div>

    <!-- Orders Today Card -->
    <div class="col-md-3">
        <div class="card stat-card gradient-danger shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex flex-column h-100">
                    <div class="mb-3">
                        <p class="stat-label mb-1">Orders Today</p>
                        <h2 class="stat-value" id="ordersCount">{{ $ordersToday }}</h2>
                    </div>
                    <div class="mt-auto">
                        <div style="height: 60px;">
                            <canvas id="ordersChart"></canvas>
                        </div>
                        <a href="/orders" class="btn view-btn btn-sm w-100 mt-3">
                            <i class="fas fa-receipt me-1"></i> View Details
                        </a>
                    </div>
                </div>
                <i class="fas fa-receipt icon-bg"></i>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { enabled: false } },
    scales: { 
        x: { display: false }, 
        y: { display: false, beginAtZero: true } 
    },
    elements: {
        line: { tension: 0.4, borderWidth: 2, borderColor: 'rgba(255,255,255,0.8)', fill: true, backgroundColor: 'rgba(255,255,255,0.1)' },
        point: { radius: 0 }
    }
};

const createChart = (id, data, color) => {
    return new Chart(document.getElementById(id), {
        type: 'line',
        data: {
            labels: ['', '', '', '', ''],
            datasets: [{
                data: [data * 0.8, data * 0.9, data * 0.7, data * 0.95, data],
                ...chartOptions.elements.line
            }]
        },
        options: chartOptions
    });
};

const usersChart = createChart('usersChart', {{ $users }});
const tablesChart = createChart('tablesChart', {{ $tables }});
const productsChart = createChart('productsChart', {{ $products }});
const ordersChart = createChart('ordersChart', {{ $ordersToday }});

// Auto Update Every 5 Seconds
setInterval(() => {
    fetch('/dashboard-data')
        .then(res => res.json())
        .then(data => {
            const updateChart = (chart, newValue) => {
                chart.data.datasets[0].data.shift();
                chart.data.datasets[0].data.push(newValue);
                chart.update('none');
            };

            updateChart(usersChart, data.users);
            document.getElementById('usersCount').innerText = data.users;

            updateChart(tablesChart, data.tables);
            document.getElementById('tablesCount').innerText = data.tables;

            updateChart(productsChart, data.products);
            document.getElementById('productsCount').innerText = data.products;

            updateChart(ordersChart, data.ordersToday);
            document.getElementById('ordersCount').innerText = data.ordersToday;
        });
}, 5000);
</script>

@endsection

