<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai semua notifikasi milik user yang sedang login sebagai 'sudah dibaca'.
     */
    public function markAllAsRead(Request $request)
    {
        auth()->user()?->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}