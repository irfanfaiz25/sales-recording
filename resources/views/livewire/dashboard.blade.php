<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Dashboard
            </h1>
            <p class="text-gray-600">
                Selamat datang kembali, {{ Auth::user()->name }}
            </p>
        </div>

        {{-- Date Range Filter --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Filter Periode
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai
                    </label>
                    <input wire:model.live="startDate" type="date" id="start_date"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Selesai
                    </label>
                    <input wire:model.live="endDate" type="date" id="end_date"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent transition duration-200">
                </div>
            </div>
        </div>

        {{-- Widgets --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Transaction Count Widget --}}
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">
                            Total Transaksi
                        </p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $this->transactionCount }}
                        </p>
                    </div>
                    <div class="w-12 h-12 p-3 flex justify-center items-center bg-gray-100 text-gray-600 rounded-full">
                        <i class="fas fa-arrow-right-arrow-left"></i>
                    </div>
                </div>
            </div>

            {{-- Total Sales Widget --}}
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">
                            Total Penjualan
                        </p>
                        <p class="text-3xl font-bold text-gray-900">
                            Rp @rupiah($this->totalSales)
                        </p>
                    </div>
                    <div class="w-12 h-12 p-3 flex justify-center items-center bg-gray-100 rounded-full">
                        <i class="fas fa-hand-holding-usd"></i>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Items Sold Widget --}}
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 text-gray-600 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">
                            Item Terjual
                        </p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $this->totalItemsSold }}
                        </p>
                    </div>
                    <div class="w-12 h-12 p-3 flex justify-center items-center bg-gray-100 rounded-full">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Monthly Sales Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Penjualan per Bulan</h3>
                <div class="h-80">
                    <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                </div>
            </div>

            {{-- Item Sales Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Item Terlaris</h3>
                <div class="h-80">
                    <canvas id="itemSalesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let monthlySalesChart = null;
        let itemSalesChart = null;

        function createCharts(monthlySalesData, itemSalesData) {
            // Destroy existing charts if they exist
            if (monthlySalesChart) {
                monthlySalesChart.destroy();
            }
            if (itemSalesChart) {
                itemSalesChart.destroy();
            }

            // Monthly Sales Line Chart
            const monthlyCtx = document.getElementById('monthlySalesChart');
            if (monthlyCtx) {
                monthlySalesChart = new Chart(monthlyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: monthlySalesData.map(item => item.month),
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: monthlySalesData.map(item => item.total),
                            borderColor: '#000000',
                            backgroundColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#000000',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Item Sales Bar Chart
            const itemCtx = document.getElementById('itemSalesChart');
            if (itemCtx) {
                itemSalesChart = new Chart(itemCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: itemSalesData.map(item => item.name),
                        datasets: [{
                            label: 'Qty Terjual',
                            data: itemSalesData.map(item => item.total_qty),
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            borderColor: '#000000',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const initialMonthlySalesData = @json($this->monthlySalesData);
            const initialItemSalesData = @json($this->itemSalesData);
            createCharts(initialMonthlySalesData, initialItemSalesData);
        });

        // Listen for Livewire events to refresh charts
        document.addEventListener('livewire:init', function() {
            Livewire.on('refreshCharts', function(data) {
                createCharts(data[0].monthlySalesData, data[0].itemSalesData);
            });
        });
    </script>
@endpush
