<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full relative z-10">
            <!-- Logo & Header -->
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 transform hover:scale-105 transition duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Reset Password
                </h2>
                <p class="mt-4 text-gray-500 text-lg font-medium">
                    Secure your account with a new password 🔐
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-500/10 p-10 space-y-8 border border-white">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email Address
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="name@company.com"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            New Password
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                        <p class="text-[11px] text-gray-400 mt-2 ml-1 font-bold uppercase tracking-wider">Min. 8 characters with letters & numbers</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="group">
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirm New Password
                        </label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-blue-50/50 p-5 rounded-2xl border-2 border-blue-50/50">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800 font-bold">Password Security Tips</p>
                                <ul class="mt-2 text-[11px] text-blue-700 space-y-1 font-medium leading-relaxed">
                                    <li>• Mix uppercase, lowercase, and numbers</li>
                                    <li>• Include at least one special character</li>
                                    <li>• Avoid using your name or birthday</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button type="submit" class="w-full py-4.5 px-6 text-white font-extrabold text-lg rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-xl hover:shadow-blue-500/30 focus:outline-none focus:ring-4 focus:ring-blue-200 transform hover:-translate-y-0.5 transition duration-300 flex items-center justify-center group mt-4">
                        <span>Reset Password</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
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
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Secure enterprise-grade encryption enabled
            </p>
        </div>
    </div>
</x-guest-layout>