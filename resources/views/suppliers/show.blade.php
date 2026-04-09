<x-app-layout>
    <div class="p-6 bg-gray-100 min-h-screen">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500 mb-4">
            Suppliers / <span class="text-gray-700 font-medium">{{ $supplier->name }}</span>
        </div>

        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">
                        {{ $supplier->name }}
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                            Active Partner
                        </span>
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        ID : {{ $supplier->code }}
                    </p>
                </div>

                <button class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100">
                    ✏️ Edit Supplier
                </button>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">NAME</p>
                    <p class="text-sm text-gray-700">
                        {{ $supplier->name }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">LOCATION</p>
                    <p class="text-sm text-gray-700">
                        {{ $supplier->location }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">MATERIAL CERTIFICATIONS</p>
                    <p class="text-sm text-gray-700">
                        {{ $supplier->certification }}
                    </p>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-xs text-gray-400 mb-1">LAST AUDIT DATE</p>
                    <p class="text-sm text-gray-700">
                        {{ $supplier->last_audit_date }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">

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
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $layup->code ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $layup->name }}</td>
                            <td class="px-4 py-3">{{ $layup->thickness ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded">
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
                        <label class="text-sm text-gray-600 dark:text-gray-300">Supplier</label>
                        
                        <select name="supplier_id"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                            
                            <option value="">-- Select Supplier --</option>

                            @foreach ($suppliers as $sup)
                                <option value="{{ $sup->id }}"
                                    {{ $sup->id == $supplier->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

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
