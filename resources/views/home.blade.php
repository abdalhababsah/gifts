<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden scroll-smooth group" data-mode="light" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>GiftShop - Perfect Gifts for Every Occasion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="GiftShop Mobile App - Find Perfect Gifts" name="description">
    <meta content="GiftShop" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="./assets/images/favicon.ico">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        custom: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        zink: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="text-base bg-white text-body font-sans dark:text-zink-50 dark:bg-zink-800">

    <nav class="fixed inset-x-0 top-0 z-50 flex items-center justify-center h-20 py-3 bg-white/80 backdrop-blur-lg dark:bg-zink-700/80 border-b border-slate-200 dark:border-zink-500 shadow-lg shadow-slate-200/25 dark:shadow-zink-500/30" id="navbar">
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto flex items-center self-center w-full">
            <div class="shrink-0">
                <a href="#!" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-custom-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800 dark:text-zink-50">GiftShop</span>
                </a>
            </div>
            <div class="mx-auto">
                <ul class="hidden md:flex items-center space-x-8">
                    <li>
                        <a href="#home" class="text-slate-800 hover:text-custom-500 dark:text-zink-100 dark:hover:text-custom-500 font-medium transition-all duration-300 ease-linear">Home</a>
                    </li>
                    <li>
                        <a href="#features" class="text-slate-800 hover:text-custom-500 dark:text-zink-100 dark:hover:text-custom-500 font-medium transition-all duration-300 ease-linear">Features</a>
                    </li>
                    <li>
                        <a href="#about" class="text-slate-800 hover:text-custom-500 dark:text-zink-100 dark:hover:text-custom-500 font-medium transition-all duration-300 ease-linear">About</a>
                    </li>
                    <li>
                        <a href="#download" class="text-slate-800 hover:text-custom-500 dark:text-zink-100 dark:hover:text-custom-500 font-medium transition-all duration-300 ease-linear">Download</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="relative pb-36 pt-24 lg:pt-44" id="home">
        <div class="absolute rotate-45 border border-dashed size-[500px] border-t-slate-300 dark:border-t-zink-500 border-l-slate-300 dark:border-l-zink-500 border-r-slate-700 dark:border-r-zink-400 border-b-slate-700 dark:border-b-zink-400 -bottom-[250px] rounded-full ltr:right-40 rtl:left-40 z-10 hidden lg:block"></div>
        <div class="absolute rotate-45 border border-dashed size-[700px] border-t-slate-300 dark:border-t-zink-500 border-l-slate-300 dark:border-l-zink-500 border-r-slate-700 dark:border-r-zink-400 border-b-slate-700 dark:border-b-zink-400 -bottom-[350px] rounded-full ltr:right-16 rtl:left-16 z-10 hidden 2xl:block"></div>
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto">
            <div class="grid grid-cols-12 items-center">
                <div class="col-span-12 lg:col-span-6">
                    <h1 class="mb-8 text-4xl md:text-5xl !leading-relaxed font-bold text-slate-800 dark:text-zink-50">
                        Find Perfect Gifts with 
                        <span class="relative inline-block px-2 mx-2 before:block before:absolute before:-inset-1 before:-skew-y-6 before:bg-sky-50 dark:before:bg-sky-500/20 before:rounded-md before:backdrop-blur-xl">
                            <span class="relative text-sky-500">GiftShop</span>
                        </span>
                        Mobile App
                    </h1>
                    <p class="mb-6 text-lg text-slate-500 dark:text-zink-200">
                        Discover thousands of unique gifts, personalized recommendations, and seamless shopping experience right at your fingertips. Make every occasion special with our curated collection.
                    </p>
                    <div class="flex items-center gap-4 flex-wrap">
                        <button type="button" class="py-3 px-6 text-white bg-custom-500 border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 rounded-lg transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span>Download App</span>
                        </button>
                        <button type="button" class="py-3 px-6 text-slate-600 bg-white border-slate-300 hover:bg-slate-50 hover:border-slate-400 focus:bg-slate-50 focus:border-slate-400 rounded-lg transition-all duration-300 flex items-center space-x-2 dark:bg-zink-700 dark:text-zink-200 dark:border-zink-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h6m2-7a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>View Demo</span>
                        </button>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-6 mt-12 lg:mt-0">
                    <div class="relative flex justify-center">
                        <!-- Phone Mockup -->
                        <div class="relative">
                            <div class="w-80 h-[600px] bg-slate-900 rounded-[3rem] p-4 shadow-2xl">
                                <div class="w-full h-full bg-white rounded-[2.5rem] overflow-hidden">
                                    <!-- Phone Screen Content -->
                                    <div class="h-full bg-gradient-to-br from-slate-50 to-slate-100 p-6 flex flex-col">
                                        <!-- Status Bar -->
                                        <div class="flex justify-between items-center mb-8 pt-4">
                                            <span class="text-sm font-medium">9:41</span>
                                            <div class="flex space-x-1">
                                                <div class="w-4 h-2 bg-slate-300 rounded-full"></div>
                                                <div class="w-4 h-2 bg-slate-300 rounded-full"></div>
                                                <div class="w-4 h-2 bg-custom-500 rounded-full"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- App Header -->
                                        <div class="text-center mb-8">
                                            <div class="w-16 h-16 bg-custom-500 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-800">GiftShop</h3>
                                            <p class="text-slate-500 text-sm">Perfect gifts await</p>
                                        </div>
                                        
                                        <!-- Feature Cards -->
                                        <div class="space-y-4 flex-1">
                                            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-slate-800">Gift Collections</h4>
                                                        <p class="text-slate-500 text-sm">Curated for you</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-slate-800">Easy Shopping</h4>
                                                        <p class="text-slate-500 text-sm">One-tap ordering</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5 7.707 6.621a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-slate-800">Fast Delivery</h4>
                                                        <p class="text-slate-500 text-sm">Same day shipping</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-32 bg-slate-900 dark:bg-zink-700" id="features">
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto">
            <div class="mx-auto text-center xl:max-w-3xl mb-16">
                <h1 class="mb-6 leading-normal capitalize text-slate-100 dark:text-zink-50">
                    Discover Amazing Features in Our 
                    <span class="relative inline-block px-2 mx-2 before:block before:absolute before:-inset-1 before:-skew-y-6 before:bg-sky-500/20 before:rounded-md before:backdrop-blur-xl">
                        <span class="relative text-sky-400">Mobile App</span>
                    </span>
                </h1>
                <p class="text-lg text-slate-400 dark:text-zink-200">
                    Experience the future of gift shopping with our intuitive mobile application designed for gift lovers.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-12 gap-x-5">
                <div class="xl:col-span-4">
                    <div class="transition-all duration-300 ease-linear -mt-16 bg-white dark:bg-zink-600 rounded-xl shadow-xl hover:-translate-y-2 p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-sky-100 to-sky-200 rounded-lg mb-6 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-16 h-16 text-sky-500 transition animate-pulse"></i>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-sky-100 border-sky-200 text-sky-500 dark:bg-sky-500/20 dark:border-sky-500/20 mb-3">Popular Feature</span>
                        <h6 class="text-lg font-semibold mb-2 text-slate-800 dark:text-zink-100">Smart Gift Finder</h6>
                        <p class="text-slate-500 dark:text-zink-200">AI-powered recommendations help you find the perfect gift based on recipient's interests and occasion.</p>
                    </div>
                </div>
                
                <div class="xl:col-span-4">
                    <div class="transition-all duration-300 ease-linear md:-mt-16 bg-white dark:bg-zink-600 rounded-xl shadow-xl hover:-translate-y-2 p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg mb-6 flex items-center justify-center">
                            <i data-lucide="gift" class="w-16 h-16 text-purple-500 transition animate-pulse"></i>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-purple-100 border-purple-200 text-purple-500 dark:bg-purple-500/20 dark:border-purple-500/20 mb-3">Gift Wrapping</span>
                        <h6 class="text-lg font-semibold mb-2 text-slate-800 dark:text-zink-100">Custom Gift Wrapping</h6>
                        <p class="text-slate-500 dark:text-zink-200">Beautiful gift wrapping options with personalized messages to make your gifts extra special.</p>
                    </div>
                </div>
                
                <div class="xl:col-span-4">
                    <div class="transition-all duration-300 ease-linear xl:-mt-16 bg-white dark:bg-zink-600 rounded-xl shadow-xl hover:-translate-y-2 p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-green-200 rounded-lg mb-6 flex items-center justify-center">
                            <i data-lucide="clock" class="w-16 h-16 text-green-500 transition animate-pulse"></i>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs inline-block font-medium rounded border bg-green-100 border-green-200 text-green-500 dark:bg-green-500/20 dark:border-green-500/20 mb-3">Fast Delivery</span>
                        <h6 class="text-lg font-semibold mb-2 text-slate-800 dark:text-zink-100">Express Delivery</h6>
                        <p class="text-slate-500 dark:text-zink-200">Same-day and next-day delivery options available for last-minute gifting needs.</p>
                    </div>
                </div>
            </div>

            <div class="p-10 mt-20 rounded-xl bg-slate-800 dark:bg-zink-600">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div class="text-center">
                        <h3 class="mb-2 text-3xl font-bold text-slate-50 dark:text-zink-50">10K+</h3>
                        <p class="text-slate-400 dark:text-zink-200">Unique Gifts</p>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-2 text-3xl font-bold text-slate-50 dark:text-zink-50">50K+</h3>
                        <p class="text-slate-400 dark:text-zink-200">Happy Customers</p>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-2 text-3xl font-bold text-slate-50 dark:text-zink-50">24/7</h3>
                        <p class="text-slate-400 dark:text-zink-200">Customer Support</p>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-2 text-3xl font-bold text-slate-50 dark:text-zink-50">99%</h3>
                        <p class="text-slate-400 dark:text-zink-200">Satisfaction Rate</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute rotate-45 border border-dashed size-[500px] border-t-slate-700 border-l-slate-700 border-r-slate-700 border-b-slate-700 bottom-48 rounded-full ltr:-left-80 rtl:-right-80 hidden md:block"></div>
        <div class="absolute rotate-45 border border-dashed size-[700px] border-t-slate-700 border-l-slate-700 border-r-slate-700 border-b-slate-700 bottom-24 rounded-full ltr:-left-96 rtl:-right-96 hidden md:block"></div>
    </section>

    <section class="relative py-32" id="about">
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto">
            <div class="mx-auto text-center xl:max-w-3xl">
                <h1 class="mb-6 leading-normal capitalize text-slate-800 dark:text-zink-50">
                    Why Choose 
                    <span class="relative inline-block px-2 mx-2 before:block before:absolute before:-inset-1 before:-skew-y-6 before:bg-sky-50 dark:before:bg-sky-500/20 before:rounded-md before:backdrop-blur-xl">
                        <span class="relative text-sky-500">GiftShop</span>
                    </span>
                    Mobile App
                </h1>
                <p class="text-lg text-slate-500 dark:text-zink-200">
                    Experience the most intuitive and delightful way to find and send perfect gifts to your loved ones.
                </p>
            </div>

            <div class="grid items-center grid-cols-1 gap-12 mt-20 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <h2 class="mb-4 text-3xl leading-normal capitalize font-bold text-slate-800 dark:text-zink-50">
                        Personalized Shopping Experience
                    </h2>
                    <p class="mb-6 text-lg text-slate-500 dark:text-zink-200">
                        Our intelligent recommendation system learns your preferences and suggests the perfect gifts for every person and occasion in your life.
                    </p>
                    <button type="button" class="py-2.5 px-6 bg-custom-500 text-white border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <span class="align-middle">Explore Features</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
                <div class="lg:col-span-6">
                    <div class="relative">
                        <div class="w-full h-80 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-custom-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i data-lucide="check" class="h-12 w-12 text-white transition animate-pulse"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-slate-700">Smart Recommendations</h3>
                                <p class="text-slate-500 mt-2">AI-powered gift suggestions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid items-center grid-cols-1 gap-12 mt-20 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <div class="relative">
                        <div class="w-full h-80 bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-purple-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i data-lucide="gift" class="w-12 h-12 text-white transition animate-pulse"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-slate-700">Gift Collections</h3>
                                <p class="text-slate-500 mt-2">Curated gift categories</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <h2 class="mb-4 text-3xl leading-normal capitalize font-bold text-slate-800 dark:text-zink-50">
                        Curated Gift Collections
                    </h2>
                    <p class="mb-6 text-lg text-slate-500 dark:text-zink-200">
                        Browse through our expertly curated collections for birthdays, anniversaries, holidays, and special occasions. Every gift is handpicked for quality and uniqueness.
                    </p>
                    <button type="button" class="py-2.5 px-6 bg-custom-500 text-white border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <span class="align-middle">Browse Collections</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid items-center grid-cols-1 gap-12 mt-20 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <h2 class="mb-4 text-3xl leading-normal capitalize font-bold text-slate-800 dark:text-zink-50">
                        Seamless Mobile Experience
                    </h2>
                    <p class="mb-4 text-lg text-slate-500 dark:text-zink-200">
                        Our mobile app provides an effortless shopping experience with intuitive navigation and beautiful design that makes gift shopping a joy.
                    </p>
                    <ul class="flex flex-col gap-3 mb-6 text-lg">
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span class="text-slate-700 dark:text-zink-200">Cross-platform compatibility</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span class="text-slate-700 dark:text-zink-200">Offline browsing capability</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span class="text-slate-700 dark:text-zink-200">Secure payment processing</span>
                        </li>
                    </ul>
                    <button type="button" class="py-2.5 px-6 bg-custom-500 text-white border-custom-500 hover:bg-custom-600 hover:border-custom-600 focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-600 active:border-custom-600 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <span class="align-middle">Learn More</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
                <div class="lg:col-span-6">
                    <div class="relative">
                        <div class="w-full h-80 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-red-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i data-lucide="layout-template" class="h-12 w-12 text-white transition animate-pulse"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-slate-700">Mobile Optimized</h3>
                                <p class="text-slate-500 mt-2">Perfect for any device</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-20 bg-custom-600 dark:bg-custom-800" id="download">
        <div class="absolute rotate-45 border border-dashed size-[500px] border-t-custom-500 border-l-custom-500 border-r-slate-700 border-b-slate-700 -bottom-[250px] rounded-full ltr:right-40 rtl:left-40 z-10 hidden lg:block"></div>
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto">
            <div class="grid items-center grid-cols-1 gap-8 lg:grid-cols-12">
                <div class="lg:col-span-8">
                    <h1 class="mb-4 text-4xl leading-normal capitalize text-custom-50 font-bold">
                        Ready to Start Your Gift Shopping Journey?
                    </h1>
                    <p class="text-lg text-custom-200">
                        Download our mobile app today and discover a world of perfect gifts waiting for you.
                    </p>
                </div>
                <div class="ltr:lg:text-right rtl:lg:text-left lg:col-span-4">
                    <div class="flex flex-col sm:flex-row lg:flex-col gap-4">
                        <button type="button" class="py-3 px-6 text-base transition-all duration-200 ease-linear bg-white text-custom-500 border-white hover:bg-custom-50 hover:border-custom-50 rounded-lg flex items-center justify-center space-x-2 font-medium">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            <span>Download for iOS</span>
                        </button>
                        <button type="button" class="py-3 px-6 text-base transition-all duration-200 ease-linear bg-white text-custom-500 border-white hover:bg-custom-50 hover:border-custom-50 rounded-lg flex items-center justify-center space-x-2 font-medium">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                            </svg>
                            <span>Download for Android</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="relative pt-20 pb-12 bg-slate-800 dark:bg-zink-700">
        <div class="container 2xl:max-w-[87.5rem] px-4 mx-auto">
            <div class="relative z-10 grid grid-cols-12 gap-5 xl:grid-cols-12">
                <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-custom-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                            </svg>
                        </div>
                        <h5 class="text-xl font-bold text-slate-50 dark:text-zink-50">GiftShop</h5>
                    </div>
                    <p class="text-slate-400 dark:text-zink-200 mb-6">
                        Making every moment special with perfect gifts for every occasion.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#!" class="flex items-center justify-center w-10 h-10 transition-all duration-200 ease-linear border rounded-full text-slate-400 border-slate-700 hover:text-custom-500 hover:border-custom-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#!" class="flex items-center justify-center w-10 h-10 transition-all duration-200 ease-linear border rounded-full text-slate-400 border-slate-700 hover:text-custom-500 hover:border-custom-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#!" class="flex items-center justify-center w-10 h-10 transition-all duration-200 ease-linear border rounded-full text-slate-400 border-slate-700 hover:text-custom-500 hover:border-custom-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.719-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                        <a href="#!" class="flex items-center justify-center w-10 h-10 transition-all duration-200 ease-linear border rounded-full text-slate-400 border-slate-700 hover:text-custom-500 hover:border-custom-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                    <h5 class="mb-4 font-medium tracking-wider text-slate-50 dark:text-zink-50">Quick Links</h5>
                    <ul class="flex flex-col gap-3 text-15">
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Gift Categories</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Occasions</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Gift Cards</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Custom Gifts</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Gift Wrapping</a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                    <h5 class="mb-4 font-medium tracking-wider text-slate-50 dark:text-zink-50">Customer Care</h5>
                    <ul class="flex flex-col gap-3 text-15">
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Help Center</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Shipping Info</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Returns</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Contact Us</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Track Order</a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-2">
                    <h5 class="mb-4 font-medium tracking-wider text-slate-50 dark:text-zink-50">Company</h5>
                    <ul class="flex flex-col gap-3 text-15">
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">About Us</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Careers</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Press</a>
                        </li>
                        <li>
                            <a href="#!" class="relative inline-block transition-all duration-200 ease-linear text-slate-400 dark:text-zink-200 hover:text-slate-300 dark:hover:text-zink-50 before:absolute before:border-b before:border-slate-500 dark:before:border-zink-500 before:inset-x-0 before:bottom-0 before:w-0 hover:before:w-full before:transition-all before:duration-300 before:ease-linear">Privacy Policy</a>
                        </li>
                    </ul>
                </div>
                
                <div class="col-span-12 md:col-span-6 lg:col-span-12 xl:col-span-4">
                    <h5 class="mb-4 font-medium tracking-wider text-slate-50 dark:text-zink-50">Stay Updated</h5>
                    <form action="#!" class="relative mb-6">
                        <input type="email" class="py-3 ltr:pr-40 rtl:pl-40 bg-slate-700/60 dark:bg-zink-600/40 w-full rounded-lg text-slate-200 border-slate-700 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:focus:border-custom-500 placeholder:text-slate-500 dark:placeholder:text-zink-200 backdrop-blur-md" autocomplete="off" placeholder="your@email.com" required>
                       <button type="submit" class="absolute px-6 py-2 text-base transition-all duration-200 ease-linear border-custom-500 bg-custom-500 ltr:right-1 rtl:left-1 text-custom-50 hover:text-custom-50 hover:bg-custom-600 hover:border-custom-600 top-1 bottom-1 rounded-md">Subscribe</button>
                   </form>

                   <p class="mb-1 text-slate-500 dark:text-zink-200 text-15">Support Email</p>
                   <h5 class="text-lg !font-normal text-slate-200 dark:text-zink-50 mb-4">support@giftshop.com</h5>

                   <p class="mb-1 text-slate-500 dark:text-zink-200 text-15">Contact Us</p>
                   <h5 class="text-lg !font-normal text-slate-200 dark:text-zink-50">+1 (555) 123-4567</h5>
               </div>
           </div>

           <div class="mt-12 text-center text-slate-400 dark:text-zink-200 text-16">
               <p>
                   <script>document.write(new Date().getFullYear())</script> © GiftShop. Made with ❤️ for gift lovers everywhere.
               </p>
           </div>
       </div>
   </footer>

   <button id="back-to-top" class="fixed flex items-center justify-center w-10 h-10 text-white bg-custom-500 rounded-md bottom-10 right-10 opacity-0 pointer-events-none transition-all duration-300 hover:bg-custom-600">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
       </svg>
   </button>

   <script>
       // Initialize Lucide icons
       lucide.createIcons();
       
       // Back to top functionality
       const backToTop = document.getElementById('back-to-top');
       
       window.addEventListener('scroll', () => {
           if (window.pageYOffset > 300) {
               backToTop.classList.add('opacity-100', 'pointer-events-auto');
               backToTop.classList.remove('opacity-0', 'pointer-events-none');
           } else {
               backToTop.classList.add('opacity-0', 'pointer-events-none');
               backToTop.classList.remove('opacity-100', 'pointer-events-auto');
           }
       });
       
       backToTop.addEventListener('click', () => {
           window.scrollTo({ top: 0, behavior: 'smooth' });
       });
       
       // Smooth scrolling for navigation links
       document.querySelectorAll('a[href^="#"]').forEach(anchor => {
           anchor.addEventListener('click', function (e) {
               e.preventDefault();
               const target = document.querySelector(this.getAttribute('href'));
               if (target) {
                   target.scrollIntoView({
                       behavior: 'smooth',
                       block: 'start'
                   });
               }
           });
       });

       // Mobile menu toggle (if needed)
       const mobileMenuButton = document.querySelector('.navbar-toggale-button button');
       const mobileMenu = document.querySelector('.navbar-menu');
       
       if (mobileMenuButton && mobileMenu) {
           mobileMenuButton.addEventListener('click', () => {
               mobileMenu.classList.toggle('hidden');
           });
       }

       // Navbar scroll effect
       const navbar = document.getElementById('navbar');
       window.addEventListener('scroll', () => {
           if (window.scrollY > 50) {
               navbar.classList.add('is-sticky');
           } else {
               navbar.classList.remove('is-sticky');
           }
       });
   </script>

</body>
</html>