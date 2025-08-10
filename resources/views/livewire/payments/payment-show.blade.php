<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
        <p class="text-gray-600">Informasi lengkap pembayaran dan penjualan terkait</p>
    </div>

    {{-- payment information --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- payment details --}}
        <div class="card">
            <div class="card-body">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Pembayaran:</span>
                        <span class="font-medium">{{ $payment->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pembayaran:</span>
                        <span class="font-medium">{{ $payment->payment_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat Oleh:</span>
                        <span class="font-medium">{{ $payment->sale->createdBy?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diperbarui Oleh:</span>
                        <span class="font-medium">{{ $payment->sale->updatedBy?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Pembayaran:</span>
                        <span class="font-semibold text-green-600">Rp
                            {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($payment->notes)
                        <div class="pt-2 border-t">
                            <span class="text-gray-600 block mb-1">Catatan:</span>
                            <p class="text-gray-800">{{ $payment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- sale information --}}
        <div class="card">
            <div class="card-body">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penjualan</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Penjualan:</span>
                        <span class="font-medium">{{ $payment->sale->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Penjualan:</span>
                        <span class="font-medium">{{ $payment->sale->sale_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Customer:</span>
                        <span class="font-medium">{{ $payment->sale->customer_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat oleh:</span>
                        <span class="font-medium">{{ $payment->sale->createdBy?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Diperbarui oleh:</span>
                        <span class="font-medium">{{ $payment->sale->updatedBy?->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Penjualan:</span>
                        <span class="font-semibold">Rp
                            {{ number_format($payment->sale->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Dibayar:</span>
                        <span class="font-medium text-green-600">Rp
                            {{ number_format($payment->sale->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sisa Tagihan:</span>
                        <span
                            class="font-semibold {{ $payment->sale->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($payment->sale->remaining_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-gray-600">Status:</span>
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $payment->sale->status === 'Sudah Dibayar' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $payment->sale->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- sale items --}}
    <div class="card mb-6">
        <div class="table-responsive overflow-x-auto">
            <table id="paymentSaleItemsTable" class="table">
                <thead>
                    <tr>
                        <th class="table-header">Gambar</th>
                        <th class="table-header">Kode</th>
                        <th class="table-header">Nama Item</th>
                        <th class="table-header text-center">Qty</th>
                        <th class="table-header text-right">Harga</th>
                        <th class="table-header text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payment->sale->saleItems as $saleItem)
                        <tr>
                            <td class="table-data">
                                @if ($saleItem->item->image)
                                    <img src="{{ Storage::url($saleItem->item->image) }}"
                                        alt="{{ $saleItem->item->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-500 text-sm"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="table-data font-medium">{{ $saleItem->item->code }}</td>
                            <td class="table-data">{{ $saleItem->item->name }}</td>
                            <td class="table-data text-center">{{ $saleItem->quantity }}</td>
                            <td class="table-data text-right">
                                @rupiah($saleItem->price)
                            </td>
                            <td class="table-data text-right font-semibold">
                                @rupiah($saleItem->total_price)
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <td colspan="5" class="table-data font-semibold text-right">Total Penjualan:</td>
                        <td class="table-data text-right font-bold text-lg">
                            @rupiah($payment->sale->total_amount)
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('payments.index') }}" class="btn-secondary">
            <i class="fas fa-circle-arrow-left mr-1"></i>
            Kembali ke Daftar
        </a>
        <a href="{{ route('sales.show', $payment->sale) }}" class="btn-secondary">
            <i class="fas fa-circle-info mr-1"></i>
            Lihat Penjualan
        </a>
        <a href="{{ route('payments.edit', $payment) }}" class="btn-primary">
            <i class="fas fa-pen-to-square mr-1"></i>
            Edit Pembayaran
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if table exists and has proper structure
            const table = document.getElementById('paymentSaleItemsTable');
            if (table && table.querySelector('thead') && table.querySelector('tbody')) {
                try {
                    $('#paymentSaleItemsTable').DataTable({
                        responsive: true,
                        pageLength: 25,
                        order: [
                            [2, 'asc']
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
                            targets: [0]
                        }]
                    });
                } catch (error) {
                    console.error('DataTables initialization error:', error);
                }
            }
        });
    </script>
</div>
