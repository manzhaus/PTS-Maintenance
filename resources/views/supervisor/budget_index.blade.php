@extends('layouts.dashboard_layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold mb-0">Pengurusan Bajet: {{ auth()->user()->pts_lokasi }}</h2>
            <p class="text-muted small">Pantau status permohonan dan baki peruntukan bulanan anda.</p>
        </div>
        <button type="button" class="btn btn-warning shadow-sm font-weight-bold px-4" data-toggle="modal" data-target="#modalRequestBudget">
            <i class="fas fa-plus-circle mr-1"></i> Mohon Bajet Tambahan
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase small text-muted font-weight-bold">Baki Bajet Semasa</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0 font-weight-bold text-dark">RM {{ number_format($bakiBudget ?? 0, 2) }}</h2>
                        <span class="ml-3 badge {{ ($percentLeft ?? 100) <= 20 ? 'badge-danger' : 'badge-success' }}">
                            {{ number_format($percentLeft ?? 100, 1) }}% Tinggal
                        </span>
                    </div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar {{ ($percentLeft ?? 100) <= 20 ? 'bg-danger' : 'bg-success' }}" 
                             style="width: {{ $percentLeft ?? 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase small text-muted font-weight-bold">Status Permohonan</h6>
                    <div class="row text-center mt-2">
                        <div class="col-4 border-right">
                            <h5 class="mb-0 font-weight-bold text-warning">{{ $myRequests->where('status', 'Submitted')->count() }}</h5>
                            <small class="text-muted">Pending</small>
                        </div>
                        <div class="col-4 border-right">
                            <h5 class="mb-0 font-weight-bold text-success">{{ $myRequests->where('status', 'Approved')->count() }}</h5>
                            <small class="text-muted">Lulus</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 font-weight-bold text-danger">{{ $myRequests->where('status', 'Rejected')->count() }}</h5>
                            <small class="text-muted">Ditolak</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-history mr-2 text-primary"></i>Sejarah Permohonan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead class="bg-light small text-uppercase font-weight-bold">
                        <tr>
                            <th>Tarikh</th>
                            <th>Jumlah (RM)</th>
                            <th>Sebab & Justifikasi</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myRequests as $req)
                        <tr>
                            <td class="align-middle">{{ $req->created_at->format('d/m/Y') }}</td>
                            <td class="align-middle font-weight-bold text-primary">RM {{ number_format($req->jumlah_dipohon, 2) }}</td>
                            <td class="align-middle text-left" style="max-width: 300px;">
                                <div class="text-center">
    <div class="small text-dark font-weight-bold mb-1">Justifikasi:</div>
    <div class="small text-muted">{{ $req->sebab }}</div>
</div>
                            </td>
                            <td class="align-middle">
                                @if($req->lampiran_quotation)
                                    <a href="{{ asset('storage/' . $req->lampiran_quotation) }}" target="_blank" class="btn btn-outline-info btn-xs">
                                        <i class="fas fa-file-pdf"></i> View
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($req->status == 'Approved')
                                    <span class="badge badge-pill badge-success px-3 py-2">
                                        <i class="fas fa-check-circle mr-1"></i> Diluluskan
                                    </span>
                                @elseif($req->status == 'Rejected')
                                    <span class="badge badge-pill badge-danger px-3 py-2">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge badge-pill badge-warning px-3 py-2 text-dark">
                                        <i class="fas fa-hourglass-half mr-1"></i> Sedang Diproses
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-2"></i>
                                <p>Tiada rekod permohonan ditemui untuk lokasi anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('supervisor.partials.modal_budget_request') 

@endsection

<style>
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    .badge-pill { border-radius: 50px; font-weight: 600; }
    .opacity-2 { opacity: 0.2; }
</style>