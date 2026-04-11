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

        @if(session('conflicts') && count(session('conflicts')))
        <div class="mt-4 bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
            <p class="font-semibold text-sm mb-2">⚠ Import Issues:</p>

            <ul class="text-xs text-gray-700 list-disc ml-4">
                @foreach(session('conflicts') as $conflict)
                    <li>
                        Row {{ $conflict['row'] }} - {{ $conflict['error'] }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-gray-800">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Associated Layups
                </h2>

                <div class="flex gap-2">
                    <button onclick="openImportModal()"
                        class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-100">
                        ⬆ Import
                    </button>
                    <a href="{{ route('suppliers.export', $supplier->id) }}"
                       class="px-3 py-2 text-sm border rounded-lg hover:bg-gray-100 inline-block">
                        ⬇ Export
                    </a>
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

    <!-- Modal Overlay -->
    <div id="importModal"
         class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 overflow-y-auto">

        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Import Layout Data</h2>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <!-- Form -->
            <form method="POST"
                  action="{{ route('suppliers.import', $supplier->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="file_temp" value="{{ session('file_temp') }}">

                <!-- Upload Area -->
                <label id="uploadLabel"
                       class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer block hover:bg-gray-50 transition">

                    <input type="file"
                           name="file"
                           id="fileInput"
                           class="hidden"
                           {{ session('preview_tree') ? '' : 'required' }}>

                    <div id="uploadContent" class="text-gray-500">
                        <div class="text-2xl mb-2">☁️</div>
                        <p class="text-sm font-medium">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400">XLSX or CSV up to 10MB</p>
                    </div>
                </label>

                @if(session('file_temp'))
                    <p class="text-xs text-green-600 mt-2">
                        File uploaded ✓
                    </p>
                @endif

                @if(session('preview_tree'))
                <div class="mt-4 border rounded-lg p-3 bg-gray-50 max-h-60 overflow-y-auto">

                    <p class="text-sm font-semibold mb-2">📊 Preview Structure</p>

                    @foreach(session('preview_tree') as $supplier => $layups)
                        <div class="font-semibold text-gray-800">{{ $supplier }}</div>

                        @foreach($layups as $layup => $layers)
                            <div class="ml-4 text-blue-600">{{ $layup }}</div>

                            @foreach($layers as $layer)
                                <div class="ml-8 text-gray-600 text-xs">- {{ $layer }}</div>
                            @endforeach

                        @endforeach
                    @endforeach

                </div>
                @endif

                <!-- Conflict Strategy -->
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-700">
                        Conflict Resolution Strategy
                    </label>

                    <select name="strategy"
                            class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="skip">Skip conflicts (Default)</option>
                        <option value="overwrite">Overwrite existing</option>
                        <option value="merge">Merge data</option>
                    </select>
                </div>

                <!-- Dry Run -->
                <div class="mt-4 flex items-start gap-2 border rounded-lg p-3">
                    <input type="checkbox" name="dry_run" value="1" class="mt-1">
                    <div>
                        <p class="text-sm font-medium">Run as Dry Run</p>
                        <p class="text-xs text-gray-500">
                            Simulate import without saving changes
                        </p>
                    </div>
                </div>

                <!-- Conflict Alert -->
                <div class="mt-4 bg-red-50 border border-red-200 text-red-600 p-3 rounded-lg text-sm">
                    ⚠ Potential Conflicts Detected <br>
                    <span class="text-xs">
                        Some data may differ from existing records.
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-6">

                    <button type="button"
                        onclick="closeImportModal()"
                        class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>

                    @if(!session('preview_tree'))
                        <!-- STEP 1: PREVIEW -->
                        <button type="submit"
                                name="preview"
                                value="1"
                                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Preview
                        </button>
                    @else
                        <!-- STEP 2: CONFIRM -->
                        <button type="submit"
                                class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Confirm Import
                        </button>
                    @endif

                </div>
            </form>
        </div>
    </div>

    @php
        $conflicts = session('conflicts', []);

        $activeIndex = session('conflict_index', 0);

        $activeConflict = $conflicts[$activeIndex] ?? null;

        $existing = $activeConflict['existing']['layers'] ?? [];
        $importing = $activeConflict['importing']['layers'] ?? [];

        $existingLayup = $activeConflict['existing']['layup'] ?? [];
        $importingLayup = $activeConflict['importing']['layup'] ?? [];

        $differences = $activeConflict['differences'] ?? [];
    @endphp

    <div id="conflictModal" class="hidden">
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
">
            <div class="bg-white w-full max-w-6xl rounded-xl shadow-lg p-6 max-h-[90vh]">

                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-lg font-semibold">
                            Conflict Resolution: Import [{{ $fileName ?? 'file.xlsx' }}]
                        </h2>
                        <p class="text-sm text-gray-500">
                            Please review discrepancies between incoming data and existing records.
                        </p>
                    </div>
                    <button onclick="closeModalConflict()" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <div class="grid grid-cols-4 gap-4">

                    <!-- LEFT SIDEBAR -->
                    <div class="col-span-1 border rounded-lg p-3 overflow-auto max-h-[50vh]">

                        @php
                            $conflicts = session('conflicts', []);
                            $activeIndex = session('conflict_index', 0);
                        @endphp

                        <h3 class="text-sm font-semibold mb-2 text-gray-600">
                            ⚠ Conflicting Layups ({{ count($conflicts) }})
                        </h3>

                        @if(empty($conflicts))
                            <p class="text-xs text-gray-400">
                                No conflicts found
                            </p>
                        @else

                            <div class="space-y-2">

                                @foreach($conflicts as $index => $conflict)

                                    @php
                                        $isActive = $activeIndex == $index;

                                        $layupName =
                                            $conflict['importing']['layup']['name']
                                            ?? $conflict['importing']['layup']
                                            ?? null;
                                    @endphp

                                    <div onclick="selectConflict({{ $index }})"
                                         class="p-3 rounded-lg border cursor-pointer transition duration-150
                                         {{ $isActive
                                            ? 'bg-green-50 border-green-400'
                                            : 'hover:bg-gray-50 border-gray-200' }}">

                                        {{-- Layup name (only if exists) --}}
                                        @if(filled($layupName))
                                            <p class="text-sm font-medium">
                                                {{ $layupName }}
                                            </p>
                                        @endif

                                        {{-- Error ALWAYS show --}}
                                        <p class="text-xs text-gray-500">
                                            {{ $conflict['row'] ?? 'No error message' }}
                                        </p>

                                        {{-- Optional badge --}}
                                        @if(isset($conflict['type']))
                                            <span class="text-[10px] px-2 py-0.5 bg-gray-200 rounded">
                                                {{ $conflict['type'] }}
                                            </span>
                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        @endif

                        <div class="mt-4 ">
                            <h4 class="text-xs text-gray-400 mb-2">RESOLVED</h4>
                            @foreach($resolved ?? [] as $item)
                                <div class="text-xs text-green-600 flex items-center gap-1">
                                    ✔ {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- MAIN CONTENT -->
                    <div class="col-span-3">

                        <!-- Title -->
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold">
                                {{ $existingLayup['name'] ?? $importingLayup['name'] ?? 'CLT' }} Comparison
                            </h3>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                {{ count($differences ?? []) }} LAYERS
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <!-- EXISTING -->
                            <div class="border rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Existing Version</h4>

                                <table class="w-full text-sm">
                                    <thead class="text-gray-500 text-xs">
                                        <tr>
                                            <th>ORDER</th>
                                            <th>THICKNESS</th>
                                            <th>WIDTH</th>
                                            <th>ANGLE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($existing as $row)
                                        <tr class="text-center border-t">
                                            <td>{{ $row['layer_order'] }}</td>

                                            <td class="{{ in_array('thickness', $differences) ? 'text-red-500 font-semibold' : '' }}">
                                                {{ $row['thickness'] }}
                                            </td>

                                            <td class="{{ in_array('width', $differences) ? 'text-red-500 font-semibold' : '' }}">
                                                {{ $row['width'] }}
                                            </td>

                                            <td class="{{ in_array('angle', $differences) ? 'text-red-500 font-semibold' : '' }}">
                                                {{ $row['angle'] }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button class="mt-4 w-full border border-green-500 text-green-600 py-2 rounded-lg hover:bg-green-50">
                                    Keep Existing
                                </button>
                            </div>

                            <!-- IMPORTING -->
                            <div class="border rounded-lg p-3">
                                <h4 class="text-sm font-semibold mb-2">Importing Version</h4>

                                <table class="w-full text-sm">
                                    <thead class="text-gray-500 text-xs">
                                        <tr>
                                            <th>ORDER</th>
                                            <th>THICKNESS</th>
                                            <th>WIDTH</th>
                                            <th>ANGLE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($importing as $row)
                                        <tr class="text-center border-t">
                                            <td>{{ $row['layer_order'] ?? '' }}</td>

                                            <td class="{{ in_array('thickness', $differences) ? 'text-red-600 font-semibold' : '' }}">
                                                {{ $row['thickness'] ?? '' }}
                                            </td>

                                            <td class="{{ in_array('width', $differences) ? 'text-red-600 font-semibold' : '' }}">
                                                {{ $row['width'] ?? '' }}
                                            </td>

                                            <td class="{{ in_array('angle', $differences) ? 'text-red-600 font-semibold' : '' }}">
                                                {{ $row['angle'] ?? '' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                                    Accept New
                                </button>
                            </div>

                        </div>

                        <!-- FOOTER NAV -->
                        <div class="flex justify-between items-center mt-4 text-sm">
                            <button class="text-gray-500 hover:underline">← Previous Conflict</button>

                            <span class="text-gray-400">
                                1 of {{ count(session('conflicts', [])) }} discrepancies
                            </span>

                            <button class="text-green-600 hover:underline">Next Conflict →</button>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-4 text-left">
                    <button onclick="closeModalConflict()" class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Cancel Import
                    </button>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('preview_tree'))
            document.getElementById('importModal').classList.remove('hidden');
            document.getElementById('importModal').classList.add('flex');
        @endif
    });
</script>

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

<script>
    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
        document.getElementById('importModal').classList.add('flex');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }
</script>

<script>
    const fileInput = document.getElementById('fileInput');
    const uploadContent = document.getElementById('uploadContent');
    const uploadLabel = document.getElementById('uploadLabel');

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];

            uploadContent.innerHTML = `
                <div class="text-green-600 text-2xl mb-2">✔</div>
                <p class="text-sm font-semibold text-gray-700">${file.name}</p>
                <p class="text-xs text-gray-400">
                    ${(file.size / 1024).toFixed(2)} KB
                </p>
                <p class="text-xs text-blue-500 mt-1">Click to change file</p>
            `;

            uploadLabel.classList.add('border-green-500', 'bg-green-50');
        }
    });
</script>

<script>
    function closeModalConflict() {
        const modal = document.getElementById('conflictModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // CLEAR SESSION VIA AJAX
        fetch("{{ url('/conflict/clear-session') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });
    }
</script>

<script>
    function selectConflict(index) {
        window.location.href = "{{ url('/conflict/select') }}/" + index;
    }
</script>

@if(session('show_conflict_modal'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("conflictModal").classList.remove("hidden");
        });
    </script>
@endif
