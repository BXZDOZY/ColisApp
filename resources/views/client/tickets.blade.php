@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark">Mes Demandes de Support</h2>
                <p class="text-muted">Historique lié au numéro : <span class="fw-bold text-primary">{{ $phone }}</span></p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home me-2"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Section support -->
            <h4 class="fw-bold mb-4"><i class="fas fa-headset me-2"></i> Demandes de support</h4>
            @forelse($tickets as $ticket)
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <span class="badge 
                                @if($ticket->status == 'open') bg-danger
                                @elseif($ticket->status == 'in_progress') bg-primary
                                @else bg-success
                                @endif
                                text-uppercase small px-3 py-2">
                                @if($ticket->status == 'open') Ouvert
                                @elseif($ticket->status == 'in_progress') En cours
                                @else Fermé
                                @endif
                            </span>
                            <span class="ms-2 fw-bold text-dark">Ticket #{{ $ticket->id }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ $ticket->created_at->format('d M Y, H:i') }}</span>
                            <a href="{{ route('client.tickets.pdf', $ticket->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">{{ $ticket->subject }}</h5>
                        <div class="bg-light p-3 rounded-3 mb-2">
                            <p class="mb-0 text-dark">{{ $ticket->message }}</p>
                        </div>
                        @if($ticket->admin_response)
                            <div class="mt-3 border-top pt-3">
                                <h6 class="fw-bold text-success mb-2"><i class="fas fa-reply me-2"></i> Réponse :</h6>
                                <p class="mb-0 text-dark small italic">{{ $ticket->admin_response }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-light border-0 shadow-sm rounded-3 mb-5">
                    Aucun ticket de support trouvé.
                </div>
            @endforelse

            <!-- Section Bordereaux -->
            <h4 class="fw-bold mb-4 mt-5"><i class="fas fa-file-invoice me-2"></i> Bordereaux de transport</h4>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">N° Colis</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $package)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">{{ $package->tracking_number }}</td>
                                        <td>{{ $package->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $package->type }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('client.packages.ticket', $package->id) }}" class="btn btn-sm btn-dark rounded-pill px-3">
                                                <i class="fas fa-print me-1"></i> Imprimer
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aucun bordereau disponible.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
