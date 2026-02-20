<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\ShipmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    // ============================================================
    // ADMIN METHODS (protected by auth middleware in routes)
    // ============================================================

    public function index(Request $request)
    {
        $query = Package::query();

        // Search by tracking number, sender name, or receiver name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhere('sender_name', 'like', '%' . $search . '%')
                  ->orWhere('receiver_name', 'like', '%' . $search . '%')
                  ->orWhere('sender_phone', 'like', '%' . $search . '%')
                  ->orWhere('receiver_phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $packages = $query->latest()->paginate(10)->withQueryString();

        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'type' => 'required|string|in:Standard,Enveloppe,Fragile,Express',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['tracking_number'] = 'COL-' . strtoupper(Str::random(8));
        $validated['status'] = 'pending';

        $package = Package::create($validated);

        ShipmentHistory::create([
            'package_id' => $package->id,
            'status' => 'pending',
            'location' => $package->sender_address,
            'details' => 'Colis enregistré dans le système et en attente de prise en charge.',
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Colis enregistré avec succès ! Numéro de suivi : ' . $package->tracking_number);
    }

    public function show($tracking_number)
    {
        $package = Package::with(['histories' => function($query) {
            $query->latest();
        }])->where('tracking_number', $tracking_number)->firstOrFail();

        return view('packages.show', compact('package'));
    }

    public function updateStatus(Request $request, Package $package)
    {
        // On valide les données : location et details sont désormais "nullable" (facultatifs)
        $validated = $request->validate([
            'status' => 'required|string|in:pending,in_transit,delivered,cancelled',
            'location' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:500',
            'type' => 'nullable|string|in:Standard,Enveloppe,Fragile,Express',
            'weight' => 'nullable|numeric|min:0.1',
        ]);

        // Mise à jour des informations du colis
        $updateData = ['status' => $validated['status']];
        if (isset($validated['type'])) $updateData['type'] = $validated['type'];
        if (isset($validated['weight'])) $updateData['weight'] = $validated['weight'];

        $package->update($updateData);

    // Création de l'entrée dans l'historique
    // Automatisation des valeurs par défaut si les champs sont laissés vides
    ShipmentHistory::create([
        'package_id' => $package->id,
        'status' => $validated['status'],
        'location' => $validated['location'] ?? match($validated['status']) {
            'delivered' => $package->receiver_address,
            'in_transit' => $package->sender_address,
            'cancelled' => 'Annulation',
            default => 'Emplacement non précisé',
        },
        'details' => $validated['details'] ?? match($validated['status']) {
            'delivered' => 'Colis livré à destination.',
            'in_transit' => 'Colis en cours d\'acheminement.',
            'cancelled' => 'Le colis a été annulé par l\'administrateur.',
            default => 'Mise à jour du statut.',
        },
    ]);

    return back()->with('success', 'Statut du colis mis à jour avec succès !');
}
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'type' => 'required|string|in:Standard,Enveloppe,Fragile,Express',
            'description' => 'nullable|string|max:500',
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Colis mis à jour avec succès !');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Colis supprimé définitivement.');
    }

    // ============================================================
    // PUBLIC METHODS (no auth required)
    // ============================================================

    public function search(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:20',
        ]);

        $tracking_number = trim($request->input('tracking_number'));

        $package = Package::where('tracking_number', $tracking_number)->first();

        if (!$package) {
            return back()->with('error', 'Aucun colis trouvé avec le numéro de suivi : ' . $tracking_number);
        }

        return redirect()->route('tracking.show', $tracking_number);
    }

    public function publicShow($tracking_number)
    {
        $package = Package::with(['histories' => function($query) {
            $query->latest();
        }])->where('tracking_number', $tracking_number)->firstOrFail();

        // Stocker en session pour lier au support plus tard
        session(['last_tracked_id' => $package->id]);

        return view('tracking.show', compact('package'));
    }

    public function validatePackage($tracking_number)
    {
        $package = Package::where('tracking_number', $tracking_number)->firstOrFail();

        if ($package->status === 'delivered') {
            return view('admin.packages.validation_result', [
                'package' => $package,
                'status' => 'already_delivered',
                'message' => 'Ce colis est déjà récupéré.'
            ]);
        }

        $package->update(['status' => 'delivered']);

        ShipmentHistory::create([
            'package_id' => $package->id,
            'status' => 'delivered',
            'location' => $package->receiver_address,
            'details' => 'Colis validé et livré via scan QR Code.',
        ]);

        return view('admin.packages.validation_result', [
            'package' => $package,
            'status' => 'success',
            'message' => 'Colis validé avec succès ! Statut passé à : Livré.'
        ]);
    }

    public function scanner()
    {
        return view('admin.packages.scanner');
    }

    public function clientSearch(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:3',
        ]);

        $phone = trim($request->input('phone'));
        
        // Stocker en session pour le support
        session(['client_phone' => $phone]);

        $packages = Package::where('sender_phone', 'like', '%' . $phone . '%')
            ->orWhere('receiver_phone', 'like', '%' . $phone . '%')
            ->latest()
            ->get();

        return view('client.packages', compact('packages', 'phone'));
    }

    public function downloadTicket($id)
    {
        $package = Package::with(['histories' => function($query) {
            $query->oldest();
        }])->findOrFail($id);
        
        // Generate QR code with validation URL
        $validationUrl = route('admin.packages.validate', $package->tracking_number);
        $qrcode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($validationUrl));
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('packages.ticket', compact('package', 'qrcode'))
            ->setPaper('a4');
        
        return $pdf->stream('ticket-'.$package->tracking_number.'.pdf');
    }
}
