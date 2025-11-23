<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mutation;
use App\Models\User;
use Illuminate\Http\Request;

class MutationController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutation::with(['user', 'reference']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $mutations = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Mutation::count(),
            'credit' => Mutation::where('type', 'credit')->count(),
            'debit' => Mutation::where('type', 'debit')->count(),
            'total_credit_amount' => Mutation::where('type', 'credit')->sum('amount'),
            'total_debit_amount' => Mutation::where('type', 'debit')->sum('amount'),
        ];

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.mutations.index', compact('mutations', 'stats', 'users'));
    }

    public function show($id)
    {
        $mutation = Mutation::with(['user', 'reference'])->findOrFail($id);
        return view('admin.mutations.show', compact('mutation'));
    }
}
