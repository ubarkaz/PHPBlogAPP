<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @can('view-dashboard')
                        <p class="mt-4 text-green-600">Welcome, Admin! You have full access.</p>
                    @else
                        <p class="mt-4 text-red-600">You do not have permission to access admin features.</p>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
