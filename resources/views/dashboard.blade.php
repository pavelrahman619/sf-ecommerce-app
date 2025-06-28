<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-10">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">You're logged in! Ecommerce App</h2>
                    </div>

                    <div class="border-t pt-6 space-y-3">
                        <h3 class="text-lg font-medium text-gray-900">Shared Login (Task 1)</h3>
                        <p class="text-sm text-gray-600">Click the button below to seamlessly log into the Foodpanda App.
                        </p>
                        <button id="goToFoodpandaBtn"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition">
                            Go to Foodpanda App
                        </button>
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Navigate the tasks (Task 2)  </h3>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('products.index') }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold transition">Products</a>
                            <a href="/sales/create"
                                class="text-blue-600 hover:text-blue-800 font-semibold transition">Sales</a>
                            <a href="/report"
                                class="text-blue-600 hover:text-blue-800 font-semibold transition">Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const goToFoodpandaBtn = document.getElementById('goToFoodpandaBtn');
            if (goToFoodpandaBtn) {
                goToFoodpandaBtn.addEventListener('click', function() {
                    this.disabled = true;
                    this.innerHTML =
                        'Processing... Please wait...';

                    fetch("{{ url('/api/shared-login/generate-token') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errData => {
                                    throw new Error(errData.message || errData.error ||
                                        `HTTP error! Status: ${response.status}`);
                                }).catch(() => {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.auto_login_url) {
                                window.location.href = data.auto_login_url;
                            } else {
                                console.error('Auto-login URL not found in response:', data);
                                alert('Error: Could not retrieve the auto-login URL. ' + (data
                                    .message || 'Unknown error'));
                                this.disabled = false;
                                this.innerHTML = 'Go to Foodpanda App';
                            }
                        })
                        .catch(error => {
                            console.error('Error during shared login initiation:', error);
                            alert('An error occurred: ' + error.message);
                            this.disabled = false;
                            this.innerHTML = 'Go to Foodpanda App';
                        });
                });
            }
        });
    </script>
</x-app-layout>
