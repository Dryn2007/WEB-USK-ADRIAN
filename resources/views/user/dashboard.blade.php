@extends('layouts.user')

@section('content')
    <div class="bg-pink-50 shadow p-6 max-w-6xl mx-auto mt-10 rounded-lg">
        <h1 class="text-2xl font-bold text-pink-800 mb-4">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-pink-600 mb-6">Lihat dan beli produk yang tersedia di bawah ini.</p>

        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('user.cart') }}" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
                🛒 Lihat Keranjang
            </a>
            <a href="{{ route('user.transactions') }}" class="bg-pink-400 text-white px-4 py-2 rounded hover:bg-pink-500">
                📦 Riwayat Transaksi
            </a>
            <a href="{{ route('user.chat') }}" class="relative bg-pink-300 text-white px-4 py-2 rounded hover:bg-pink-400">
                                <span>💬 Chat dengan Admin</span>

                                                @if(session('user_unread_messages_count'))
                                        <span class="absolute -top-2 -right-2 flex h-5 w-5">
                                                <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                <span
                            class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-white text-xs items-center justify-center">{{ session('user_unread_messages_count') }}</span>
                                            </span>
                                @endif
                            </a>
        </div>

        <form method="GET" action="{{ route('user.dashboard') }}" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">

                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-pink-700 mb-1">Cari Produk</label>
                    <input type="text" name="search" id="search" placeholder="Nama produk..." value="{{ request('search') }}"
                        class="border border-pink-300 p-2 rounded w-full focus:outline-pink-400">
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-pink-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori"
                        class="border border-pink-300 p-2 rounded w-full focus:outline-pink-400">
                        <option value="">Semua</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="harga_min" class="block text-sm font-medium text-pink-700 mb-1">Harga Min</label>
                    <input type="number" name="harga_min" id="harga_min" placeholder="Rp 0" value="{{ request('harga_min') }}"
                        class="border border-pink-300 p-2 rounded w-full focus:outline-pink-400">
                </div>

                <div>
                    <label for="harga_max" class="block text-sm font-medium text-pink-700 mb-1">Harga Max</label>
                    <input type="number" name="harga_max" id="harga_max" placeholder="Rp 1.000.000"
                        value="{{ request('harga_max') }}"
                        class="border border-pink-300 p-2 rounded w-full focus:outline-pink-400">
                </div>

            </div>
            <div class="mt-4">
                <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">
                    Filter Produk
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <!-- Ubah bagian card produk -->
        @forelse ($produk as $item)
            <div class="border border-pink-200 rounded p-4 shadow bg-white flex flex-col justify-between">
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}"
                    class="w-full h-40 object-cover rounded mb-2">
                <h2 class="text-lg font-semibold text-pink-700">{{ $item->nama }}</h2>

                <p class="mt-1 text-pink-800 font-bold">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>

                <!-- Tambahkan informasi stok -->
                <div class="mt-2 flex items-center">
                    <span class="text-sm {{ $item->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($item->stock > 0)
                            Stok: {{ $item->stock }}
                        @else
                            Stok Habis
                        @endif
                    </span>
                </div>

                <form action="{{ route('user.cart.add', $item->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 w-full {{ $item->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $item->stock <= 0 ? 'disabled' : '' }}>
                        {{ $item->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                    </button>
                </form>
            </div>
        @empty
            <p class="col-span-full text-pink-600">Produk tidak ditemukan.</p>
        @endforelse

        </div>
        <div class="mt-8">
            {{ $produk->appends(request()->query())->links() }}
        </div>
    </div>
@endsection