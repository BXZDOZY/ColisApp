@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark">Tableau de Bord</h2>
        <p class="text-muted">Aperçu global de l'activité de transport</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-primary border-4 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Colis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPackages }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-info border-4 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">En Transit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $packagesInTransit }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shipping-fast fa-2x text-gray-300 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-success border-4 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Livrés</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $packagesDelivered }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 border-start border-warning border-4 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Support Actif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeTickets }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-headset fa-2x text-gray-300 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Flux des Expéditions (Volume réel)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px; position: relative;">
                    <canvas id="shipmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Répartition par Type</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4" style="height: 250px; position: relative;">
                    <canvas id="typePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Derniers Colis Enregistrés --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold">Derniers Colis Enregistrés</h6>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">N° de Suivi</th>
                                <th>Expéditeur</th>
                                <th>Destinataire</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_packages as $pkg)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.packages.show', $pkg->tracking_number) }}" class="fw-bold text-primary text-decoration-none">{{ $pkg->tracking_number }}</a>
                                </td>
                                <td>{{ $pkg->sender_name }}</td>
                                <td>{{ $pkg->receiver_name }}</td>
                                <td><span class="badge bg-light text-dark">{{ $pkg->type }}</span></td>
                                <td>
                                    @if($pkg->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning">En attente</span>
                                    @elseif($pkg->status == 'in_transit')
                                        <span class="badge bg-info-subtle text-info">En transit</span>
                                    @elseif($pkg->status == 'delivered')
                                        <span class="badge bg-success-subtle text-success">Livré</span>
                                    @elseif($pkg->status == 'cancelled')
                                        <span class="badge bg-danger-subtle text-danger">Annulé</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $pkg->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart — real data from DB
        const lineCtx = document.getElementById('shipmentChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                    label: "Volume d'envoi",
                    data: {!! json_encode($monthlyCounts) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Pie Chart — real data from DB
        const pieCtx = document.getElementById('typePieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($typeLabels) !!},
                datasets: [{
                    data: {!! json_encode($typeCounts) !!},
                    backgroundColor: ['#0d6efd', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>
@endsection
