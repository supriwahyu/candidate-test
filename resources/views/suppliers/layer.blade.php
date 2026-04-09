<x-app-layout>
    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Home > Suppliers > Layups > 
            <span class="text-gray-700 dark:text-gray-200 font-medium">
                {{ $layup->name }}
            </span>
        </div>

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Layup Specification: {{ $layup->name }}
                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded-full">
                        Active
                    </span>
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Standard 5-layer panel
                </p>
            </div>

            <div class="flex gap-2">
                <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                    Duplicate
                </button>
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- Info Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <p class="text-xs text-gray-400">CREATED BY</p>
                <p class="text-sm text-gray-700 dark:text-gray-200">Eng. Dept A</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <p class="text-xs text-gray-400">LAST MODIFIED</p>
                <p class="text-sm text-gray-700 dark:text-gray-200">Oct 24, 2023</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <p class="text-xs text-gray-400">TOTAL THICKNESS</p>
                <p class="text-sm text-green-600 font-semibold">{{ $layup->thickness ?? '-' }} mm</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <p class="text-xs text-gray-400">TOTAL LAYERS</p>
                <p class="text-sm text-green-600 font-semibold">{{ $layup->layers->count() ?? '-' }} Layers</p>
            </div>

        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- LEFT: Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm">

                <div class="flex justify-between mb-4">
                    <h2 class="font-semibold text-gray-800 dark:text-white">
                        Layer Composition
                    </h2>

                    <button 
                        id="openLayerModal"
                        class="text-sm text-green-600 hover:underline">
                        + Add Layer
                    </button>
                </div>

                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 border-b">
                        <tr>
                            <th class="py-2">#</th>
                            <th>Thickness</th>
                            <th>Width</th>
                            <th>Angle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">

                        @foreach ($layup->layers as $layer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2">{{ $loop->iteration }}</td>
                            <td>{{ $layer->thickness }}mm</td>
                            <td>{{ $layer->width }}mm</td>
                            <td>{{ $layer->angle }}°</td>
                            <td>
                                <!-- Edit -->
                                <button 
                                    onclick='openEditLayerModal(@json($layer))'
                                    class="text-yellow-600 hover:underline text-xs">
                                    edit
                                </button>
                                <!-- Delete -->
                                <form method="POST"
                                      action="{{ route('layers.destroy', $layer->id) }}"
                                      onsubmit="return confirm('Delete this layer?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 text-xs">
                                        delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="text-xs text-gray-400 mt-4">
                    Showing {{ $layup->layers->count() }} layers
                </div>
            </div>

            <!-- RIGHT: Visualizer -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm flex flex-col items-center">

                <h2 class="font-semibold text-gray-800 dark:text-white mb-4">
                    Structure Visualizer
                </h2>

                <!-- Stack Visual -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg w-full max-w-xs">

                    @foreach ($layup->layers as $layer)
                        <div class="mb-2 text-center">
                            <div class="py-3 rounded text-sm font-medium
                                {{ $loop->odd 
                                    ? 'bg-yellow-200 dark:bg-yellow-700' 
                                    : 'bg-orange-200 dark:bg-orange-700' }}">
                                
                                L{{ $loop->iteration }} ({{ $layer->thickness }}mm)
                            </div>
                        </div>
                    @endforeach

                </div>

                <p class="text-xs text-gray-400 mt-4 text-center">
                    Cross-Laminated Structure Assembly
                </p>

            </div>

        </div>

        <!-- Note -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                ⚠ Engineering Note: Ensure bonding pressure is adjusted properly.
            </p>
        </div>

    </div>

    <!-- Add Layer Modal -->
    <div id="layerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6 relative">

            <!-- Close -->
            <button id="closeLayerModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                Add Layer
            </h2>

            <form method="POST" action="{{ route('layers.store') }}">
                @csrf

                <!-- Layup ID -->
                <input type="hidden" name="layup_id" value="{{ $layup->id }}">

                <div class="space-y-4">

                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Order</label>
                        <input type="number" name="layer_order"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="e.g. 1"
                            required>
                    </div>

                    <!-- Thickness -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Thickness (mm)</label>
                        <input type="number" name="thickness"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                    </div>

                    <!-- Width -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Width (mm)</label>
                        <input type="number" name="width"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Angle -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Angle</label>
                        <select name="angle"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="0">0°</option>
                            <option value="90">90°</option>
                        </select>
                    </div>

                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelLayerModal"
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

    <!-- Edit Layer Modal -->
    <div id="editLayerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6 relative">

            <!-- Close -->
            <button onclick="closeEditLayerModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                ✕
            </button>

            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                Edit Layer
            </h2>

            <form id="editLayerForm" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <!-- Order -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Order</label>
                        <input type="number" name="layer_order" id="edit_layer_order"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Thickness -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Thickness</label>
                        <input type="number" name="thickness" id="edit_thickness"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Width -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Width</label>
                        <input type="number" name="width" id="edit_width"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Angle -->
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Angle</label>
                        <select name="angle" id="edit_angle"
                            class="w-full mt-1 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="0">0°</option>
                            <option value="90">90°</option>
                        </select>
                    </div>

                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeEditLayerModal()"
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
    const layerModal = document.getElementById('layerModal');
    const openLayerBtn = document.getElementById('openLayerModal');
    const closeLayerBtn = document.getElementById('closeLayerModal');
    const cancelLayerBtn = document.getElementById('cancelLayerModal');

    // Open
    openLayerBtn.addEventListener('click', () => {
        layerModal.classList.remove('hidden');
        layerModal.classList.add('flex');
    });

    // Close functions
    function closeLayerModalFunc() {
        layerModal.classList.add('hidden');
        layerModal.classList.remove('flex');
    }

    closeLayerBtn.addEventListener('click', closeLayerModalFunc);
    cancelLayerBtn.addEventListener('click', closeLayerModalFunc);

    // Click outside
    layerModal.addEventListener('click', (e) => {
        if (e.target === layerModal) {
            closeLayerModalFunc();
        }
    });

    // ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLayerModalFunc();
    });
</script>

<script>
    function openEditLayerModal(layer) {
        const modal = document.getElementById('editLayerModal');
        const form = document.getElementById('editLayerForm');

        // set dynamic action
        form.action = `/layers/update/${layer.id}`;

        // fill data
        document.getElementById('edit_layer_order').value = layer.layer_order ?? '';
        document.getElementById('edit_thickness').value = layer.thickness ?? '';
        document.getElementById('edit_width').value = layer.width ?? '';
        document.getElementById('edit_angle').value = parseInt(layer.angle) ?? 0;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditLayerModal() {
        const modal = document.getElementById('editLayerModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // click outside
    document.getElementById('editLayerModal').addEventListener('click', function(e){
        if(e.target === this) closeEditLayerModal();
    });

    // ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditLayerModal();
    });
</script>