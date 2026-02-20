@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark">Suivi du Colis</h2>
            <p class="text-muted">Référence : <span class="badge bg-primary fs-6">{{ $package->tracking_number }}</span></p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Tracking Area -->
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

    <!-- Management Sidebar -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-0 bg-white py-4">
                <h5 class="mb-0 fw-bold">Résumé du Colis</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Expéditeur :</span>
                    <div class="text-end">
                        <div class="fw-bold">{{ $package->sender_name }}</div>
                        <small class="text-muted d-block">{{ $package->sender_address }}</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Destinataire :</span>
                    <div class="text-end">
                        <div class="fw-bold">{{ $package->receiver_name }}</div>
                        <small class="text-muted d-block text-wrap" style="max-width: 200px;">{{ $package->receiver_address }}</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                    <span class="text-muted">Type :</span>
                    <span class="fw-bold">{{ $package->type }}</span>
                </div>
                <div class="d-flex justify-content-between mb-0 border-bottom pb-2">
                    <span class="text-muted">Poids :</span>
                    <span class="fw-bold">{{ $package->weight }} kg</span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.packages.ticket', $package->id) }}" class="btn btn-outline-dark w-100 fw-bold">
                        <i class="fas fa-print me-2"></i> Imprimer le Bordereau (PDF)
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header border-0 bg-primary text-white py-4">
                <h5 class="mb-0 fw-bold">Mettre à jour le statut</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show py-2 px-3 border-0 rounded-3 small" role="alert">
                        <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show py-2 px-3 border-0 rounded-3 small" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Current status indicator --}}
                <div class="mb-3 p-2 rounded-3 bg-light text-center">
                    <small class="text-muted">Statut actuel :</small>
                    <span class="fw-bold ms-1
                        @if($package->status == 'pending') text-warning
                        @elseif($package->status == 'in_transit') text-primary
                        @elseif($package->status == 'delivered') text-success
                        @else text-danger
                        @endif
                    ">
                        @if($package->status == 'pending') En attente
                        @elseif($package->status == 'in_transit') En transit
                        @elseif($package->status == 'delivered') Livré
                        @elseif($package->status == 'cancelled') Annulé
                        @endif
                    </span>
                </div>

                @if(in_array($package->status, ['delivered', 'cancelled']))
                    <div class="alert alert-info py-2 px-3 border-0 rounded-3 small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Ce colis est déjà <strong>{{ $package->status == 'delivered' ? 'livré' : 'annulé' }}</strong>. Aucune mise à jour possible.
                    </div>
                @else
                <form action="{{ route('admin.packages.updateStatus', $package->id) }}" method="POST" id="statusForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Nouveau Statut <span class="text-danger">*</span></label>
                        <select name="status" id="statusSelect" class="form-select bg-light border-0" required>
                            <option value="" disabled selected>-- Choisir un statut --</option>
                            @if($package->status == 'pending')
                                <option value="in_transit" {{ old('status') == 'in_transit' ? 'selected' : '' }}>📦 En transit</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                            @elseif($package->status == 'in_transit')
                                <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>✅ Livré</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                            @endif
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select bg-light border-0" required>
                                <option value="Standard" {{ $package->type == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Enveloppe" {{ $package->type == 'Enveloppe' ? 'selected' : '' }}>Enveloppe</option>
                                <option value="Fragile" {{ $package->type == 'Fragile' ? 'selected' : '' }}>Fragile</option>
                                <option value="Express" {{ $package->type == 'Express' ? 'selected' : '' }}>Express</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase">Poids (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="weight" class="form-control bg-light border-0" value="{{ $package->weight }}" required>
                        </div>
                    </div>
                    <div class="mb-3" id="locationGroup">
                        <label class="form-label small fw-bold text-uppercase" id="locationLabel">Localisation <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="locationInput" class="form-control bg-light border-0" placeholder="Ex: Entrepot Dakar" value="{{ old('location') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase" id="detailsLabel">Commentaires <span class="text-danger" id="detailsRequired">*</span></label>
                        <textarea name="details" id="detailsInput" class="form-control bg-light border-0" rows="2" placeholder="Ex: Reçu au centre de tri">{{ old('details') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2" id="submitBtn" disabled>
                        <i class="fas fa-sync-alt me-1"></i> Mettre à jour
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if(!in_array($package->status, ['delivered', 'cancelled']))
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');
            const locationGroup = document.getElementById('locationGroup');
            const locationInput = document.getElementById('locationInput');
            const locationLabel = document.getElementById('locationLabel');
            const detailsInput = document.getElementById('detailsInput');
            const detailsRequired = document.getElementById('detailsRequired');
            const submitBtn = document.getElementById('submitBtn');

            const receiverAddress = @json($package->receiver_address);
            const senderAddress = @json($package->sender_address);

            statusSelect.addEventListener('change', function() {
                const selected = this.value;
                submitBtn.disabled = false;

                if (selected === 'delivered') {
                    // Livré: auto-fill with receiver address
                    locationGroup.style.display = 'block';
                    locationInput.value = receiverAddress || '';
                    locationInput.required = true;
                    locationInput.placeholder = 'Lieu de livraison';
                    locationLabel.innerHTML = 'Lieu de livraison <span class="text-danger">*</span>';
                    detailsInput.required = true;
                    detailsInput.value = 'Colis livré à destination.';
                    detailsInput.placeholder = 'Ex: Colis remis au destinataire';
                    detailsRequired.style.display = 'inline';
                } else if (selected === 'cancelled') {
                    // Annulé: no location needed, details pre-filled
                    locationGroup.style.display = 'none';
                    locationInput.value = '';
                    locationInput.required = false;
                    detailsInput.required = false;
                    detailsInput.value = 'Le colis a été annulé par l\'administrateur.';
                    detailsInput.placeholder = 'Raison de l\'annulation (optionnel)';
                    detailsRequired.style.display = 'none';
                } else if (selected === 'in_transit') {
                    // in_transit: pre-fill with sender address
                    locationGroup.style.display = 'block';
                    locationInput.value = senderAddress || '';
                    locationInput.required = true;
                    locationInput.placeholder = 'Position actuelle';
                    locationLabel.innerHTML = 'Localisation <span class="text-danger">*</span>';
                    detailsInput.required = true;
                    detailsInput.value = 'Colis en cours d\'acheminement.';
                    detailsInput.placeholder = 'Ex: En cours de transfert vers le centre de tri';
                    detailsRequired.style.display = 'inline';
                } else {
                    // other (pending, etc.)
                    locationGroup.style.display = 'block';
                    locationInput.value = '';
                    locationInput.required = true;
                    detailsInput.required = true;
                    detailsRequired.style.display = 'inline';
                }
            });

            // Trigger change if old value exists
            if (statusSelect.value) {
                statusSelect.dispatchEvent(new Event('change'));
            }
        });
        </script>
        @endif
    </div>
</div>

<style>
    .timeline-item:last-child {
        border-left: none !important;
        padding-bottom: 0 !important;
    }
</style>
@endsection
