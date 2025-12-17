<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bank Transfer Instructions
        </h2>
    </x-slot>
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <svg class="h-16 w-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Bank Transfer Payment</h3>
                    <p class="text-gray-600">Please transfer the amount to our bank account</p>
                </div>

                <!-- Subscription Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h4 class="font-semibold text-gray-900 mb-4">Order Details</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference Number:</span>
                            <span class="font-mono font-bold text-blue-600">{{ $subscription->transaction->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tool:</span>
                            <span class="font-medium">{{ $subscription->package->tool->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Package:</span>
                            <span class="font-medium">{{ $subscription->package->name }}</span>
                        </div>
                        
                        <div class="border-t pt-3 flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Amount to Transfer:</span>
                            <span class="text-3xl font-bold text-green-600">€{{ number_format($subscription->package->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="border-2 border-blue-200 rounded-lg p-6 mb-8 bg-blue-50">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Bank Account Details
                    </h4>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-600 block">Bank Name:</span>
                            <span class="font-semibold">Example Bank Europe</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 block">Account Name:</span>
                            <span class="font-semibold">Your Company Name</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 block">IBAN:</span>
                            <span class="font-mono font-semibold">DE89 3704 0044 0532 0130 00</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600 block">BIC/SWIFT:</span>
                            <span class="font-mono font-semibold">COBADEFFXXX</span>
                        </div>
                        <div class="bg-yellow-100 border border-yellow-300 rounded p-3 mt-4">
                            <span class="text-sm font-semibold text-yellow-800 block mb-1">⚠️ Important - Reference Number:</span>
                            <span class="font-mono font-bold text-lg text-yellow-900">{{ $subscription->transaction->transaction_id }}</span>
                            <p class="text-xs text-yellow-700 mt-2">Please include this reference number in your transfer to ensure quick processing</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                    <h4 class="font-semibold text-gray-900 mb-3">Payment Instructions:</h4>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Transfer exactly <strong>€{{ number_format($subscription->package->price, 2) }}</strong> to the above bank account</li>
                        <li>Use <strong class="font-mono">{{ $subscription->transaction->transaction_id }}</strong> as the payment reference</li>
                        <li>Processing time: 2-3 business days</li>
                        <li>You will receive email confirmation once payment is verified</li>
                        <li>Your subscription will be activated after payment confirmation</li>
                    </ol>
                </div>

                <!-- Alert -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Your subscription status: <strong>Pending Payment</strong>. We'll notify you via email once your payment is confirmed.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('user.subscriptions.index') }}" class="flex-1 text-center px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold">
                        Go to My Subscriptions
                    </a>
                    <a href="{{ route('user.subscriptions.checkout', $subscription->package) }}" class="flex-1 text-center px-6 py-3 border-2 border-blue-600 text-blue-600 rounded-md hover:bg-blue-50 font-semibold">
                        Change Payment Method
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
