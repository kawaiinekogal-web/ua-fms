@extends('layouts.admin')

@section('admin-content')
<div class="admin-dash-header">
  <h1>Admin Dashboard</h1>
  <p>Welcome to the GSU Admin Portal. Manage facilities, requests, and users from here.</p>
</div>

<!-- Statistics Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Facilities Requests Chart (Pie Chart) -->
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Facilities Requests Overview</h3>
        <div class="relative" style="height: 400px;">
            <div id="facilitiesChart3D"></div>
        </div>
        <div class="mt-4 text-sm text-neutral-600">
            <p>Total Requests: <strong>{{ $facilitiesStats['total'] }}</strong></p>
        </div>
    </div>

    <!-- Maintenance Tickets Chart -->
    <div class="bg-white border border-neutral-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Repair & Maintenance Overview</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="maintenanceChart"></canvas>
        </div>
        <div class="mt-4 text-sm text-neutral-600">
            <p>Total Tickets: <strong>{{ $maintenanceStats['total'] }}</strong></p>
        </div>
    </div>
</div>

<div class="quick-access-section">
  <h2>Quick access</h2>
  <div class="admin-grid">
    <a href="{{ route('admin.facilities.index') }}" class="admin-card">
        <h3>Facilities</h3>
        <p>Manage campus facilities and equipment</p>
    </a>
    <a href="{{ route('admin.forms.facilities.index') }}" class="admin-card">
        <h3> Approve Requests</h3>
        <p>Review and approve facility utilization submissions</p>
    </a>
    <a href="{{ route('admin.bookings.index') }}" class="admin-card">
        <h3>Bookings</h3>
        <p>View and adjust confirmed bookings</p>
    </a>
    <a href="{{ route('admin.users.index') }}" class="admin-card">
        <h3>User Management</h3>
        <p>Create and manage portal accounts</p>
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
    pull: [0, 0.05, 0, 0, 0], // Pull out the 2nd slice slightly for 3D effect
    textinfo: 'label+percent',
    textposition: 'outside',
    automargin: true
}];

const facilitiesLayout = {
    height: 400,
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
