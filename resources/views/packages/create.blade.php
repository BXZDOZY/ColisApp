@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold text-dark">Nouvel Envoi</h2>
        <p class="text-muted">Enregistrez un nouveau colis dans le système</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <form action="{{ route('admin.packages.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="fw-bold border-bottom pb-2 mb-4 text-primary"><i class="fas fa-user-circle me-2"></i> Informations Expéditeur</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nom Complet <span class="text-danger">*</span></label>
                            <input type="text" name="sender_name" class="form-control form-control-lg bg-light border-0" placeholder="Ex: Jean Paul" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" name="sender_phone" class="form-control form-control-lg bg-light border-0" placeholder="Ex: +226 xx xx xx xx" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Adresse de Départ <span class="text-danger">*</span></label>
                            <textarea name="sender_address" class="form-control bg-light border-0" rows="2" placeholder="Ex: Avenue de l'Indépendance, Ouagadougou" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="fw-bold border-bottom pb-2 mb-4 text-info"><i class="fas fa-shipping-fast me-2"></i> Informations Destinataire</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nom Complet <span class="text-danger">*</span></label>
                            <input type="text" name="receiver_name" class="form-control form-control-lg bg-light border-0" placeholder="Ex: Marie Koné" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" name="receiver_phone" class="form-control form-control-lg bg-light border-0" placeholder="Ex: +226 xx xx xx xx" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Adresse de Livraison <span class="text-danger">*</span></label>
                            <textarea name="receiver_address" class="form-control bg-light border-0" rows="2" placeholder="Ex: Rue 14.52, Porte 123, Quartier Pissy" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="fw-bold border-bottom pb-2 mb-4 text-warning"><i class="fas fa-box-open me-2"></i> Détails du Colis</h5>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Poids (kg) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="weight" class="form-control form-control-lg bg-light border-0" placeholder="0.0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Type de Colis <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-lg bg-light border-0" required>
                                <option value="Standard">Standard</option>
                                <option value="Enveloppe">Enveloppe</option>
                                <option value="Fragile">Fragile</option>
                                <option value="Express">Express</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold small text-uppercase">Description / Notes</label>
                            <textarea name="description" class="form-control bg-light border-0" rows="2" placeholder="Ex: Documents importants, colis fragile à manipuler avec soin..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-light px-4 py-2 fw-bold border">Annuler</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">
                            <i class="fas fa-save me-2"></i> Enregistrer l'envoi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
