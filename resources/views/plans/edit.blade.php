<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Plan') }}: {{ $plan->plan_name }}
            </h2>
            <a href="{{ route('plans.show', $plan) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Plan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('plans.update', $plan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="plan_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Plan Name *
                            </label>
                            <input type="text" 
                                   name="plan_name" 
                                   id="plan_name" 
                                   value="{{ old('plan_name', $plan->plan_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('plan_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Plan ID *
                            </label>
                            <input type="text" 
                                   name="plan_id" 
                                   id="plan_id" 
                                   value="{{ old('plan_id', $plan->plan_id) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('plan_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Price *
                                </label>
                                <input type="number" 
                                       name="price" 
                                       id="price" 
                                       value="{{ old('price', $plan->price) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sale Price (Optional)
                                </label>
                                <input type="number" 
                                       name="sale_price" 
                                       id="sale_price" 
                                       value="{{ old('sale_price', $plan->sale_price) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('sale_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-2">
                                Frequency *
                            </label>
                            <select name="frequency" 
                                    id="frequency" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Select Frequency</option>
                                <option value="monthly" {{ old('frequency', $plan->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('frequency', $plan->frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="quarterly" {{ old('frequency', $plan->frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="weekly" {{ old('frequency', $plan->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="one-time" {{ old('frequency', $plan->frequency) == 'one-time' ? 'selected' : '' }}>One Time</option>
                            </select>
                            @error('frequency')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description
                            </label>
                            <textarea name="short_description" 
                                      id="short_description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('short_description', $plan->short_description) }}</textarea>
                            @error('short_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('plans.show', $plan) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 