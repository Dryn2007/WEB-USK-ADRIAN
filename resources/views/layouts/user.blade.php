<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - User</title>

    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

<body class="bg-pink-50 font-sans antialiased">
    <div id="app">
        <!-- Navbar -->
        <nav class="bg-pink-600 fixed top-0 left-0 w-full z-50 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/user/dashboard') }}" class="text-white font-bold text-lg">
                            ADR BOOKS ( {{ Auth::user()->name }} )
                        </a>
                    </div>
                    <a href="{{ route('user.chat') }}" class="posisi-link-chat-anda">
                            Chat dengan Admin
                            @if(session('user_unread_messages_count'))
                                    <span class="badge-notifikasi-user">{{ session('user_unread_messages_count') }}</span>
                            @endif
                    </a>

                    <div class="flex items-center space-x-6">

                        <!-- Tombol Logout -->
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-white hover:text-pink-100 font-medium">
                            Logout
                        </a>
                        <a href="{{ route('about.show') }}" class="text-white font-semibold hover:text-gray-300">
                            About Us
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- Content -->
        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>