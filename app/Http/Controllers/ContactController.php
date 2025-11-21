<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact-us');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        try {
            Contact::create([
                'type' => $request->type,
                'name' => $request->name,
                'whatsapp' => $request->whatsapp,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            // Generate WhatsApp link
            $phone = $request->whatsapp;
            $message = urlencode(
                "Halo, saya {$request->name}.\n\n" .
                "Tipe: {$request->type}\n\n" .
                "Pesan: {$request->message}"
            );
            $whatsappUrl = "https://wa.me/{$phone}?text={$message}";

            return redirect()->back()->with('whatsapp_url', $whatsappUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }
}
