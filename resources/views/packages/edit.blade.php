@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ route('admin.packages.index') }}" class="btn btn-link text-muted p-0 mb-3 text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Retour à l'historique
        </a>
        <h2 class="fw-bold text-dark">Modifier le Colis</h2>
        <p class="text-muted">Modification des informations pour le colis #{{ $package->tracking_number }}</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Section Expéditeur -->
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-paper-plane me-2"></i> Expéditeur</h5>
                    <div class="mb-3">
                        <label for="sender_name" class="form-label fw-bold">Nom Complet</label>
                        <input type="text" class="form-control" name="sender_name" id="sender_name" value="{{ old('sender_name', $package->sender_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sender_phone" class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control" name="sender_phone" id="sender_phone" value="{{ old('sender_phone', $package->sender_phone) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sender_address" class="form-label fw-bold">Adresse de Départ</label>
                        <textarea class="form-control" name="sender_address" id="sender_address" rows="2" required>{{ old('sender_address', $package->sender_address) }}</textarea>
                    </div>
                </div>

                <!-- Section Destinataire -->
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold mb-3 text-success"><i class="fas fa-user-check me-2"></i> Destinataire</h5>
                    <div class="mb-3">
                        <label for="receiver_name" class="form-label fw-bold">Nom Complet</label>
                        <input type="text" class="form-control" name="receiver_name" id="receiver_name" value="{{ old('receiver_name', $package->receiver_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="receiver_phone" class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control" name="receiver_phone" id="receiver_phone" value="{{ old('receiver_phone', $package->receiver_phone) }}" required>
                    </div>
                </div>

                <div class="col-md-12 mb-4">
                    <div class="mb-3">
                        <label for="receiver_address" class="form-label fw-bold">Adresse de Destination</label>
                        <textarea class="form-control" name="receiver_address" id="receiver_address" rows="2" required>{{ old('receiver_address', $package->receiver_address) }}</textarea>
                    </div>
                </div>

                <hr class="my-2">

                <!-- Détails du Colis -->
                <div class="col-md-12 mt-4 mb-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-box me-2"></i> Détails du Colis</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label fw-bold">Type de Colis</label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="Standard" {{ $package->type == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Enveloppe" {{ $package->type == 'Enveloppe' ? 'selected' : '' }}>Enveloppe</option>
                                <option value="Fragile" {{ $package->type == 'Fragile' ? 'selected' : '' }}>Fragile</option>
                                <option value="Express" {{ $package->type == 'Express' ? 'selected' : '' }}>Express</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="weight" class="form-label fw-bold">Poids (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="weight" id="weight" value="{{ old('weight', $package->weight) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Statut Actuel</label>
                            <input type="text" class="form-control bg-light" value="{{ $package->status }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description (Optionnel)</label>
                        <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $package->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                    <i class="fas fa-save me-2"></i> Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
