<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <span class="text-blue-600 font-black uppercase tracking-widest text-xs">Identity Profile</span><br>
        <span class="text-gray-900 font-extrabold text-2xl tracking-tight">{{ __('Profile Information') }}</span>
    </x-slot>

    <x-slot name="description">
        <p class="text-gray-500 font-medium leading-relaxed">
            {{ __('Update your account\'s identity details and primary communication email address.') }}
        </p>
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Profile Photo') }}" class="text-gray-700 font-bold mb-3" />

                <div class="flex items-center space-x-6">
                    <!-- Current Profile Photo -->
                    <div class="relative group" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-3xl size-24 object-cover border-4 border-white shadow-xl shadow-blue-500/10">
                        <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 rounded-3xl transition-opacity pointer-events-none"></div>
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="relative" x-show="photoPreview" style="display: none;">
                        <span class="block rounded-3xl size-24 bg-cover bg-no-repeat bg-center border-4 border-white shadow-xl shadow-blue-500/10"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <div class="flex flex-col space-y-2">
                        <button type="button" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all active:scale-95"
                                x-on:click.prevent="$refs.photo.click()">
                            <i class="fas fa-camera mr-2"></i> {{ __('Update Photo') }}
                        </button>

                        @if ($this->user->profile_photo_path)
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-100 rounded-xl font-bold text-xs text-red-600 uppercase tracking-widest hover:bg-red-100 transition-all active:scale-95" 
                                    wire:click="deleteProfilePhoto">
                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Remove') }}
                            </button>
                        @endif
                    </div>
                </div>

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Full Legal Name') }}" class="text-gray-700 font-bold mb-2" />
            <x-input id="name" 
                     type="text" 
                     class="mt-1 block w-full px-5 py-3.5 bg-gray-50/50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 font-medium transition duration-200" 
                     wire:model="state.name" 
                     required 
                     autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Primary Email Instance') }}" class="text-gray-700 font-bold mb-2" />
            <x-input id="email" 
                     type="email" 
                     class="mt-1 block w-full px-5 py-3.5 bg-gray-50/50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 text-gray-900 font-medium transition duration-200" 
                     wire:model="state.email" 
                     required 
                     autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                    <p class="text-sm text-orange-800 font-bold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ __('Email Verification Pending') }}
                    </p>
                    
                    <button type="button" 
                            class="mt-2 text-xs font-black text-blue-600 uppercase tracking-widest hover:text-indigo-700 underline underline-offset-4 decoration-2" 
                            wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification link') }}
                    </button>

                    @if ($this->verificationLinkSent)
                        <p class="mt-3 font-bold text-xs text-green-600 uppercase tracking-widest">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('A new verification link has been transmitted.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-4 text-green-600 font-black text-xs uppercase tracking-widest" on="saved">
            <i class="fas fa-check-circle mr-1"></i> {{ __('Saved Success') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" 
                  wire:target="photo" 
                  class="px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black rounded-2xl hover:shadow-xl hover:shadow-blue-500/30 transform hover:-translate-y-0.5 transition duration-300">
            {{ __('Update Identity') }}
        </x-button>
    </x-slot>
</x-form-section>