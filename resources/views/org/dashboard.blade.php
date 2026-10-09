@extends('layouts.org')

@section('org-content')
    <div class="fms-card">
        <div class="fms-page-header border-0 pb-0 mb-6">
            <h1 class="fms-page-title">Organization Staff Dashboard</h1>
        </div>
        <p class="mb-6 text-sm text-neutral-600">
            Welcome to the Organization Staff Portal. Manage your facility requests, reservations, and repair requests from the sidebar.
        </p>

        <!-- Statistics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Facilities Requests Chart (3D Pie Chart) -->
            <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">My Facilities Requests</h3>
                <div class="relative" style="height: 300px;">
                    <div id="facilitiesChart3D"></div>
                </div>
                <div class="mt-4 text-sm text-neutral-600">
                    <p>Total Requests: <strong>{{ $facilitiesStats['total'] }}</strong></p>
                </div>
            </div>

            <!-- Maintenance Tickets Chart -->
            <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">My Repair Requests</h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="maintenanceChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-neutral-600">
                    <p>Total Tickets: <strong>{{ $maintenanceStats['total'] }}</strong></p>
                </div>
            </div>
        </div>

        <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-neutral-500">Quick access</h2>
        <div class="fms-stat-grid">
            <a href="{{ route('org.bookings.index') }}" class="fms-stat-card">
                <h3>My Reservations</h3>
                <p>View your confirmed facility reservations</p>
            </a>
            <a href="{{ route('org.requests.facilities.create') }}" class="fms-stat-card">
                <h3>Utilization request</h3>
                <p>Submit a facilities utilization request to GSU</p>
            </a>
            <a href="{{ route('org.maintenance-tickets.create') }}" class="fms-stat-card">
                <h3>Repair request</h3>
                <p>Submit a repair or maintenance request</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
    <script>
    // Facilities Requests 3D Pie Chart using Plotly
    const facilitiesData = [{
        values: [
            {{ $facilitiesStats['pending'] }},
            {{ $facilitiesStats['pending_payment'] }},
            {{ $facilitiesStats['approved'] }},
            {{ $facilitiesStats['reserved'] }},
            {{ $facilitiesStats['disapproved'] }}
        ],
        labels: ['Pending', 'Pending Payment', 'Approved', 'Reserved', 'Disapproved'],
        type: 'pie',
        marker: {
            colors: [
                '#FCD34D', // Yellow - Pending
                '#FB923C', // Orange - Pending Payment
                '#34D399', // Green - Approved
                '#60A5FA', // Blue - Reserved
                '#F87171'  // Red - Disapproved
            ],
            line: {
                color: '#fff',
                width: 2
            }
        },
        hole: 0,
        pull: [0, 0.05, 0, 0, 0],
        textinfo: 'label+percent',
        textposition: 'outside',
        automargin: true
    }];

    const facilitiesLayout = {
        height: 300,
        margin: { t: 20, b: 20, l: 20, r: 20 },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        showlegend: true,
        legend: {
            orientation: 'h',
            y: -0.2,
            x: 0.5,
            xanchor: 'center'
        }
    };

    const facilitiesConfig = {
        responsive: true,
        displayModeBar: false
    };

    Plotly.newPlot('facilitiesChart3D', facilitiesData, facilitiesLayout, facilitiesConfig);

    // Maintenance Tickets Chart (Bar Chart)
    const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
    new Chart(maintenanceCtx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Notified', 'In Progress', 'Completed', 'Rejected'],
            datasets: [{
                label: 'Number of Tickets',
                data: [
                    {{ $maintenanceStats['pending'] }},
                    {{ $maintenanceStats['notified'] }},
                    {{ $maintenanceStats['in_progress'] }},
                    {{ $maintenanceStats['completed'] }},
                    {{ $maintenanceStats['rejected'] }}
                ],
                backgroundColor: [
                    '#FCD34D', // Yellow - Pending
                    '#60A5FA', // Blue - Notified
                    '#FB923C', // Orange - In Progress
                    '#34D399', // Green - Completed
                    '#F87171'  // Red - Rejected
                ],
                borderWidth: 1,
                borderColor: '#1a1a1a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    </script>
@endsection

 