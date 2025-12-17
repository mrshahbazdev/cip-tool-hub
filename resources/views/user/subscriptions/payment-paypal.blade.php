<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            PayPal Payment
        </h2>
    </x-slot>
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="text-center mb-8">
                    <svg class="h-16 w-16 text-blue-600 mx-auto mb-4" viewBox="0 0 124 33" fill="currentColor">
                        <!-- PayPal Logo SVG -->
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Payment</h3>
                    <p class="text-gray-600">You will be redirected to PayPal to complete the payment</p>
                </div>

                <!-- Payment Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Tool:</span>
                        <span class="font-medium">{{ $subscription->package->tool->name }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Package:</span>
                        <span class="font-medium">{{ $subscription->package->name }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subdomain:</span>
                        <span class="font-medium">{{ $subscription->subdomain }}.{{ $subscription->package->tool->domain }}</span>
                    </div>
                    <div class="border-t pt-2 mt-2 flex justify-between">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-green-600">€{{ number_format($subscription->package->price, 2) }}</span>
                    </div>
                </div>

                <!-- PayPal Button Container -->
                <div id="paypal-button-container" class="mb-4"></div>

                <div class="text-center">
                    <a href="{{ route('user.subscriptions.checkout', $subscription->package) }}" class="text-blue-600 hover:underline">
                        ← Back to checkout
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '{{ $subscription->package->price }}'
                        },
                        description: '{{ $subscription->package->tool->name }} - {{ $subscription->package->name }}'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Send to server for verification
                    window.location.href = '{{ route("user.subscriptions.success", $subscription) }}?paypal_order_id=' + data.orderID;
                });
            },
            onError: function(err) {
                alert('Payment failed. Please try again.');
                console.error(err);
            }
        }).render('#paypal-button-container');
    </script>
    @endpush
</x-app-layout>
