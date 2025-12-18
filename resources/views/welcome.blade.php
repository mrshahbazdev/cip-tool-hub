<x-guest-layout>
    <div class="min-h-screen flex flex-col">
        <!-- Premium Navigation (Matching Layout) -->
        <nav class="bg-white/80 backdrop-blur-lg border-b border-blue-100/50 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center group">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 transform group-hover:scale-105 transition duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <span class="ml-4 text-2xl font-extrabold tracking-tight text-gray-900 hidden sm:block">
                                CIP <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Tools</span>
                            </span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-blue-600 transition-colors px-4 py-2">Login</a>
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-500/20 hover:-translate-y-0.5 transition-all text-sm">Join Free</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content (Reset Password Form) -->
        <main class="flex-1 flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Decorative Blobs (Background) -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-pulse pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-pulse pointer-events-none"></div>

            <div class="max-w-md w-full relative z-10">
                <!-- Header -->
                <div class="text-center mb-10">
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
                    </form>
                </div>
            </div>
        </main>

        <!-- Premium Footer (Matching Layout) -->
        <footer class="bg-gray-900 border-t border-gray-800 relative z-10 mt-auto">
            <div class="max-w-7xl mx-auto pt-12 pb-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">CIP Tools</span>
                    </div>
                    <div class="text-sm text-gray-500 font-medium">
                        &copy; 2025 CIP Tools. All rights reserved.
                    </div>
                </div>
            </div>
            <div class="h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
        </footer>
    </div>
</x-guest-layout>