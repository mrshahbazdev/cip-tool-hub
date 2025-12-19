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
            
            <!-- Global Error Messages -->
            @if(session('error'))
                <div class="bg-red-50 border-2 border-red-100 rounded-[1.5rem] p-6 mb-8 shadow-sm flex items-start animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="shrink-0 w-10 h-10 bg-red-600 text-white rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-red-900 uppercase tracking-widest text-xs mb-1">Execution Error</h4>
                        <p class="text-red-700 font-medium text-sm leading-relaxed">
                            {{ session('error') }}
                        </p>
                        <p class="mt-2 text-[10px] text-red-400 font-bold uppercase tracking-tighter">The system prevented a duplicate entry conflict.</p>
                    </div>
                </div>
            @endif

            <!-- Upgrade Alert -->
            @if($hasActiveSubscription ?? false)
                <div class="bg-indigo-50 border-2 border-indigo-100 rounded-[1.5rem] p-6 mb-10 shadow-sm flex items-start">
                    <div class="shrink-0 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-black text-indigo-900 uppercase tracking-widest text-xs mb-1">Upgrade Detection</h4>
                        <p class="text-indigo-700 font-medium text-sm leading-relaxed">
                            You are upgrading your current instance of <strong>{{ $package->tool->name }}</strong>. Your data and subdomain <strong>({{ $suggestedSubdomain }})</strong> will be migrated to the new plan.
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

                    <div class="bg-gray-900 rounded-[2rem] p-8 text-white text-center shadow-2xl">
                        <svg class="w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <h4 class="font-black text-sm uppercase tracking-[0.2em] mb-2">Secure Gateway</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium">All transactions are processed through encrypted channels.</p>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-white p-10 md:p-12">
                        <form method="POST" action="{{ route('user.subscriptions.subscribe', $package) }}" id="subscriptionForm" class="space-y-10">
                            @csrf

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
                                        >
                                        <div class="absolute right-4 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-400 font-black text-xs uppercase tracking-widest">
                                            .{{ $package->tool->domain }}
                                        </div>
                                    </div>
                                    <div class="mt-4 min-h-[24px]" id="availabilityStatus"></div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest flex items-center">
                                    <span class="w-6 h-1 bg-green-600 mr-3 rounded-full"></span>
                                    Payment Logic
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($package->price > 0)
                                        <label class="relative flex flex-col p-6 border-2 border-slate-100 rounded-[1.5rem] cursor-pointer hover:border-blue-600 transition-all group">
                                            <input type="radio" name="payment_method" value="stripe" class="absolute top-4 right-4 text-blue-600" checked>
                                            <span class="font-black text-gray-900 uppercase tracking-widest text-xs">Stripe</span>
                                        </label>
                                        <label class="relative flex flex-col p-6 border-2 border-slate-100 rounded-[1.5rem] cursor-pointer hover:border-blue-600 transition-all group">
                                            <input type="radio" name="payment_method" value="paypal" class="absolute top-4 right-4 text-blue-600">
                                            <span class="font-black text-gray-900 uppercase tracking-widest text-xs">PayPal</span>
                                        </label>
                                    @else
                                        <div class="col-span-full bg-green-50 border-2 border-green-100 rounded-[1.5rem] p-8 text-center">
                                            <input type="hidden" name="payment_method" value="free">
                                            <div class="w-12 h-12 bg-green-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/20">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                            <h4 class="font-black text-green-900 uppercase tracking-widest text-sm mb-1">Free Activation</h4>
                                            <p class="text-green-700 font-medium text-xs">No payment required for this tier.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-10 border-t border-slate-100">
                                <button type="submit" id="submitBtn" class="w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-blue-600 transition-all duration-300 shadow-xl hover:shadow-blue-500/30 flex items-center justify-center">
                                    <span id="submitText">
                                        @if($isUpgrade ?? false)
                                            Confirm & Upgrade Plan
                                        @elseif($package->price > 0)
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
    const subdomainInput = document.getElementById('subdomain');
    const statusDiv = document.getElementById('availabilityStatus');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('subscriptionForm');
    let available = {{ ($isUpgrade ?? false) ? 'true' : 'false' }};

    function updateSubmitButton() {
        submitBtn.disabled = !available;
        submitBtn.classList.toggle('opacity-50', !available);
    }

    if (subdomainInput && !{{ ($isUpgrade ?? false) ? 'true' : 'false' }}) {
        subdomainInput.addEventListener('input', async () => {
            const val = subdomainInput.value.trim().toLowerCase();
            if (val.length < 3) { available = false; updateSubmitButton(); return; }
            
            statusDiv.innerHTML = '<span class="text-[10px] font-black uppercase tracking-widest text-slate-400 animate-pulse">Syncing Availability...</span>';
            
            const response = await fetch('/api/subdomain/check', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ subdomain: val, tool_id: {{ $package->tool_id }} })
            });
            const data = await response.json();
            available = data.available;
            statusDiv.innerHTML = `<span class="text-[10px] font-black uppercase tracking-widest ${available ? 'text-emerald-600' : 'text-red-600'}">${data.message}</span>`;
            updateSubmitButton();
        });
    }

    form.addEventListener('submit', () => {
        document.getElementById('submitText').classList.add('hidden');
        document.getElementById('submitLoader').classList.remove('hidden');
        submitBtn.disabled = true;
    });

    updateSubmitButton();
})();
</script>