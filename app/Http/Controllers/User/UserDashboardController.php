<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Message;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan eager loading 'kategori'
        // Ini menggantikan dua blok query terpisah yang Anda miliki sebelumnya
        $query = Produk::with('kategori');

        // Filter by search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        //  Filter berdasarkan Harga Minimum
        if ($request->has('harga_min') && $request->harga_min != '') {
            // (int) untuk memastikan kita membandingkan angka
            $query->where('harga', '>=', (int) $request->harga_min);
        }

        //  Filter berdasarkan Harga Maksimum
        if ($request->has('harga_max') && $request->harga_max != '') {
            $query->where('harga', '<=', (int) $request->harga_max);
        }

        // Ambil semua kategori untuk dropdown filter
        $kategori = Kategori::all();

        // Eksekusi query dan dapatkan hasilnya
        // Urutkan berdasarkan yang terbaru

        $perPage = 6;

        $produk = $query->latest()->paginate($perPage);

        // Cek apakah user punya pesan baru dari admin
        $unreadMessagesCount = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        if ($unreadMessagesCount > 0) {
            session()->put('user_unread_messages_count', $unreadMessagesCount);
        } else {
            session()->forget('user_unread_messages_count');
        }

        return view('user.dashboard', compact('produk', 'kategori'));
    }
}
