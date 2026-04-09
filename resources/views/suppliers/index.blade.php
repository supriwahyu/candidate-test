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

            <a href="{{ route('suppliers.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                + Add Supplier
            </a>
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
                                            <p class="text-xs text-gray-400">
                                                ID: {{ $supplier->code }}
                                            </p>
                                        </div>
                                    </td>

                                    <!-- Total -->
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $supplier->total_layups ?? 0 }}
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $supplier->created_at->format('M d, Y') }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                               class="text-blue-600 hover:underline text-sm">
                                                Edit
                                            </a>

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

</x-app-layout>