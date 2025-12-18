<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-gray-900 dark:via-purple-900 dark:to-blue-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl transform hover:rotate-6 transition duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white">
                    Create Account
                </h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400 text-lg">
                    Start your journey with us today! 🚀
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 space-y-6 backdrop-blur-sm bg-opacity-95">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name Field -->
                    <div class="group">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Full Name
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               required 
                               autofocus
                               placeholder="John Doe"
                               class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 group-hover:border-indigo-300">
                    </div>

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email Address
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required
                               placeholder="john@example.com"
                               class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-500 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 group-hover:border-purple-300">
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
                               placeholder="••••••••"
                               class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-pink-300 dark:focus:ring-pink-600 focus:border-pink-500 dark:focus:border-pink-500 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 group-hover:border-pink-300">
                        <div class="flex gap-1 mt-2">
                            <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Min. 8 characters with letters & numbers</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="group">
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirm Password
                        </label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required
                               placeholder="••••••••"
                               class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-500 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 group-hover:border-blue-300">
                    </div>

                    <!-- Terms & Conditions -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-700 p-4 rounded-xl border-2 border-indigo-100 dark:border-gray-600">
                            <label for="terms" class="flex items-start cursor-pointer group">
                                <div class="flex items-center h-5 mt-1">
                                    <input id="terms" 
                                           name="terms" 
                                           type="checkbox" 
                                           required
                                           class="w-5 h-5 text-indigo-600 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded focus:ring-3 focus:ring-indigo-300 dark:focus:ring-indigo-600 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">
                                        I agree to the 
                                        <a target="_blank" href="{{ route('terms.show') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline">Terms of Service</a>
                                        and 
                                        <a target="_blank" href="{{ route('policy.show') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 underline">Privacy Policy</a>
                                    </span>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <button type="submit" class="w-full py-4 px-6 text-white font-bold text-lg rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 focus:outline-none focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 flex items-center justify-center group">
                        <span>Create Account</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t-2 border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-semibold">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <a href="{{ route('login') }}" class="w-full py-4 px-6 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold text-lg rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transform hover:-translate-y-1 transition duration-300 flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span>Sign In Instead</span>
                    </a>
                </form>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                Protected by industry-standard encryption 🔒
            </p>
        </div>
    </div>
</x-guest-layout>
