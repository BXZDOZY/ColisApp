@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark">Historique des Envois</h2>
            <p class="text-muted">Gérez et suivez tous les colis enregistrés</p>
        </div>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-plus-circle me-2"></i> Nouvel Envoi
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-4">
        <form action="{{ route('admin.packages.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par N° de suivi, nom, téléphone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tous les Statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>En transit</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-filter me-1"></i> Filtrer</button>
                @if(request()->hasAny(['search', 'status', 'date']))
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary" title="Réinitialiser"><i class="fas fa-times"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">N° de Suivi</th>
                        <th>Expéditeur</th>
                        <th>Destinataire</th>
                        <th>Type</th>
                        <th>Poids</th>
                        <th>Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary">{{ $package->tracking_number }}</div>
                                <small class="text-muted">{{ $package->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $package->sender_name }}</div>
                                <small class="text-muted">{{ $package->sender_phone }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $package->receiver_name }}</div>
                                <small class="text-muted">{{ $package->receiver_phone }}</small>
                            </td>
                            <td>{{ $package->type }}</td>
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
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.packages.show', $package->tracking_number) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.packages.ticket', $package->id) }}" class="btn btn-sm btn-dark" title="Imprimer Ticket">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if(Auth::user()->isSuperAdmin())
                                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce colis ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3 border-0">
        {{ $packages->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
