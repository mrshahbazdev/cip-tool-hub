<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIP-Tools - Professional SaaS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚀</text></svg>">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation { animation: float 3s ease-in-out infinite; }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ mobileMenuOpen: false, scrolled: false }" 
      @scroll.window="scrolled = window.pageYOffset > 50">

    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 transition-all duration-300"
         :class="scrolled ? 'bg-white shadow-lg' : 'bg-white shadow-md'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition">
                        <span class="text-white font-bold text-xl">CT</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">CIP-Tools</h1>
                        <p class="text-xs text-gray-600">Professional SaaS Platform</p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tools" class="text-gray-700 hover:text-blue-600 font-medium transition">Tools</a>
                    <a href="#features" class="text-gray-700 hover:text-blue-600 font-medium transition">Features</a>
                    <a href="#pricing" class="text-gray-700 hover:text-blue-600 font-medium transition">Pricing</a>
                    <a href="#blog" class="text-gray-700 hover:text-blue-600 font-medium transition">Blog</a>
                    
                    @auth
                        <a href="{{ route('tenants.index') }}" 
                           class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>My Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-blue-600 font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-2xl text-gray-700"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition
             class="md:hidden bg-white border-t shadow-lg"
             style="display: none;">
            <div class="px-4 py-4 space-y-3">
                <a href="#tools" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Tools</a>
                <a href="#features" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Features</a>
                <a href="#pricing" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Pricing</a>
                <a href="#blog" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Blog</a>
                @auth
                    <a href="{{ route('tenants.index') }}" class="block py-2 text-blue-600 font-semibold">My Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 overflow-hidden pt-20">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl float-animation"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-300 opacity-10 rounded-full blur-3xl float-animation" style="animation-delay: 1s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center text-white">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Your <span class="text-yellow-300">Professional</span><br/>
                SaaS Platform
            </h1>
            <p class="text-xl md:text-2xl mb-12 text-blue-100 max-w-3xl mx-auto">
                Choose from powerful tools and get your own subdomain instantly. 
                Professional, secure, and ready to scale.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                @guest
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition shadow-2xl">
                        <i class="fas fa-rocket mr-2"></i>Start Free
                    </a>
                @else
                    <a href="{{ route('tenants.create') }}" 
                       class="px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition shadow-2xl">
                        <i class="fas fa-plus mr-2"></i>Create Tenant
                    </a>
                @endguest
                <a href="#tools" 
                   class="px-8 py-4 border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transition">
                    <i class="fas fa-th mr-2"></i>Browse Tools
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto mt-20">
                <div class="text-center">
                    <p class="text-4xl font-bold text-yellow-300 mb-2">1000+</p>
                    <p class="text-blue-100">Active Tenants</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-yellow-300 mb-2">99.9%</p>
                    <p class="text-blue-100">Uptime</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-yellow-300 mb-2">24/7</p>
                    <p class="text-blue-100">Support</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-yellow-300 mb-2">Fast</p>
                    <p class="text-blue-100">Setup</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white text-3xl opacity-75"></i>
        </div>
    </div>

    <!-- Tools Section -->
    <div id="tools" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Available Tools</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Choose the perfect tool for your needs. Each comes with its own subdomain.
                </p>
            </div>

            @if($tools->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-4"></i>
                        <div>
                            <p class="text-yellow-900 font-semibold">No tools available yet</p>
                            <p class="text-yellow-700 text-sm mt-1">Please contact admin to add tools to the platform.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($tools as $tool)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden group">
                            <div class="p-8">
                                <div class="flex items-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition">
                                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr($tool->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $tool->name }}</h3>
                                        <span class="text-sm text-blue-600 font-mono">.{{ $tool->domain }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-6 min-h-[60px]">{{ $tool->description }}</p>
                                
                                @if($tool->packages->count() > 0)
                                    @php
                                        $cheapestPackage = $tool->packages->sortBy('price')->first();
                                    @endphp
                                    <div class="mb-6">
                                        <p class="text-sm text-gray-500 mb-1">Starting at</p>
                                        <p class="text-4xl font-bold text-blue-600">
                                            @if($cheapestPackage->price == 0)
                                                <span class="text-green-600">FREE</span>
                                            @else
                                                €{{ number_format($cheapestPackage->price, 2) }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">per month</p>
                                    </div>
                                @endif

                                <a href="{{ route('tools.show', $tool) }}" 
                                   class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition shadow-lg">
                                    View Packages <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>

                            <div class="bg-blue-600 px-8 py-4">
                                <div class="flex items-center justify-between text-white text-sm">
                                    <span><i class="fas fa-users mr-2"></i>{{ $tool->packages->sum('max_users') ?? '∞' }} users</span>
                                    <span><i class="fas fa-check-circle mr-2"></i>Instant setup</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose CIP-Tools?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Enterprise-grade features for businesses of all sizes
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Instant Deployment</h3>
                    <p class="text-gray-600">
                        Get your own subdomain and start using your chosen tool within minutes. No technical setup required.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Secure & Reliable</h3>
                    <p class="text-gray-600">
                        Enterprise-grade security with data encryption, regular backups, and 99.9% uptime guarantee.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Scalable</h3>
                    <p class="text-gray-600">
                        Start small and grow. Upgrade your plan anytime as your business needs evolve.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-cog text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Easy Management</h3>
                    <p class="text-gray-600">
                        Intuitive dashboard to manage your tenants, users, and settings. No coding required.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">24/7 Support</h3>
                    <p class="text-gray-600">
                        Our expert support team is always ready to help you succeed. Email, chat, and phone support.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-sync text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Regular Updates</h3>
                    <p class="text-gray-600">
                        Get the latest features and security updates automatically. Always stay up-to-date.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Choose a plan that fits your needs. All plans include core features.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Starter</h3>
                    <p class="text-4xl font-bold text-blue-600 mb-6">Free</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>1 Tool</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Up to 10 users</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Basic support</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Community access</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 bg-gray-200 text-gray-900 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Get Started
                    </a>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-2xl p-8 transform scale-105">
                    <div class="inline-block px-3 py-1 bg-yellow-400 text-gray-900 rounded-full text-sm font-bold mb-4">POPULAR</div>
                    <h3 class="text-2xl font-bold text-white mb-4">Professional</h3>
                    <p class="text-4xl font-bold text-white mb-6">€49<span class="text-lg">/mo</span></p>
                    <ul class="space-y-4 mb-8 text-white">
                        <li class="flex items-center"><i class="fas fa-check text-yellow-300 mr-3"></i>3 Tools</li>
                        <li class="flex items-center"><i class="fas fa-check text-yellow-300 mr-3"></i>Unlimited users</li>
                        <li class="flex items-center"><i class="fas fa-check text-yellow-300 mr-3"></i>Priority support</li>
                        <li class="flex items-center"><i class="fas fa-check text-yellow-300 mr-3"></i>Advanced features</li>
                        <li class="flex items-center"><i class="fas fa-check text-yellow-300 mr-3"></i>Custom branding</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Start Free Trial
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                    <p class="text-4xl font-bold text-blue-600 mb-6">Custom</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Unlimited tools</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Unlimited users</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Dedicated support</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>SLA guarantee</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>On-premise option</li>
                    </ul>
                    <a href="mailto:sales@cip-tools.com" class="block w-full text-center px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Contact Sales
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog -->
    <div id="blog" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest from Our Blog</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Tips, guides, and industry insights</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                        <i class="fas fa-rocket text-white text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-blue-600 uppercase">SaaS</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Getting Started with Multi-Tenancy</h3>
                        <p class="text-gray-600 mb-4">Learn the basics of multi-tenant architecture...</p>
                        <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">
                            Read More <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-purple-600 uppercase">Security</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Best Practices for SaaS Security</h3>
                        <p class="text-gray-600 mb-4">Essential security measures for your SaaS platform...</p>
                        <a href="#" class="text-purple-600 font-semibold hover:text-purple-800">
                            Read More <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-green-600 uppercase">Growth</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">Scaling Your SaaS Business</h3>
                        <p class="text-gray-600 mb-4">Strategies to grow your customer base effectively...</p>
                        <a href="#" class="text-green-600 font-semibold hover:text-green-800">
                            Read More <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl mb-8 text-blue-100">
                Join thousands of businesses using CIP-Tools
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" 
                   class="px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition shadow-2xl">
                    <i class="fas fa-rocket mr-2"></i>Start Free
                </a>
                <a href="mailto:hello@cip-tools.com" 
                   class="px-8 py-4 border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transition">
                    <i class="fas fa-envelope mr-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">CT</span>
                        </div>
                        <h3 class="text-xl font-bold text-white">CIP-Tools</h3>
                    </div>
                    <p class="text-sm text-gray-400">
                        Professional SaaS platform for modern businesses.
                    </p>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#tools" class="hover:text-blue-400 transition">Tools</a></li>
                        <li><a href="#features" class="hover:text-blue-400 transition">Features</a></li>
                        <li><a href="#pricing" class="hover:text-blue-400 transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">API</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition">About</a></li>
                        <li><a href="#blog" class="hover:text-blue-400 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Careers</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">GDPR</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-400 mb-4 md:mb-0">
                        © {{ date('Y') }} CIP-Tools. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
