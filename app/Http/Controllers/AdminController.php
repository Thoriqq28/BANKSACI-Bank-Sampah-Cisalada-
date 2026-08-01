<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Admin
     */
    public function index()
    {
        // Mengarah ke resources/views/admin/dashboard.blade.php
        return view('admin.dashboard'); 
    }
}