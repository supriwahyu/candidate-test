<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Suppliers
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage timber suppliers and material sourcing.
                </p>
            </div>

            <button onclick="openAddModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                + Add Supplier
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search & Actions -->
            <div class="flex items-center justify-between mb-4">
                <div class="w-1/3">
                    <input type="text"
                           placeholder="Search suppliers by name..."
                           class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex gap-2">
                    <button class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        Filter
                    </button>
                    <button class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        Export
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="text-left px-6 py-3">Name</th>
                                <th class="text-left px-6 py-3">Total Layups</th>
                                <th class="text-left px-6 py-3">Created At</th>
                                <th class="text-right px-6 py-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse ($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    
                                    <!-- Name -->
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        <div class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-600 text-xs font-semibold text-gray-600 dark:text-gray-200">
                                            {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                                {{ $supplier->name }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Total -->
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $supplier->total_layups ?? 0 }}
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $supplier->created_at }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button 
                                                onclick='openEditModal(@json($supplier))'
                                                class="text-blue-600 hover:underline text-sm">
                                                Edit
                                            </button>

                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this supplier?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="text-red-600 hover:underline text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-400">
                                        No suppliers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                    <p>
                        Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} 
                        of {{ $suppliers->total() }} results
                    </p>

                    {{ $suppliers->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">

            <button onclick="closeAddModal()" class="absolute top-3 right-3">✕</button>

            <h2 class="text-lg font-semibold mb-4">Add Supplier</h2>

            <form action="{{ route('suppliers.create') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="text-sm">Name</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-2 border rounded-lg" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 border rounded-lg">
                        Cancel
                    </button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">

            <button onclick="closeEditModal()" class="absolute top-3 right-3">✕</button>

            <h2 class="text-lg font-semibold mb-4">Edit Supplier</h2>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="text-sm">Name</label>
                    <input type="text" name="name" id="editName"
                        class="w-full px-4 py-2 border rounded-lg" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg">
                        Cancel
                    </button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>

<script>
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    function openAddModal() {
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
    }

    function closeAddModal() {
        addModal.classList.add('hidden');
        addModal.classList.remove('flex');
    }

    function openEditModal(supplier) {
        document.getElementById('editName').value = supplier.name;

        // set form action dynamically
        document.getElementById('editForm').action = `/suppliers/edit/${supplier.id}`;

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }

    // click outside to close
    window.addEventListener('click', function(e) {
        if (e.target === addModal) closeAddModal();
        if (e.target === editModal) closeEditModal();
    });
</script>