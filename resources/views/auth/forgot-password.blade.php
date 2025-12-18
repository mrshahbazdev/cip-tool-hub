<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full relative z-10">
            <!-- Logo & Header -->
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 transform hover:scale-105 transition duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Forgot Password?
                </h2>
                <p class="mt-4 text-gray-500 text-lg font-medium">
                    No worries, we'll send you reset instructions 📧
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-500/10 p-10 space-y-8 border border-white">
                
                <!-- Info Message -->
                <div class="bg-blue-50/50 p-5 rounded-2xl border-2 border-blue-50/50">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-800 font-medium leading-relaxed">
                            Enter your email address and we'll send you a password reset link that will allow you to choose a new one.
                        </p>
                    </div>
                </div>

                <!-- Success Message -->
                @session('status')
                    <div class="p-4 bg-green-50 border border-green-100 rounded-2xl">
                        <p class="text-sm font-bold text-green-600 flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $value }}
                        </p>
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                               placeholder="name@company.com"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                        <p class="text-[11px] text-gray-400 mt-2 ml-1 font-bold uppercase tracking-wider">We'll send a secure link to this address</p>
                    </div>

                    <!-- Send Reset Link Button -->
                    <button type="submit" class="w-full py-4.5 px-6 text-white font-extrabold text-lg rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-xl hover:shadow-blue-500/30 focus:outline-none focus:ring-4 focus:ring-blue-200 transform hover:-translate-y-0.5 transition duration-300 flex items-center justify-center group mt-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                        </svg>
                        <span>Send Reset Link</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-10">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-100"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-400 font-bold uppercase tracking-widest text-[10px]">
                                Remembered your password?
                            </span>
                        </div>
                    </div>

                    <!-- Back to Login Link -->
                    <a href="{{ route('login') }}" class="w-full py-4 px-6 border-2 border-blue-50 text-blue-600 font-extrabold text-lg rounded-2xl bg-blue-50/30 hover:bg-blue-50 hover:border-blue-100 transform hover:-translate-y-0.5 transition duration-300 flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span>Back to Login</span>
                    </a>
                </form>
            </div>

            <!-- Footer Message -->
            <p class="mt-8 text-center text-sm text-gray-400 font-medium flex items-center justify-center">
                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" clip-rule="evenodd" />
                </svg>
                Protected by industry-standard encryption
            </p>
        </div>
    </div>
</x-guest-layout>