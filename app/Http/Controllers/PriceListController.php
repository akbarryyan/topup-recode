<?php

namespace App\Http\Controllers;

use App\Models\GameService;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * Display the price list page
     */
    public function index(Request $request)
    {
        // Get all unique games for filter dropdown
        $games = GameService::where('is_active', true)
            ->where('status', 'available')
            ->select('game')
            ->distinct()
            ->orderBy('game')
            ->pluck('game');

        // Get game services with pagination
        $query = GameService::where('is_active', true)
            ->where('status', 'available')
            ->orderBy('game')
            ->orderBy('name');

        // Apply game filter if provided
        if ($request->has('game') && $request->game != '') {
            $query->where('game', $request->game);
        }

        $services = $query->paginate(15);

        // If AJAX request, return only the table and pagination HTML
        if ($request->ajax()) {
            return view('partials.price-list-table', compact('services'))->render();
        }

        return view('price-list', compact('games', 'services'));
    }
}
