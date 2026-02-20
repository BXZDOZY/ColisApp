@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6 text-center">
        @if($status === 'success')
            <div class="card shadow-sm border-0 p-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h2 class="fw-bold text-success mb-3">{{ $message }}</h2>
                <div class="bg-light p-4 rounded-4 mb-4 text-start">
                    <p class="mb-2"><strong>Colis :</strong> #{{ $package->tracking_number }}</p>
                    <p class="mb-2"><strong>Destinataire :</strong> {{ $package->receiver_name }}</p>
                    <p class="mb-0"><strong>Adresse :</strong> {{ $package->receiver_address }}</p>
                </div>
                <a href="{{ route('admin.packages.scanner') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                    <i class="fas fa-camera me-2"></i> Scanner un autre colis
                </a>
            </div>
        @else
            <div class="card shadow-sm border-0 p-5">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
                </div>
                <h2 class="fw-bold text-warning mb-3">Déjà livré</h2>
                <p class="text-muted fs-5 mb-4">{{ $message }}</p>
                <div class="bg-light p-4 rounded-4 mb-4 text-start">
                    <p class="mb-2"><strong>Colis :</strong> #{{ $package->tracking_number }}</p>
                    <p class="mb-0"><strong>Status :</strong> <span class="badge bg-success">LIVRÉ</span></p>
                </div>
                <a href="{{ route('admin.packages.scanner') }}" class="btn btn-outline-dark btn-lg rounded-pill px-5">
                    <i class="fas fa-camera me-2"></i> Scanner un autre colis
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
