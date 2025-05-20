<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkeTrack - Inventory & Transaction Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">
    <nav class="navbar-bg fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-boxes text-yellow-400 text-2xl mr-2"></i>
                        <span class="text-white font-bold text-xl">Oke<span class="text-yellow-400">Track</span></span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="cta-button bg-yellow-400 px-4 py-2 rounded-full text-sm font-medium text-gray-900 hover:bg-yellow-300">Login</a>
                </div>

                <div class="md:hidden flex items-center">
                    <button class="mobile-menu-button p-2 rounded-md text-gray-400 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mobile-menu hidden md:hidden bg-gray-900">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('filament.admin.auth.login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 bg-yellow-400 hover:bg-yellow-300 text-center">Login</a>
            </div>
        </div>
    </nav>

    <section class="pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-b from-gray-900 to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 items-center">
                <div class="mt-10 sm:mt-12 lg:mt-0">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight animate-fade-in">
                        Simplify Inventory & Transaction Management with <span class="text-yellow-400">OkeTrack</span>
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-gray-300 animate-fade-in animate-delay-1">
                        Real-time tracking, secure access, automated reports, and smart analytics - all in one powerful platform designed to streamline your business operations.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 animate-fade-in animate-delay-2">
                        <a href="{{ route('filament.admin.auth.login') }}" class="px-8 py-4 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold rounded-lg text-center transition duration-300 transform hover:scale-105">
                            Get Started
                        </a>
                        <a href="{{ route('filament.admin.auth.register') }}" class="px-8 py-4 border-2 border-yellow-400 text-yellow-400 hover:bg-yellow-400 hover:text-gray-900 font-bold rounded-lg text-center transition duration-300 transform hover:scale-105">
                            Register
                        </a>
                    </div>
                </div>
                <div class="relative mt-12 lg:mt-0 animate-fade-in animate-delay-3">
                    <div class="relative mx-auto w-full max-w-md px-4">
                        <div class="overflow-hidden rounded-2xl shadow-xl">
                            <div class="bg-gray-800 p-6">
                                <div class="dashboard-grid h-64">
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-barcode text-yellow-400 text-xl"></i>
                                    </div>
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-chart-line text-yellow-400 text-xl"></i>
                                    </div>
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-box-open text-yellow-400 text-xl"></i>
                                    </div>
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-exchange-alt text-yellow-400 text-xl spinning"></i>
                                    </div>
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-yellow-400 text-xl"></i>
                                    </div>
                                    <div class="grid-cell flex items-center justify-center">
                                        <i class="fas fa-file-invoice-dollar text-yellow-400 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-6 -right-6">
                            <div class="bg-yellow-400 rounded-full w-12 h-12 flex items-center justify-center floating">
                                <i class="fas fa-bolt text-gray-900"></i>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6">
                            <div class="bg-yellow-400 rounded-full w-12 h-12 flex items-center justify-center floating" style="animation-delay: 2s;">
                                <i class="fas fa-cog text-gray-900 spinning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.3s; }
        .animate-delay-2 { animation-delay: 0.6s; }
        .animate-delay-3 { animation-delay: 0.9s; }

        .navbar-bg {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            position: relative;
        }

        .navbar-bg::after {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            pointer-events: none;
        }

        .cta-button {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
        }

        .floating { animation: float 6s ease-in-out infinite; }
        .spinning { animation: spin 8s linear infinite; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 10px;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .grid-cell {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .grid-cell:hover {
            background: rgba(245, 158, 11, 0.1);
            transform: scale(1.05);
        }
    </style>
</body>
</html>