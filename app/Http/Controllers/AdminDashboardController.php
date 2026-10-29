<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $totalUsers = User::count();

        $pendingProducts = Product::where('status', 'pending')->count();

        $approvedProducts = Product::where('status', 'approved')->count();

        $rejectedProducts = Product::where('status', 'rejected')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingProducts',
            'approvedProducts',
            'rejectedProducts'
        ));
    }
}
