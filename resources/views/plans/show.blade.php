<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $plan->plan_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('plans.edit', $plan) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit Plan
                </a>
                <a href="{{ route('plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Plans
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Plan Header -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $plan->plan_name }}</h1>
                                <p class="text-gray-600">Plan ID: {{ $plan->plan_id }}</p>
                            </div>
                            @if($plan->is_on_sale)
                                <div class="text-right">
                                    <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                                        {{ $plan->discount_percentage }}% OFF
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pricing Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frequency:</span>
                                    <span class="font-medium">{{ ucfirst($plan->frequency) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Regular Price:</span>
                                    <span class="font-medium">{{ $plan->formatted_price }}</span>
                                </div>
                                @if($plan->is_on_sale)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sale Price:</span>
                                        <span class="font-medium text-green-600">{{ $plan->formatted_sale_price }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">You Save:</span>
                                        <span class="font-medium text-green-600">${{ number_format($plan->price - $plan->sale_price, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Created:</span>
                                    <span class="font-medium">{{ $plan->created_at ? $plan->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last Updated:</span>
                                    <span class="font-medium">{{ $plan->updated_at ? $plan->updated_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium">
                                        @if($plan->is_on_sale)
                                            <span class="text-green-600">On Sale</span>
                                        @else
                                            <span class="text-gray-600">Active</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    @if($plan->short_description || $plan->description)
                        <div class="border-t border-gray-200 pt-6">
                            @if($plan->short_description)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Short Description</h3>
                                    <p class="text-gray-700 leading-relaxed">{{ $plan->short_description }}</p>
                                </div>
                            @endif

                            @if($plan->description)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Full Description</h3>
                                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                                        {!! nl2br(e($plan->description)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 pt-6 mt-8">
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-4">
                                <a href="{{ route('plans.edit', $plan) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Plan
                                </a>
                                <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Are you sure you want to delete this plan? This action cannot be undone.')">
                                        Delete Plan
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('plans.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to All Plans
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 