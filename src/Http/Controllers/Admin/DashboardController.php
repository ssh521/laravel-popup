<?php

namespace Ssh521\LaravelPopup\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Ssh521\LaravelPopup\Models\Popup;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('laravel-popup::admin.dashboard', [
            'totalCount' => Popup::count(),
            'activeCount' => Popup::active()->count(),
            'draftCount' => Popup::where('status', 'draft')->count(),
            'recentPopups' => Popup::latest()->limit(8)->get(),
        ]);
    }
}
