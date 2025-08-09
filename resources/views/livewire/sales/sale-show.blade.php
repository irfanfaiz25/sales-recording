<div>
    <div class="page-header">
        <h1 class="page-title">Detail Penjualan</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- detail sale --}}
        <div class="card">
            <div class="card-body">
                <h2 class="text-lg font-semibold mb-4">Informasi Penjualan</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Kode:</span>
                        <span>{{ $sale->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Tanggal:</span>
                        <span>{{ $sale->sale_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Customer:</span>
                        <span>{{ $sale->customer_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Dibuat Oleh:</span>
                        <span>{{ $sale->createdBy?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Diperbarui Oleh:</span>
                        <span>{{ $sale->updatedBy?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Status:</span>
                        <span
                            class="px-2 py-1 rounded text-sm
                        @if ($sale->status === 'Sudah Dibayar') bg-green-100 text-green-800
                        @elseif($sale->status === 'Belum Dibayar Sepenuhnya') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                            {{ $sale->status }}
                        </span>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span>
                            @rupiah($sale->total_amount)
                        </span>
                    </div>
                    @if ($sale->total_paid > 0)
                        <div class="flex justify-between">
                            <span class="font-medium">Sudah Dibayar:</span>
                            <span>
                                @rupiah($sale->total_paid)
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Sisa:</span>
                            <span>
                                @rupiah($sale->remaining_amount)
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- payment history --}}
        <div class="card">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Riwayat Pembayaran</h2>
                    @if ($sale->status !== 'Sudah Dibayar')
                        <a href="{{ route('payments.create', ['sale_id' => $sale->id]) }}" class="btn-primary">
                            Tambah Pembayaran
                        </a>
                    @endif
                </div>

                @if ($sale->payments->count() > 0)
                    <div class="space-y-3">
                        @foreach ($sale->payments as $payment)
                            <div class="border rounded-lg p-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium">{{ $payment->code }}</div>
                                        <div class="text-sm text-gray-600">{{ $payment->payment_date->format('d/m/Y') }}
                                        </div>
                                        @if ($payment->notes)
                                            <div class="text-sm text-gray-600 mt-1">{{ $payment->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-lg">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                        <div class="flex gap-2 mt-2">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200">
                                                <i class="fas fa-circle-info mr-1 text-xs"></i>
                                                <span class="text-sm">Detail</span>
                                            </a>
                                            <a href="{{ route('payments.edit', $payment) }}"
                                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors duration-200">
                                                <i class="fas fa-pen-to-square mr-1 text-xs"></i>
                                                <span class="text-sm">Edit</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada pembayaran</p>
                @endif
            </div>
        </div>
    </div>

    {{-- sale items --}}
    <div class="card mt-6">
        <div class="card-body overflow-x-auto">
            <table id="saleItemsTable" class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleItems as $saleItem)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if ($saleItem->item->image)
                                        <img src="{{ Storage::url($saleItem->item->image) }}"
                                            alt="{{ $saleItem->item->name }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fa fa-image text-sm text-gray-500"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium">{{ $saleItem->item->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $saleItem->item->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $saleItem->quantity }}</td>
                            <td>Rp {{ number_format($saleItem->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($saleItem->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="3">Total</td>
                        <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="flex justify-end gap-4 mt-6">
        <a href="{{ route('sales.index') }}" class="btn-secondary">
            <i class="fas fa-circle-arrow-left mr-2"></i>
            Kembali
        </a>
        @if ($sale->status !== 'Sudah Dibayar')
            <a href="{{ route('sales.edit', $sale) }}" class="btn-primary">
                <i class="fas fa-pen-to-square mr-1"></i>
                Edit Penjualan
            </a>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if table exists and has proper structure
                const table = document.getElementById('saleItemsTable');
                if (table && table.querySelector('thead') && table.querySelector('tbody')) {
                    try {
                        $('#saleItemsTable').DataTable({
                            responsive: true,
                            pageLength: 25,
                            order: [
                                [0, 'asc']
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
                                targets: []
                            }]
                        });
                    } catch (error) {
                        console.error('DataTables initialization error:', error);
                    }
                }
            });
        </script>
    @endpush
</div>
