<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Stripe Payment
        </h2>
    </x-slot>
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <svg class="h-16 w-16 text-blue-600 mx-auto mb-4" viewBox="0 0 60 25" fill="currentColor">
                        <path d="M59.64 14.28h-8.06c.19 1.93 1.6 2.55 3.2 2.55 1.64 0 2.96-.37 4.05-.95v3.32a8.33 8.33 0 0 1-4.56 1.1c-4.01 0-6.83-2.5-6.83-7.48 0-4.19 2.39-7.52 6.3-7.52 3.92 0 5.96 3.28 5.96 7.5 0 .4-.04 1.26-.06 1.48zm-5.92-5.62c-1.03 0-2.17.73-2.17 2.58h4.25c0-1.85-1.07-2.58-2.08-2.58zM40.95 20.3c-1.44 0-2.32-.6-2.9-1.04l-.02 4.63-4.12.87V5.57h3.76l.08 1.02a4.7 4.7 0 0 1 3.23-1.29c2.9 0 5.62 2.6 5.62 7.4 0 5.23-2.7 7.6-5.65 7.6zM40 8.95c-.95 0-1.54.34-1.97.81l.02 6.12c.4.44.98.78 1.95.78 1.52 0 2.54-1.65 2.54-3.87 0-2.15-1.04-3.84-2.54-3.84zM28.24 5.57h4.13v14.44h-4.13V5.57zm0-4.7L32.37 0v3.36l-4.13.88V.88zm-4.32 9.35v9.79H19.8V5.57h3.7l.12 1.22c1-1.77 3.07-1.41 3.62-1.22v3.79c-.52-.17-2.29-.43-3.32.86zm-8.55 4.72c0 2.43 2.6 1.68 3.12 1.46v3.36c-.55.3-1.54.54-2.89.54a4.15 4.15 0 0 1-4.27-4.24l.01-13.17 4.02-.86v3.54h3.14V9.1h-3.13v5.85zm-4.91.7c0 2.97-2.31 4.66-5.73 4.66a11.2 11.2 0 0 1-4.46-.93v-3.93c1.38.75 3.1 1.31 4.46 1.31.92 0 1.53-.24 1.53-1C6.26 13.77 0 14.51 0 9.95 0 7.04 2.28 5.3 5.62 5.3c1.36 0 2.72.2 4.09.75v3.88a9.23 9.23 0 0 0-4.1-1.06c-.86 0-1.44.25-1.44.93 0 1.85 6.29.97 6.29 5.88z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Complete Payment with Stripe</h3>
                    <p class="text-gray-600">Secure payment powered by Stripe</p>
                </div>

                <!-- Subscription Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h4 class="font-semibold text-gray-900 mb-4">Order Summary</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tool:</span>
                            <span class="font-medium">{{ $subscription->package->tool->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Package:</span>
                            <span class="font-medium">{{ $subscription->package->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subdomain:</span>
                            <span class="font-medium">{{ $subscription->full_domain }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">
                                @if($subscription->package->duration_type === 'lifetime')
                                    Lifetime Access
                                @else
                                    {{ $subscription->package->duration_value }} {{ ucfirst($subscription->package->duration_type) }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="border-t pt-3 flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                            <span class="text-3xl font-bold text-green-600">€{{ number_format($subscription->package->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="payment-form" class="mb-6">
                    @csrf
                    
                    <!-- Card Holder Name -->
                    <div class="mb-4">
                        <label for="card-holder-name" class="block text-sm font-medium text-gray-700 mb-2">
                            Card Holder Name
                        </label>
                        <input 
                            type="text" 
                            id="card-holder-name" 
                            value="{{ auth()->user()->name }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="John Doe"
                        >
                    </div>

                    <!-- Card Element -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Card Details
                        </label>
                        <div id="card-element" class="p-4 border border-gray-300 rounded-md bg-white min-h-[45px]">
                            <!-- Stripe card element will be inserted here -->
                        </div>
                        <div id="card-errors" class="mt-2 text-sm text-red-600"></div>
                    </div>

                    <!-- Test Cards Info -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm font-semibold text-yellow-800 mb-2">🧪 Test Mode - Use These Cards:</p>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• <strong>Success:</strong> 4242 4242 4242 4242</li>
                            <li>• <strong>Decline:</strong> 4000 0000 0000 0002</li>
                            <li>• Use any future expiry date (e.g., 12/34) and any 3-digit CVC</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submit-button"
                        class="w-full px-6 py-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-semibold text-lg shadow-lg transition-all"
                    >
                        <span id="button-text">Pay €{{ number_format($subscription->package->price, 2) }}</span>
                        <span id="spinner" class="hidden inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </form>

                <!-- Security Notice -->
                <div class="flex items-start text-sm text-gray-600 mb-6">
                    <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <p>
                        Your payment information is secure and encrypted. We never store your card details.
                        Payments are processed by Stripe.
                    </p>
                </div>

                <!-- Back Link -->
                <div class="text-center">
                    <a href="{{ route('user.subscriptions.checkout', $subscription->package) }}" class="text-blue-600 hover:underline">
                        ← Change payment method
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Stripe JS - Load at the end -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Stripe...');
    
    // Initialize Stripe with test key
    const stripe = Stripe('pk_test_51IqJGGSA6f0YZcZcLwqNxYC8R7Bk4HK3pY2Y8xC8vD7gK6yJ8hC5fB9nE4mG7pK3hY2vF8wZ5cB6nD4mH7pK3j');
    
    const elements = stripe.elements();
    
    // Custom styling
    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            fontSmoothing: 'antialiased',
            '::placeholder': {
                color: '#aab7c4'
            },
            padding: '12px'
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    
    // Create card element
    const cardElement = elements.create('card', {
        style: style,
        hidePostalCode: true
    });
    
    // Mount card element
    cardElement.mount('#card-element');
    console.log('Stripe card element mounted');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        console.log('Form submitted');
        
        // Disable button
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        // Get card holder name
        const cardHolderName = document.getElementById('card-holder-name').value;
        
        // Create payment method
        const {paymentMethod, error} = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: cardHolderName,
                email: '{{ auth()->user()->email }}'
            }
        });
        
        if (error) {
            console.error('Error:', error);
            // Show error
            document.getElementById('card-errors').textContent = error.message;
            
            // Re-enable button
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        } else {
            console.log('Payment Method created:', paymentMethod);
            
            // Success! Redirect to success page
            // In production, you would send paymentMethod.id to your server first
            window.location.href = '{{ route("user.subscriptions.success", $subscription) }}?payment_method_id=' + paymentMethod.id;
        }
    });
});
</script>
