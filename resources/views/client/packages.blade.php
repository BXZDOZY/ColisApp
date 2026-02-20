@extends('layouts.public')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark">Portail Client</h2>
            <p class="text-muted">Colis associés au numéro : <span class="badge bg-primary fs-6">{{ $phone }}</span></p>
        </div>
        <a href="/" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-4">
                <h5 class="mb-0 fw-bold">Mes Envois et Réceptions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">N° de Suivi</th>
                                <th>Rôle</th>
                                <th>Correspondant</th>
                                <th>Poids</th>
                                <th>Statut</th>
                                <th>Mise à jour</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary">{{ $package->tracking_number }}</div>
                                        <small class="text-muted">{{ $package->type }}</small>
                                    </td>
                                    <td>
                                        @if($package->sender_phone == $phone)
                                            <span class="badge bg-primary-subtle text-primary border-primary-subtle">Expéditeur</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border-success-subtle">Destinataire</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $package->sender_phone == $phone ? $package->receiver_name : $package->sender_name }}</div>
                                        <small class="text-muted">{{ $package->sender_phone == $phone ? $package->receiver_phone : $package->sender_phone }}</small>
                                    </td>
                                    <td>{{ $package->weight }} kg</td>
                                    <td>
                                        @if($package->status == 'pending')
                                            <span class="badge bg-warning-subtle text-warning border-warning-subtle">En attente</span>
                                        @elseif($package->status == 'in_transit')
                                            <span class="badge bg-primary-subtle text-primary border-primary-subtle">En transit</span>
                                        @elseif($package->status == 'delivered')
                                            <span class="badge bg-success-subtle text-success border-success-subtle">Livré</span>
                                        @else
                                            <span class="badge bg-light text-secondary border-secondary">{{ $package->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $package->updated_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('tracking.show', $package->tracking_number) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-search-location me-1"></i> Suivre
                                            </a>
                                            <a href="{{ route('client.packages.ticket', $package->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                                <i class="fas fa-print me-1"></i> Imprimer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="py-3">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-25"></i>
                                            <p class="text-muted">Aucun colis trouvé pour ce numéro.</p>
                                        </div>
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

<div class="row mt-3">
    <div class="col-md-6 mb-4">
        <div class="card border-0 bg-primary text-white p-4 h-100 shadow">
            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Aide</h5>
            <p class="mb-3 opacity-75">Pour toute question, contactez notre centre de support.</p>
            <a href="{{ route('support.create') }}" class="btn btn-light text-primary fw-bold align-self-start rounded-pill px-4 shadow-sm">Ouvrir un ticket</a>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card border-0 bg-dark text-white p-4 h-100 shadow">
            <h5 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2"></i> Confidentialité</h5>
            <p class="mb-3 opacity-75">Seuls les colis liés à votre numéro sont affichés ici.</p>
            <div class="d-flex align-items-center opacity-50">
                <i class="fas fa-lock me-2"></i> <small>Données protégées</small>
            </div>
        </div>
    </div>
</div>
@endsection
