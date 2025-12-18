<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full relative z-10">
            <!-- Logo & Header -->
            <div class="text-center mb-10">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 transform hover:scale-105 transition duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Join the <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">CIP Tools Ecosystem</span>
                </h2>
                <p class="mt-4 text-gray-500 text-lg font-medium">
                    Start your professional journey today! 🚀
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-500/10 p-10 space-y-8 border border-white">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div class="group">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                    </div>

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
                               value="{{ old('email') }}"
                               required
                               placeholder="name@company.com"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Password
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
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
                            Confirm Password
                        </label>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required
                               placeholder="••••••••"
                               class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 placeholder-gray-400 transition duration-200 outline-none font-medium">
                    </div>

                    <!-- Terms & Conditions -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="bg-blue-50/50 p-5 rounded-2xl border-2 border-blue-50">
                            <label for="terms" class="flex items-start cursor-pointer group">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input id="terms" 
                                           name="terms" 
                                           type="checkbox" 
                                           required
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded-lg focus:ring-blue-500 cursor-pointer transition-colors">
                                </div>
                                <div class="ml-4 text-sm font-medium">
                                    <span class="text-gray-600">
                                        I agree to the 
                                        <a target="_blank" href="{{ route('terms.show') }}" class="font-bold text-blue-600 hover:text-indigo-700 underline decoration-2 underline-offset-4">Terms of Service</a>
                                        and 
                                        <a target="_blank" href="{{ route('policy.show') }}" class="font-bold text-blue-600 hover:text-indigo-700 underline decoration-2 underline-offset-4">Privacy Policy</a>
                                    </span>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <button type="submit" class="w-full py-4.5 px-6 text-white font-extrabold text-lg rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-xl hover:shadow-blue-500/30 focus:outline-none focus:ring-4 focus:ring-blue-200 transform hover:-translate-y-0.5 transition duration-300 flex items-center justify-center group mt-4">
                        <span>Create Account</span>
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
                                Already a member?
                            </span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <a href="{{ route('login') }}" class="w-full py-4 px-6 border-2 border-blue-50 text-blue-600 font-extrabold text-lg rounded-2xl bg-blue-50/30 hover:bg-blue-50 hover:border-blue-100 transform hover:-translate-y-0.5 transition duration-300 flex items-center justify-center group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        <span>Sign In Instead</span>
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