@extends('layouts.public')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark">Suivi du Colis</h2>
            <p class="text-muted">Référence : <span class="badge bg-primary fs-6">{{ $package->tracking_number }}</span></p>
        </div>
        <a href="/" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Nouvelle recherche
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-0 bg-white py-4">
                <h5 class="mb-0 fw-bold"><i class="fas fa-clock text-primary me-2"></i> Historique des Événements</h5>
            </div>
            <div class="card-body px-5">
                <div class="timeline">
                    @foreach($package->histories as $history)
                    <div class="timeline-item position-relative mb-4 pb-4 border-start ps-5">
                        <div class="timeline-point position-absolute start-0 top-0 translate-middle-x bg-white border border-4 border-{{ $loop->first ? 'primary' : 'secondary shadow-sm' }}" style="width: 24px; height: 24px; border-radius: 50%; z-index: 10;"></div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold mb-0 {{ $loop->first ? 'text-primary' : '' }}">
                                @if($history->status == 'pending') En attente
                                @elseif($history->status == 'in_transit') En transit
                                @elseif($history->status == 'delivered') Livré
                                @else {{ $history->status }}
                                @endif
                            </h6>
                            <span class="small text-muted fw-bold">{{ $history->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="mb-1 text-dark fw-bold"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $history->location }}</p>
                        <p class="text-muted small mb-0">{{ $history->details }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-0 bg-white py-4">
                <h5 class="mb-0 fw-bold">Résumé du Colis</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Statut :</span>
                    <span>
                        @if($package->status == 'pending')
                            <span class="badge bg-warning-subtle text-warning">En attente</span>
                        @elseif($package->status == 'in_transit')
                            <span class="badge bg-primary-subtle text-primary">En transit</span>
                        @elseif($package->status == 'delivered')
                            <span class="badge bg-success-subtle text-success">Livré</span>
                        @else
                            <span class="badge bg-light text-secondary">{{ $package->status }}</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Expéditeur :</span>
                    <span class="fw-bold">{{ $package->sender_name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Destinataire :</span>
                    <span class="fw-bold">{{ $package->receiver_name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Type :</span>
                    <span class="fw-bold">{{ $package->type }}</span>
                </div>
                <div class="d-flex justify-content-between mb-0">
                    <span class="text-muted">Poids :</span>
                    <span class="fw-bold">{{ $package->weight }} kg</span>
                </div>
            </div>
        </div>

        <div class="card bg-light border-0 p-4">
            <h6 class="fw-bold text-muted"><i class="fas fa-info-circle me-2"></i> Besoin d'aide ?</h6>
            <p class="text-muted small mb-3">Si vous avez des questions sur votre colis, contactez notre centre de support.</p>
            <a href="{{ route('support.create') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="fas fa-headset me-1"></i> Contacter le support
            </a>
        </div>
    </div>
</div>

<style>
    .timeline-item:last-child {
        border-left: none !important;
        padding-bottom: 0 !important;
    }
</style>
@endsection
