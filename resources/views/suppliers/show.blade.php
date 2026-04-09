<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen dark:bg-gray-900 dark:text-gray-300">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500 mb-4 dark:bg-gray-900 dark:text-gray-300">
            Suppliers / <span class="text-gray-700 font-medium dark:text-gray-300">{{ $supplier->name }}</span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 dark:bg-gray-800 dark:text-gray-300">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-300">
                        {{ $supplier->name }}
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                            Active Partner
                        </span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-300">
                        ID : {{ $supplier->name }}
                    </p>
                </div>

                <button 
                    id="openEditSupplierModal"
                    class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    ✏️ Edit Supplier
                </button>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">NAME</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $supplier->name }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">LOCATION</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $supplier->location ?? '-' }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">MATERIAL CERTIFICATIONS</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $supplier->certification ?? '-' }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">LAST AUDIT DATE</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        {{ $supplier->last_audit_date ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-gray-800">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Associated Layups
                </h2>

                <div class="flex gap-2">
                    <button class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-100">
                        ⬆ Import
                    </button>
                    <button class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-100">
                        ⬇ Export
                    </button>
                    <button 
                        id="openModalBtn"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        + Add Layup
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border rounded-lg overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Layup ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Thickness</th>
                            <th class="px-4 py-3">Ply Count</th>
                            <th class="px-4 py-3">Species/Grade</th>
                            <th class="px-4 py-3">Revision</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">

                        @foreach ($supplier->layups as $layup)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">{{ $layup->code ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $layup->name }}</td>
                            <td class="px-4 py-3">{{ $layup->thickness ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded">
                                    {{ $layup->ply_count ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $layup->species ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $layup->revision ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if ($layup->status == 'active')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                        Active
                                    </span>
                                @elseif ($layup->status == 'draft')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                        Draft
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-200 text-gray-600 rounded-full">
                                        Archived
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('layups.show', $layup->id) }}"
                                   class="text-blue-600 hover:underline text-sm">
                                    View
                                </a>

                                <!-- Edit -->
                                <button 
                                    onclick='openEditLayupModal(@json($layup))'
                                    class="text-yellow-600 hover:underline text-sm">
                                    Edit
                                </button>

                                <!-- Delete -->
                                <form method="POST" action="{{ route('layups.destroy', $layup->id) }}"
                                      onsubmit="return confirm('Delete this layup?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="text-red-600 hover:underline text-sm">
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="text-xs text-gray-500 mt-4">
                Showing {{ $supplier->layups->count() }} layups
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div id="layupModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <!-- Modal Box -->
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-xl shadow-lg p-6 relative">

            <!-- Close Button -->
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                Add Layup
            </h2>

            <!-- Form -->
            <form method="POST" action="{{ route('layups.store') }}">
                @csrf

                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Name</label>
                        <input type="text" name="name"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                    </div>

                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div id="editSupplierModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6 relative">

            <!-- Close -->
            <button id="closeEditSupplierModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                Edit Supplier
            </h2>

            <form method="POST" action="{{ route('suppliers.edit', $supplier->id) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Name</label>
                    <input type="text" name="name"
                        value="{{ $supplier->name }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg 
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelEditSupplierModal"
                        class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Edit Layup Modal -->
    <div id="editLayupModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6 relative">

            <!-- Close -->
            <button onclick="closeEditLayupModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                Edit Layup
            </h2>

            <form id="editLayupForm" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label class="text-sm text-gray-600 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditLayupModal()"
                        class="px-4 py-2 text-sm border rounded-lg">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-app-layout>

<script>
    const modal = document.getElementById('layupModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    // Open
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // Close (X)
    closeBtn.addEventListener('click', closeModal);

    // Cancel button
    cancelBtn.addEventListener('click', closeModal);

    // Click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this layup?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<script>
    const editModal = document.getElementById('editSupplierModal');
    const openEditBtn = document.getElementById('openEditSupplierModal');
    const closeEditBtn = document.getElementById('closeEditSupplierModal');
    const cancelEditBtn = document.getElementById('cancelEditSupplierModal');

    // Open
    openEditBtn.addEventListener('click', () => {
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    });

    // Close
    function closeEditModal() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }

    closeEditBtn.addEventListener('click', closeEditModal);
    cancelEditBtn.addEventListener('click', closeEditModal);

    // Click outside
    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) {
            closeEditModal();
        }
    });

    // ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditModal();
    });
</script>

<script>
    function openEditLayupModal(layup) {
        const modal = document.getElementById('editLayupModal');
        const form = document.getElementById('editLayupForm');

        // set form action dynamically
        form.action = `/layups/update/${layup.id}`;

        // fill inputs
        document.getElementById('edit_name').value = layup.name ?? '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditLayupModal() {
        const modal = document.getElementById('editLayupModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // click outside
    document.getElementById('editLayupModal').addEventListener('click', function(e){
        if(e.target === this) closeEditLayupModal();
    });

    // ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditLayupModal();
    });
</script>
