<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name or whatsapp
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $contacts = $query->paginate(15);

        // Statistics
        $stats = [
            'total' => Contact::count(),
            'pending' => Contact::where('status', 'pending')->count(),
            'in_progress' => Contact::where('status', 'in_progress')->count(),
            'resolved' => Contact::where('status', 'resolved')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'Contact berhasil dihapus');
    }
}
