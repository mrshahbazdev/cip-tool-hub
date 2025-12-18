<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Create Account</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Join us today and get started</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name Field -->
            <div class="transform transition-all duration-200 hover:scale-[1.01]">
                <x-label for="name" value="{{ __('Name') }}" class="text-gray-700 dark:text-gray-300 font-semibold" />
                <x-input id="name" 
                         class="block mt-2 w-full px-4 py-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm transition duration-200" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autofocus 
                         autocomplete="name"
                         placeholder="Enter your full name" />
            </div>

            <!-- Email Field -->
            <div class="transform transition-all duration-200 hover:scale-[1.01]">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 dark:text-gray-300 font-semibold" />
                <div class="relative mt-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <x-input id="email" 
                             class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm transition duration-200" 
                             type="email" 
                             name="email" 
                             :value="old('email')" 
                             required 
                             autocomplete="username"
                             placeholder="your@email.com" />
                </div>
            </div>

            <!-- Password Field -->
            <div class="transform transition-all duration-200 hover:scale-[1.01]">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 dark:text-gray-300 font-semibold" />
                <div class="relative mt-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <x-input id="password" 
                             class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm transition duration-200" 
                             type="password" 
                             name="password" 
                             required 
                             autocomplete="new-password"
                             placeholder="Min. 8 characters" />
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must be at least 8 characters long</p>
            </div>

            <!-- Confirm Password Field -->
            <div class="transform transition-all duration-200 hover:scale-[1.01]">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-700 dark:text-gray-300 font-semibold" />
                <div class="relative mt-2">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <x-input id="password_confirmation" 
                             class="block w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm transition duration-200" 
                             type="password" 
                             name="password_confirmation" 
                             required 
                             autocomplete="new-password"
                             placeholder="Confirm your password" />
                </div>
            </div>

            <!-- Terms and Privacy Policy -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <x-label for="terms">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <x-checkbox name="terms" 
                                          id="terms" 
                                          required 
                                          class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700" />
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="text-gray-700 dark:text-gray-300">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 underline transition duration-150">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 underline transition duration-150">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col space-y-4 mt-6">
                <x-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition duration-200 hover:scale-[1.02] shadow-lg">
                    {{ __('Register') }}
                </x-button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">
                            Already have an account?
                        </span>
                    </div>
                </div>

                <a class="w-full text-center py-3 px-4 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition duration-200 hover:scale-[1.02]" 
                   href="{{ route('login') }}">
                    {{ __('Sign In') }}
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
