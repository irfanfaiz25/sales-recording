<div class="container mx-auto px-4 py-6">
    <div
        class="bg-gradient-to-r from-gray-800 via-gray-900 to-gray-700 rounded-2xl shadow-2xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="page-title text-white mb-2 flex items-center">
                        Manajemen User
                    </h1>
                    <p class="page-subtitle">Kelola data user</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('users.create') }}"
                        class="bg-white text-gray-600 px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center">
                        <i class="fa fa-circle-plus mr-2"></i>
                        Tambah User
                    </a>
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
            <table id="usersTable" class="table">
                <thead>
                    <tr>
                        <th class="table-header">Nama</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">Role</th>
                        <th class="table-header">Dibuat</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="table-data">
                                <div class="font-medium text-gray-900 capitalize">{{ $user->name }}</div>
                            </td>
                            <td class="table-data">
                                {{ $user->email }}
                            </td>
                            <td class="table-data capitalize">
                                {{ $user->getRoleNames()->first() ?? '-' }}
                            </td>
                            <td class="table-data" data-order="{{ $user->created_at->format('Y-m-d') }}">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="table-data text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="border border-gray-500 hover:border-gray-600 text-gray-500 hover:text-gray-600 px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fa fa-pencil mr-1"></i>
                                        Edit
                                    </a>
                                    <button onclick="deleteUser({{ $user->id }})"
                                        class="border border-red-500 hover:border-red-600 text-red-500 hover:text-red-600 px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 transform hover:scale-105 flex items-center">
                                        <i class="fa fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-data text-center text-gray-500">
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if table exists and has proper structure
        const table = document.getElementById('usersTable');
        if (table && table.querySelector('thead') && table.querySelector('tbody')) {
            // Check if table has any data rows
            const rows = table.querySelector('tbody').getElementsByTagName('tr');
            const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');

            if (hasData) {
                try {
                    $('#usersTable').DataTable({
                        responsive: true,
                        pageLength: 25,
                        order: [
                            [3, 'desc']
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
                            targets: [4]
                        }]
                    });
                } catch (error) {
                    console.error('DataTables initialization error:', error);
                }
            }
        }
    });

    function deleteUser(userId) {
        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            @this.call('delete', userId);
        }
    }
</script>
