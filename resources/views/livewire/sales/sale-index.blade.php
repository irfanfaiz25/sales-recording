<div>
    <div
        class="bg-gradient-to-r from-gray-800 via-gray-900 to-gray-700 rounded-2xl shadow-2xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                        Daftar Penjualan
                    </h1>
                    <p class="text-blue-100 text-lg">Kelola semua transaksi penjualan Anda</p>
                </div>
                <div class="flex flex-col justify-center gap-2">
                    <div class="flex gap-2">
                        <a href="{{ route('sales.create') }}"
                            class="flex-1 bg-white text-gray-600 px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex justify-center items-center">
                            <i class="fa fa-circle-plus mr-2"></i>
                            Tambah Penjualan
                        </a>
                        <button class="btn-danger" wire:click='resetFilter'>
                            <i class="fa fa-repeat mr-2"></i>
                            Reset Filter
                        </button>
                    </div>

                    <p class="text-sm text-white">
                        Filter tanggal:
                    </p>

                    {{-- Filter sales by date range --}}
                    <div class="flex items-center gap-2">
                        <div>
                            <input type="date" id="dateStart" class="form-input" placeholder="Pilih Tanggal"
                                wire:model.live.debounce.300ms="dateStart">
                        </div>
                        <div>
                            <input type="date" id="dateEnd" class="form-input" placeholder="Pilih Tanggal"
                                wire:model.live.debounce.300ms="dateEnd">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
    </div>

    {{-- flash message --}}
    @if (session()->has('message'))
        <div
            class="bg-gradient-to-r from-green-400 to-green-600 text-white px-6 py-4 rounded-xl mb-6 shadow-lg flex items-center animate-slide-in">
            <i class="fa fa-circle-check mr-2"></i>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="bg-gradient-to-r from-red-400 to-red-600 text-white px-6 py-4 rounded-xl mb-6 shadow-lg flex items-center animate-slide-in">
            <i class="fa fa-circle-xmark mr-2"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- sales table --}}
    <div
        class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 hover:shadow-3xl transition-all duration-300">
        <div class="table-responsive">
            <table id="salesTable" class="table display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode Penjualan</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>
                                {{ $sale->code }}
                            </td>
                            <td>
                                {{ $sale->sale_date->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ $sale->customer_name ?? '-' }}
                            </td>
                            <td>
                                <div class="text-sm">
                                    @foreach ($sale->saleItems->take(2) as $item)
                                        <div>{{ $item->item->name }} ({{ $item->quantity }}x)</div>
                                    @endforeach
                                    @if ($sale->saleItems->count() > 2)
                                        <div class="text-gray-500">+{{ $sale->saleItems->count() - 2 }} item lainnya
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                    @if ($sale->status === 'Sudah Dibayar') bg-green-100 text-green-800
                                    @elseif($sale->status === 'Belum Dibayar Sepenuhnya') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('sales.show', $sale) }}"
                                        class="border border-gray-500 hover:border-gray-600 text-gray-500 hover:text-gray-600 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fas fa-circle-info mr-1"></i>
                                        Lihat
                                    </a>
                                    @if ($sale->status !== 'Sudah Dibayar')
                                        <a href="{{ route('sales.edit', $sale) }}"
                                            class="border border-gray-500 hover:border-gray-600 text-gray-500 hover:text-gray-600 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                            <i class="fas fa-pen-to-square mr-1"></i>
                                            Edit
                                        </a>
                                        <button onclick="deleteSale({{ $sale->id }})"
                                            class="border border-red-500 hover:border-red-600 text-red-500 hover:text-red-600 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center cursor-pointer">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if table exists and has proper structure
                const table = document.getElementById('salesTable');
                if (table && table.querySelector('thead') && table.querySelector('tbody')) {
                    try {
                        $('#salesTable').DataTable({
                            responsive: true,
                            pageLength: 25,
                            order: [
                                [1, 'desc']
                            ],
                            destroy: true,
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ data per halaman",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                                infoFiltered: "(disaring dari _MAX_ total data)",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "Selanjutnya",
                                    previous: "Sebelumnya"
                                },
                                emptyTable: "Tidak ada data yang tersedia",
                                zeroRecords: "Tidak ditemukan data yang sesuai"
                            },
                            columnDefs: [{
                                orderable: false,
                                targets: [3, 6] // Disable sorting for Items and Actions columns
                            }]
                        });
                    } catch (error) {
                        console.error('DataTables initialization error:', error);
                    }
                }
            });

            function deleteSale(id) {
                if (confirm('Apakah Anda yakin ingin menghapus penjualan ini?')) {
                    @this.call('delete', id);
                }
            }
        </script>
    @endpush

</div>
