@extends('layouts.public')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark"><i class="fas fa-headset text-info me-2"></i> Centre de Support</h2>
        <p class="text-muted">Envoyez-nous votre demande, nous vous répondrons dans les plus brefs délais</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h5 class="fw-bold mb-4">Ouvrir un Ticket de Support</h5>

                <form action="{{ route('support.store') }}" method="POST">
                    @csrf

                    @if($package)
                        <div class="alert alert-info py-2 px-3 border-0 rounded-3 mb-3 small d-flex align-items-center">
                            <i class="fas fa-link me-2"></i>
                            Demande liée au colis : <strong class="ms-1">{{ $package->tracking_number }}</strong>
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Nom Complet</label>
                            <input type="text" name="customer_name" class="form-control bg-light border-0 py-3" placeholder="Votre nom" value="{{ old('customer_name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Téléphone</label>
                            <input type="text" name="phone" class="form-control bg-light border-0 py-3" placeholder="Ex: 771234567" value="{{ old('phone', session('client_phone')) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Email</label>
                        <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="votre@email.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Sujet</label>
                        <input type="text" name="subject" class="form-control bg-light border-0 py-3" placeholder="Ex: Problème de suivi de colis" value="{{ old('subject') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Message</label>
                        <textarea name="message" class="form-control bg-light border-0" rows="5" placeholder="Décrivez votre problème en détail..." required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                        <i class="fas fa-paper-plane me-2"></i> Envoyer ma demande
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 bg-primary text-white shadow mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="fas fa-clock me-2"></i> Délai de réponse</h5>
                <p class="opacity-75 mb-0">Notre équipe répond généralement dans un délai de 24 à 48 heures ouvrées.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Questions Fréquentes</h5>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Comment suivre mon colis ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Rendez-vous sur la page d'accueil et entrez votre numéro de suivi dans le champ de recherche.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Mon colis est en retard, que faire ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Vérifiez d'abord le statut de votre colis. Si le délai est dépassé, ouvrez un ticket de support.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Comment voir tous mes colis ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Utilisez le "Portail Client" sur la page d'accueil et entrez votre numéro de téléphone.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
