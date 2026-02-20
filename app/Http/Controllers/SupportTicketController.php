<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\Package;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    // Admin: list all tickets (protected by auth middleware)
    public function index()
    {
        $tickets = SupportTicket::latest()->get();
        return view('support.index', compact('tickets'));
    }

    // Public: show the create form
    public function create()
    {
        $package = null;
        if (session('last_tracked_id')) {
            $package = Package::find(session('last_tracked_id'));
        }
        return view('support.create', compact('package'));
    }

    // Public: store a new ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'package_id' => 'nullable|exists:packages,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        SupportTicket::create($validated);

        return back()->with('success', 'Votre demande de support a été soumise. Nous vous contacterons bientôt !');
    }


    // Admin: generate PDF for a ticket
    public function downloadPDF($id)
    {
        $ticket = SupportTicket::with('package')->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('support.pdf', compact('ticket'))
            ->setPaper('a4');
        
        return $pdf->stream('ticket-'.$ticket->id.'.pdf');
    }

    // Client: list historical tickets by phone
    public function clientTickets(Request $request)
    {
        $phone = $request->get('phone') ?? session('client_phone');
        
        if (!$phone) {
            return redirect()->route('home')->with('error', 'Veuillez d\'abord vous identifier.');
        }

        $tickets = SupportTicket::where('phone', $phone)->latest()->get();
        $packages = Package::where('sender_phone', $phone)
            ->orWhere('receiver_phone', $phone)
            ->latest()
            ->get();
        
        return view('client.tickets', compact('tickets', 'packages', 'phone'));
    }

    public function clientDownloadPDF($id)
    {
        $phone = session('client_phone');
        
        if (!$phone) {
            return redirect()->route('home')->with('error', 'Session expirée. Veuillez vous identifier à nouveau.');
        }

        $ticket = SupportTicket::with('package')->where('phone', $phone)->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('support.pdf', compact('ticket'))
            ->setPaper('a4');
        
        return $pdf->stream('ticket-'.$ticket->id.'.pdf');
    }
}
