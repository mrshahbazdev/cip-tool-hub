<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tools.show', $package->tool) }}" class="group flex items-center justify-center w-10 h-10 bg-white border border-blue-100 rounded-xl shadow-sm hover:bg-blue-600 hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight leading-none">
                        Secure Checkout
                    </h2>
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1">Licensing Tier: {{ $package->name }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Upgrade Alert -->
            @if($hasActiveSubscription ?? false)
                <div class="bg-indigo-50 border-2 border-indigo-100 rounded-[1.5rem] p-6 mb-10 shadow-sm flex items-start">
                    <div class="shrink-0 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-indigo-900 uppercase tracking-widest text-xs mb-1">Upgrade Detection</h4>
                        <p class="text-indigo-700 font-medium text-sm leading-relaxed">
                            You already have an active license for <strong>{{ $package->tool->name }}</strong>. Completing this transaction will automatically upgrade your current instance to the <strong>{{ $package->name }}</strong> tier.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Order Summary (Left) -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-white p-8">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center">
                            <span class="w-6 h-1 bg-blue-600 mr-3 rounded-full"></span>
                            Order Summary
                        </h3>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center pb-4 border-b border-slate-50">
                                <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Tool</span>
                                <span class="font-black text-gray-900">{{ $package->tool->name }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-slate-50">
                                <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Plan</span>
                                <span class="font-black text-blue-600">{{ $package->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Billing</span>
                                <span class="font-black text-gray-900">{{ $package->duration_type === 'lifetime' ? 'Lifetime' : $package->duration_value . ' ' . ucfirst($package->duration_type) }}</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Due</p>
                            <h2 class="text-4xl font-black text-gray-900 tracking-tighter">€{{ number_format($package->price, 2) }}</h2>
                        </div>
                    </div>

                    <!-- Trust Badge -->
                    <div class="bg-gray-900 rounded-[2rem] p-8 text-white text-center shadow-2xl">
                        <svg class="w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <h4 class="font-black text-sm uppercase tracking-[0.2em] mb-2">Secure Gateway</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium">All transactions are processed through encrypted channels. We do not store financial data.</p>
                    </div>
                </div>

                <!-- Form Section (Right) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-white p-10 md:p-12">
                        <form method="POST" action="{{ route('user.subscriptions.subscribe', $package) }}" id="subscriptionForm" class="space-y-10">
                            @csrf

                            <!-- Subdomain Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest flex items-center">
                                    <span class="w-6 h-1 bg-indigo-600 mr-3 rounded-full"></span>
                                    Provisioning Data
                                </h3>

                                <div>
                                    <label for="subdomain" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">
                                        Instance Subdomain <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative flex items-center">
                                        <input 
                                            type="text" 
                                            name="subdomain" 
                                            id="subdomain"
                                            required
                                            value="{{ old('subdomain', $suggestedSubdomain ?? '') }}"
                                            {{ ($isUpgrade ?? false) ? 'readonly' : '' }}
                                            class="w-full pl-6 pr-40 py-5 bg-slate-50 border-2 border-slate-100 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-blue-500/5 focus:border-blue-600 text-gray-900 font-bold shadow-inner {{ ($isUpgrade ?? false) ? 'opacity-70 cursor-not-allowed' : '' }}"
                                            placeholder="my-instance"
                                        >
                                        <div class="absolute right-4 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-400 font-black text-xs uppercase tracking-widest">
                                            .{{ $package->tool->domain }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 min-h-[24px]" id="availabilityStatus"></div>

                                    @if($isUpgrade ?? false)
                                        <p class="mt-4 p-3 bg-blue-50 rounded-xl text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                            Locked: Upgrading current instance
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Payment Method Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest flex items-center">
                                    <span class="w-6 h-1 bg-green-600 mr-3 rounded-full"></span>
                                    Payment Logic
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($package->price > 0)
                                        <label class="relative flex flex-col p-6 border-2 border-slate-100 rounded-[1.5rem] cursor-pointer hover:border-blue-600 transition-all group">
                                            <input type="radio" name="payment_method" value="stripe" class="absolute top-4 right-4 text-blue-600 focus:ring-blue-500" checked>
                                            <svg class="h-8 w-auto mb-4" viewBox="0 0 60 25" fill="#635BFF"><path d="M59.64 14.28h-8.06c.19 1.93 1.6 2.55 3.2 2.55 1.64 0 2.96-.37 4.05-.95v3.32a8.33 8.33 0 0 1-4.56 1.1c-4.01 0-6.83-2.5-6.83-7.48 0-4.19 2.39-7.52 6.3-7.52 3.92 0 5.96 3.28 5.96 7.5 0 .4-.04 1.26-.06 1.48zm-5.92-5.62c-1.03 0-2.17.73-2.17 2.58h4.25c0-1.85-1.07-2.58-2.08-2.58zM40.95 20.3c-1.44 0-2.32-.6-2.9-1.04l-.02 4.63-4.12.87V5.57h3.76l.08 1.02a4.7 4.7 0 0 1 3.23-1.29c2.9 0 5.62 2.6 5.62 7.4 0 5.23-2.7 7.6-5.65 7.6zM40 8.95c-.95 0-1.54.34-1.97.81l.02 6.12c.4.44.98.78 1.95.78 1.52 0 2.54-1.65 2.54-3.87 0-2.15-1.04-3.84-2.54-3.84zM28.24 5.57h4.13v14.44h-4.13V5.57zm0-4.7L32.37 0v3.36l-4.13.88V.88zm-4.32 9.35v9.79H19.8V5.57h3.7l.12 1.22c1-1.77 3.07-1.41 3.62-1.22v3.79c-.52-.17-2.29-.43-3.32.86zm-8.55 4.72c0 2.43 2.6 1.68 3.12 1.46v3.36c-.55.3-1.54.54-2.89.54a4.15 4.15 0 0 1-4.27-4.24l.01-13.17 4.02-.86v3.54h3.14V9.1h-3.13v5.85zm-4.91.7c0 2.97-2.31 4.66-5.73 4.66a11.2 11.2 0 0 1-4.46-.93v-3.93c1.38.75 3.1 1.31 4.46 1.31.92 0 1.53-.24 1.53-1C6.26 13.77 0 14.51 0 9.95 0 7.04 2.28 5.3 5.62 5.3c1.36 0 2.72.2 4.09.75v3.88a9.23 9.23 0 0 0-4.1-1.06c-.86 0-1.44.25-1.44.93 0 1.85 6.29.97 6.29 5.88z"/></svg>
                                            <span class="font-black text-gray-900 uppercase tracking-widest text-xs">Credit/Debit Card</span>
                                        </label>
                                        
                                        <label class="relative flex flex-col p-6 border-2 border-slate-100 rounded-[1.5rem] cursor-pointer hover:border-blue-600 transition-all group">
                                            <input type="radio" name="payment_method" value="paypal" class="absolute top-4 right-4 text-blue-600 focus:ring-blue-500">
                                            <svg class="h-8 w-auto mb-4" viewBox="0 0 124 33" fill="#003087"><path d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z"/><path fill="#009cde" d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z"/><path fill="#003087" d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2c-.696.494-1.523.869-2.458 1.109-.906.236-1.939.355-3.072.355h-.73c-.522 0-1.029.188-1.427.525a2.21 2.21 0 0 0-.744 1.328l-.055.299-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z"/><path fill="#009cde" d="M23.048 7.667c-.028.179-.06.362-.096.55-1.237 6.351-5.469 8.545-10.874 8.545H9.326c-.661 0-1.218.48-1.321 1.132L6.596 26.83l-.399 2.533a.704.704 0 0 0 .695.814h4.881c.578 0 1.069-.42 1.16-.99l.048-.248.919-5.832.059-.32c.09-.572.582-.992 1.16-.992h.73c4.729 0 8.431-1.92 9.513-7.476.452-2.321.218-4.259-.978-5.622a4.667 4.667 0 0 0-1.336-1.03z"/><path fill="#012169" d="M21.754 7.151a9.757 9.757 0 0 0-1.203-.267 15.284 15.284 0 0 0-2.426-.177h-7.352a1.172 1.172 0 0 0-1.159.992L8.05 17.605l-.045.289a1.336 1.336 0 0 1 1.321-1.132h2.752c5.405 0 9.637-2.195 10.874-8.545.037-.188.068-.371.096-.55a6.594 6.594 0 0 0-1.017-.429 9.045 9.045 0 0 0-.277-.087z"/><path fill="#003087" d="M9.614 7.699a1.169 1.169 0 0 1 1.159-.991h7.352c.871 0 1.684.057 2.426.177a9.757 9.757 0 0 1 1.481.353c.365.121.704.264 1.017.429.368-2.347-.003-3.945-1.272-5.392C20.378.682 17.853 0 14.622 0h-9.38c-.66 0-1.223.48-1.325 1.133L.01 25.898a.806.806 0 0 0 .795.932h5.791l1.454-9.225 1.564-9.906z"/></svg>
                                            <span class="font-black text-gray-900 uppercase tracking-widest text-xs">PayPal Instance</span>
                                        </label>
                                    @else
                                        <div class="col-span-full bg-green-50 border-2 border-green-100 rounded-[1.5rem] p-8 text-center">
                                            <div class="w-12 h-12 bg-green-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/20">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                            <h4 class="font-black text-green-900 uppercase tracking-widest text-sm mb-1">Free Activation</h4>
                                            <p class="text-green-700 font-medium text-xs">This package is free. No billing information required.</p>
                                            <input type="hidden" name="payment_method" value="free">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Finalize Section -->
                            <div class="pt-10 border-t border-slate-100">
                                <label class="flex items-start cursor-pointer group mb-10">
                                    <input type="checkbox" required class="mt-1 w-5 h-5 text-blue-600 border-slate-300 rounded-lg focus:ring-blue-500 cursor-pointer">
                                    <span class="ml-4 text-xs font-bold text-slate-500 group-hover:text-gray-900 transition-colors">
                                        I certify that I have read and agree to the <a href="#" class="text-blue-600 underline">Terms of Logic</a> and the <a href="#" class="text-blue-600 underline">Privacy Protocol</a>.
                                    </span>
                                </label>

                                <button 
                                    type="submit"
                                    id="submitBtn"
                                    class="w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-blue-600 transition-all duration-300 shadow-xl hover:shadow-blue-500/30 flex items-center justify-center"
                                >
                                    <span id="submitText">
                                        @if($package->price > 0)
                                            Confirm & Proceed to Gateway
                                        @else
                                            Activate Infrastructure ✓
                                        @endif
                                    </span>
                                    <span id="submitLoader" class="hidden">
                                        <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
(function() {
    'use strict';
    const subdomainInput = document.getElementById('subdomain');
    const statusDiv = document.getElementById('availabilityStatus');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('subscriptionForm');
    
    let checking = false;
    let available = {{ ($hasActiveSubscription ?? false) ? 'true' : 'false' }};
    let debounceTimer = null;
    
    function showStatus(type, message) {
        let html = '';
        if (type === 'checking') {
            html = `<div class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center">
                <svg class="animate-spin h-3 w-3 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Syncing Availability...</div>`;
        } else if (type === 'success') {
            html = `<div class="text-[10px] font-black uppercase tracking-widest text-emerald-600 flex items-center">
                <svg class="h-3 w-3 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                ${message}</div>`;
        } else if (type === 'error') {
            html = `<div class="text-[10px] font-black uppercase tracking-widest text-red-600 flex items-center">
                <svg class="h-3 w-3 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                ${message}</div>`;
        }
        statusDiv.innerHTML = html;
        updateSubmitButton();
    }
    
    function updateSubmitButton() {
        if (checking || !available) {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-slate-300', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-gray-900', 'hover:bg-blue-600');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-slate-300', 'cursor-not-allowed');
            submitBtn.classList.add('bg-gray-900', 'hover:bg-blue-600');
        }
    }
    
    async function checkAvailability() {
        const subdomain = subdomainInput.value.trim().toLowerCase();
        if (subdomain.length < 3) {
            available = false;
            statusDiv.innerHTML = '';
            updateSubmitButton();
            return;
        }
        checking = true;
        showStatus('checking');
        try {
            const response = await fetch('/api/subdomain/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ subdomain: subdomain, tool_id: {{ $package->tool_id }} })
            });
            const data = await response.json();
            available = data.available;
            showStatus(data.available ? 'success' : 'error', data.message);
        } catch (error) {
            available = false;
            showStatus('error', 'Sync Failed');
        } finally {
            checking = false;
            updateSubmitButton();
        }
    }
    
    if (subdomainInput && !{{ ($isUpgrade ?? false) ? 'true' : 'false' }}) {
        subdomainInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(checkAvailability, 500);
        });
    }

    form.addEventListener('submit', () => {
        document.getElementById('submitText').classList.add('hidden');
        document.getElementById('submitLoader').classList.remove('hidden');
        submitBtn.disabled = true;
    });

    // Check availability on load if subdomain exists
    if (subdomainInput && subdomainInput.value.length >= 3 && !{{ ($isUpgrade ?? false) ? 'true' : 'false' }}) {
        checkAvailability();
    } else if ({{ ($isUpgrade ?? false) ? 'true' : 'false' }}) {
        available = true;
        updateSubmitButton();
    }
})();
</script>