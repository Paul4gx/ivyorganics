<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.shipping.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $country->name }} - States/Provinces
                </h2>
            </div>
            <div class="space-x-2">
                <button type="button" onclick="document.getElementById('addStateModal').classList.remove('hidden')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Add State
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Country Info -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Country</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $country->name }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Code</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $country->code }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Default Shipping Fee</h4>
                    <p class="text-lg font-semibold text-gray-900">{{$country->code == 'NG'? '₦':'$';}}{{ number_format($country->default_shipping_fee,2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- States Section -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">States/Provinces</h3>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">State/Province</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Fee ({{$country->code == 'NG'? '₦':'$';}})</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($states as $state)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $state->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $state->code ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$country->code == 'NG'? '₦':'$';}}{{ number_format($state->shipping_fee, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $state->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $state->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editState({{ $state->id }}, '{{ $state->name }}', '{{ $state->code }}', {{ $state->shipping_fee }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <form action="{{ route('admin.shipping.states.toggle', $state) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        {{ $state->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.shipping.states.destroy', $state) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this state?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No states/provinces found for this country.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add State Modal -->
    <div id="addStateModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New State/Province</h3>
                <form action="{{ route('admin.shipping.states.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="shipping_country_id" value="{{ $country->id }}">
                    <div class="space-y-4">
                        <div>
                            <label for="state_name" class="block text-sm font-medium text-gray-700">State/Province Name</label>
                            <input type="text" name="name" id="state_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="state_code" class="block text-sm font-medium text-gray-700">State/Province Code (Optional)</label>
                            <input type="text" name="code" id="state_code" maxlength="10"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="state_shipping_fee" class="block text-sm font-medium text-gray-700">Shipping Fee ({{$country->code == 'NG'? '₦':'$';}})</label>
                            <input type="number" name="shipping_fee" id="state_shipping_fee" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addStateModal').classList.add('hidden')"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Add State
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit State Modal -->
    <div id="editStateModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit State/Province</h3>
                <form id="editStateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_state_name" class="block text-sm font-medium text-gray-700">State/Province Name</label>
                            <input type="text" name="name" id="edit_state_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="edit_state_code" class="block text-sm font-medium text-gray-700">State/Province Code (Optional)</label>
                            <input type="text" name="code" id="edit_state_code" maxlength="10"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="edit_state_shipping_fee" class="block text-sm font-medium text-gray-700">Shipping Fee ({{$country->code == 'NG'? '₦':'$';}})</label>
                            <input type="number" name="shipping_fee" id="edit_state_shipping_fee" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <input type="hidden" name="shipping_country_id" value="{{ $country->id }}">
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editStateModal').classList.add('hidden')"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update State
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editState(id, name, code, shippingFee) {
            const form = document.getElementById('editStateForm');
            form.action = `/admin/shipping/states/${id}`;
            document.getElementById('edit_state_name').value = name;
            document.getElementById('edit_state_code').value = code || '';
            document.getElementById('edit_state_shipping_fee').value = shippingFee;
            document.getElementById('editStateModal').classList.remove('hidden');
        }
    </script>
</x-admin-layout> 