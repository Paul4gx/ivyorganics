<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shipping Countries') }}
            </h2>
            <div class="space-x-2">
                <button type="button" onclick="document.getElementById('addCountryModal').classList.remove('hidden')"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add Country
                </button>
            </div>
        </div>
    </x-slot>

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Countries Section -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Countries</h3>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default Fee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">States</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($countries as $country)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $country->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $country->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{$country->code == 'NG'? '₦':'$';}}{{ $country->default_shipping_fee }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $country->states_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $country->is_international ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $country->is_international ? 'International' : 'Domestic' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $country->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $country->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.shipping.countries.states.view', $country) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">View States</a>
                                <button onclick="editCountry({{ $country->id }}, '{{ $country->name }}', '{{ $country->code }}', {{ $country->default_shipping_fee }}, {{ $country->is_international ? 'true' : 'false' }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <form action="{{ route('admin.shipping.countries.toggle', $country) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        {{ $country->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.shipping.countries.destroy', $country) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this country?')" {{$country->code == 'NG'? 'disabled':'';}}>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Country Modal -->
    <div id="addCountryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 {{ $errors->any() ? '' : 'hidden' }}">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Country</h3>
                <form action="{{ route('admin.shipping.countries.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="country_name" class="block text-sm font-medium text-gray-700">Country Name</label>
                            <input type="text" name="name" id="country_name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country_code" class="block text-sm font-medium text-gray-700">Country Code (ISO 2-letter)</label>
                            <input type="text" name="code" id="country_code" maxlength="2" value="{{ old('code') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country_default_fee" class="block text-sm font-medium text-gray-700">Default Shipping Fee ({{ get_currency_symbol() }})</label>
                            <input type="number" name="default_shipping_fee" id="country_default_fee" step="0.01" value="{{ old('default_shipping_fee') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('default_shipping_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_international" id="country_international" value="1" {{ old('is_international') ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="country_international" class="ml-2 block text-sm text-gray-900">
                                International Shipping
                            </label>
                            @error('is_international')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addCountryModal').classList.add('hidden')"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Add Country
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Country Modal -->
    <div id="editCountryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Country</h3>
                <form id="editCountryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_country_name" class="block text-sm font-medium text-gray-700">Country Name</label>
                            <input type="text" name="name" id="edit_country_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit_country_code" class="block text-sm font-medium text-gray-700">Country Code (ISO 2-letter)</label>
                            <input type="text" name="code" id="edit_country_code" maxlength="2" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="edit_country_default_fee" class="block text-sm font-medium text-gray-700">Default Shipping Fee (<span id="active_modal_currency"></span>)</label>
                            <input type="number" name="default_shipping_fee" id="edit_country_default_fee" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('default_shipping_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_international" id="edit_country_international" value="1"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="edit_country_international" class="ml-2 block text-sm text-gray-900">
                                International Shipping
                            </label>
                            @error('is_international')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editCountryModal').classList.add('hidden')"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Update Country
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        function editCountry(id, name, code, defaultFee, isInternational) {
            const form = document.getElementById('editCountryForm');
            form.action = `/admin/shipping/countries/${id}`;
            document.getElementById('edit_country_name').value = name;
            document.getElementById('edit_country_code').value = code;
            document.getElementById('edit_country_default_fee').value = defaultFee;
            document.getElementById('edit_country_international').checked = isInternational;
            document.getElementById('editCountryModal').classList.remove('hidden');
            document.getElementById('active_modal_currency').innerHTML = code == 'NG'? '₦':'$';
        }
    </script>
</x-admin-layout> 