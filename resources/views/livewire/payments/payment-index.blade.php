<div>
    <div
        class="bg-gradient-to-r from-gray-800 via-gray-900 to-gray-700 rounded-2xl shadow-2xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                        Pembayaran
                    </h1>
                    <p class="text-green-100 text-lg">Kelola semua transaksi pembayaran</p>
                </div>
                <div class="flex flex-col justify-center gap-2">
                    <div class="flex gap-2">
                        <a href="{{ route('payments.create') }}"
                            class="flex-1 bg-white text-gray-600 px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex justify-center items-center">
                            <i class="fa fa-circle-plus mr-2"></i>
                            Tambah Pembayaran
                        </a>
                        <button class="btn-danger" wire:click='resetFilter'>
                            <i class="fa fa-repeat mr-2"></i>
                            Reset Filter
                        </button>
                    </div>

                    <p class="text-sm text-white">
                        Filter tanggal:
                    </p>

                    {{-- filter payments by date range --}}
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

    <div
        class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 hover:shadow-3xl transition-all duration-300">
        <div class="table-responsive">
            <table id="paymentsTable" class="table display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Penjualan</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td class="font-medium">{{ $payment->code }}</td>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td>
                                <div>
                                    <div class="font-medium">{{ $payment->sale->code }}</div>
                                    <div class="text-sm text-gray-600">{{ $payment->sale->user->name ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($payment->notes)
                                    <span class="text-sm">{{ Str::limit($payment->notes, 50) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('payments.show', $payment) }}"
                                        class="border border-gray-500 hover:border-gray-600 text-gray-500 hover:text-gray-600 px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fas fa-circle-info mr-1"></i>
                                        Lihat
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}"
                                        class="border border-gray-500 hover:border-gray-600 text-gray-500 hover:text-gray-600 px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fas fa-pen-to-square mr-1"></i>
                                        Edit
                                    </a>
                                    <button onclick="deletePayment({{ $payment->id }})"
                                        class="border border-red-500 hover:border-red-600 text-red-500 hover:text-red-600 px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if table exists and has proper structure
            const table = document.getElementById('paymentsTable');
            if (table && table.querySelector('thead') && table.querySelector('tbody')) {
                try {
                    $('#paymentsTable').DataTable({
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
                            targets: [5] // Disable sorting for Actions column
                        }]
                    });
                } catch (error) {
                    console.error('DataTables initialization error:', error);
                }
            }
        });

        function deletePayment(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')) {
                @this.call('delete', id);
            }
        }
    </script>
</div>
