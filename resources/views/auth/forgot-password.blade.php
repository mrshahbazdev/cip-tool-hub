<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white">
                    Forgot Password?
                </h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400 text-lg">
                    No worries, we'll send you reset instructions 📧
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 space-y-6 border border-gray-200 dark:border-gray-700">
                
                <!-- Info Message -->
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border-2 border-indigo-100 dark:border-indigo-800">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-indigo-800 dark:text-indigo-300">
                            Enter your email address and we'll send you a password reset link that will allow you to choose a new one.
                        </p>
                    </div>
                </div>

                <!-- Success Message -->
                @session('status')
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 border-2 border-green-200 dark:border-green-800 rounded-xl">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400 flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $value }}
                        </p>
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            Email Address
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Enter your registered email"
                               class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400 text-gray-900 dark:text-white placeholder-gray-400 transition duration-200 outline-none">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">We'll never share your email with anyone else.</p>
                    </div>

                    <!-- Send Reset Link Button -->
                    <button type="submit" class="w-full py-4 px-6 text-white font-bold text-lg rounded-xl bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center group mt-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                        </svg>
                        <span>Email Password Reset Link</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-medium">
                                Remember your password?
                            </span>
                        </div>
                    </div>

                    <!-- Back to Login Link -->
                    <a href="{{ route('login') }}" class="w-full py-4 px-6 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold text-lg rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span>Back to Login</span>
                    </a>
                </form>
            </div>

            <!-- Footer Info -->
            <div class="mt-8 text-center">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Didn't receive the email? Check your spam folder
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
