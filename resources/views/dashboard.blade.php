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
                    {{ __("You're logged in! Ecommerce App") }}

                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Shared Login</h3>
                        <p class="mb-4 text-sm text-gray-600">Click the button below to seamlessly log into the Foodpanda
                            App.</p>
                        <button id="goToFoodpandaBtn"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Go to Foodpanda App
                        </button>
                        <button type="button" class="">Default</button>
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
