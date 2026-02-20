@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark">Support Client</h2>
        <p class="text-muted">Gérez les demandes d'assistance des clients</p>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <div class="rounded-circle bg-primary-subtle text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-search fa-lg"></i>
            </div>
            <h6 class="fw-bold">Tracking</h6>
            <p class="text-muted small">Problèmes de suivi</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <div class="rounded-circle bg-success-subtle text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
            <h6 class="fw-bold">Facturation</h6>
            <p class="text-muted small">Paiements & Tarifs</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <div class="rounded-circle bg-info-subtle text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-cog fa-lg"></i>
            </div>
            <h6 class="fw-bold">Technique</h6>
            <p class="text-muted small">Bugs & Compte</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <div class="rounded-circle bg-warning-subtle text-warning mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-question-circle fa-lg"></i>
            </div>
            <h6 class="fw-bold">Autre</h6>
            <p class="text-muted small">Questions diverses</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Gestion des Tickets</h5>
                <span class="badge bg-primary">Total: {{ count($tickets) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 30%;">Ticket / Objet</th>
                                <th style="width: 25%;">Client / Contact</th>
                                <th style="width: 15%;">Statut</th>
                                <th style="width: 15%;">Date</th>
                                <th class="text-end pe-4" style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ $ticket->subject }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $ticket->message }}</div>
                                        @if($ticket->package_id)
                                            <div class="mt-1"><span class="badge bg-info-subtle text-info small">Colis: {{ $ticket->package->tracking_number }}</span></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $ticket->customer_name }}</div>
                                        <div class="small text-muted text-nowrap"><i class="fas fa-phone me-1"></i> {{ $ticket->phone }}</div>
                                    </td>
                                    <td>
                                        @if($ticket->status == 'open')
                                            <span class="badge rounded-pill bg-danger px-3">Ouvert</span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="badge rounded-pill bg-primary px-3">En cours</span>
                                        @else
                                            <span class="badge rounded-pill bg-success px-3">Fermé</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.support.pdf', $ticket->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        Aucun ticket trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
