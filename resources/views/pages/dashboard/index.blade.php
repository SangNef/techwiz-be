@extends('layout.main')

@section('content')
    <div class="px-6">
        <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Trips Information Card -->
            <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col space-y-2">
                        <span class="text-gray-400">Total Trips</span>
                        <span class="text-lg font-semibold">{{ number_format($totalTrips) }}</span>
                    </div>
                    <div class="text-4xl text-blue-500 p-3">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </div>

            <!-- Total Destinations Information Card -->
            <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col space-y-2">
                        <span class="text-gray-400">Total Destinations</span>
                        <span class="text-lg font-semibold">{{ number_format($totalDestinations) }}</span>
                    </div>
                    <div class="text-4xl text-green-500 p-3">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Total Users Information Card -->
            <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col space-y-2">
                        <span class="text-gray-400">Total Users</span>
                        <span class="text-lg font-semibold">{{ number_format($totalUsers) }}</span>
                    </div>
                    <div class="text-4xl text-yellow-500 p-3">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Total Samples Information Card -->
            <div class="p-4 transition-shadow border rounded-lg shadow-sm hover:shadow-lg bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col space-y-2">
                        <span class="text-gray-400">Total Samples</span>
                        <span class="text-lg font-semibold">{{ number_format($totalSamples) }}</span>
                    </div>
                    <div class="text-4xl text-red-500 p-3">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-6 mt-6 lg:flex-row lg:gap-6">
            <!-- Monthly Trips Chart -->
            <div class="flex-1 p-4 border rounded-lg shadow-sm bg-white">
                <h3 class="text-lg font-semibold">Monthly Trips</h3>
                <canvas id="tripsChart" width="400" height="200"></canvas>
            </div>

            <div class="flex-1 p-4 border rounded-lg shadow-sm bg-white">
                <h3 class="text-lg font-semibold">Destinations, Users, Samples - Last 6 Months</h3>
                <canvas id="metricsChart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="flex flex-col gap-6 mt-6 lg:flex-row lg:gap-6">
            <div class="flex-1 p-4 border rounded-lg shadow-sm bg-white">
                <h3 class="text-lg font-semibold">Currency Usage Rate</h3>
                <canvas id="currencyChart" width="400" height="200"></canvas>
            </div>
            <div class="flex-1 p-4 border rounded-lg shadow-sm bg-white">
                <h3 class="text-lg font-semibold">Number of Samples</h3>
                <canvas id="samplesChart" width="400" height="300"></canvas>
            </div>
        </div>
        
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxTrips = document.getElementById('tripsChart').getContext('2d');
        const tripsChart = new Chart(ctxTrips, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Number of Trips',
                    data: @json($monthlytotalTrips),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxMetrics = document.getElementById('metricsChart').getContext('2d');
        const metricsChart = new Chart(ctxMetrics, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                        label: 'Number of Destinations',
                        data: @json($monthlyDestinations),
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Number of Users',
                        data: @json($monthlyUsers),
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Number of Samples',
                        data: @json($monthlySamples),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue;
                            }
                        }
                    }
                }
            }
        });

        const ctxCurrency = document.getElementById('currencyChart').getContext('2d');
        const currencyChart = new Chart(ctxCurrency, {
            type: 'pie',
            data: {
                labels: @json($currencyData->pluck('name')),
                datasets: [{
                    label: 'Usage Rate',
                    data: @json($currencyData->pluck('percentage')),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });

        const ctxSamples = document.getElementById('samplesChart').getContext('2d');
        const samplesChart = new Chart(ctxSamples, {
            type: 'bar',
            data: {
                labels: @json($currencyData->pluck('name')), // Or sample type list if available
                datasets: [{
                    label: 'Number of Samples',
                    data: @json($monthlySamples), // Update this variable with sample count data
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue;
                            }
                        }
                    }
                }
            }
        });

    </script>
@endsection
