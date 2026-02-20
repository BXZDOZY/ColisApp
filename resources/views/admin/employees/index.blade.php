@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark">Gestion des Employés</h2>
            <p class="text-muted">Gérez les comptes d'accès pour votre personnel</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-user-plus me-2"></i> Nouvel Employé
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date de création</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=random&color=fff" class="rounded-circle me-3" width="40">
                                    <div class="fw-bold">{{ $employee->name }}</div>
                                </div>
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>
                                <span class="badge bg-info-subtle text-info border-info-subtle">Employé</span>
                            </td>
                            <td>{{ $employee->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3 border-0">
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
