@extends('layouts.dashboard_layout')

@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 font-weight-bold">HQ Admin Dashboard: <span class="text-primary">Global Overview</span></h2>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-file-download mr-1"></i> Lorry Reports
            </a>
            <a href="{{ route('assets.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-file-alt mr-1"></i> Assets Reports
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <i class="fas fa-money-bill-wave fa-2x opacity-5"></i>
                        </div>
                        <div>
                            <h6 class="text-uppercase small mb-1">Total Maintenance Cost ({{ now()->format('F Y') }})</h6>
                            <h2 class="mb-0 font-weight-bold">RM {{ number_format($totalKosBulanIni, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card {{ $pendingRequestsCount > 0 ? 'bg-warning border-0' : 'bg-light border-0' }} shadow-sm">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase small font-weight-bold {{ $pendingRequestsCount > 0 ? 'text-dark' : 'text-muted' }}">Pending Budget Requests</h6>
                            <h2 class="mb-0 font-weight-bold">{{ $pendingRequestsCount }}</h2>
                        </div>
                        <a href="{{ route('admin.budgets.index') }}" class="btn {{ $pendingRequestsCount > 0 ? 'btn-dark' : 'btn-outline-secondary' }} font-weight-bold">
                            Review Requests <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($overBudgetAlerts) > 0)
    <div class="alert alert-danger shadow-sm border-0 mb-4">
        <h6 class="font-weight-bold text-danger mb-3">
            <i class="fas fa-exclamation-triangle mr-2"></i> Lokasi Melebihi 80% Had Bajet
        </h6>
        <div class="row">
            @foreach($overBudgetAlerts as $alert)
            <div class="col-md-4 mb-2">
                <div class="bg-white p-3 rounded shadow-sm border-left border-danger">
                    <div class="small text-uppercase font-weight-bold text-muted">{{ $alert['pts'] }}</div>
                    <div class="h5 font-weight-bold mb-1 text-danger">{{ number_format($alert['percent'], 1) }}% Guna</div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $alert['percent'] }}%"></div>
                    </div>
                    <small class="text-muted">Baki: RM {{ number_format($alert['baki'], 2) }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-bar mr-2 text-danger"></i>Top 3: Perbelanjaan Tertinggi (RM)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="bg-light small text-uppercase font-weight-bold">
                            <tr>
                                <th>PTS</th>
                                <th>Jumlah Kos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPTS as $pts)
                            <tr>
                                <td class="font-weight-bold align-middle">{{ $pts['pts_lokasi'] }}</td>
                                <td class="align-middle text-danger font-weight-bold">RM {{ number_format($pts['total'], 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="py-4 text-muted text-center">Tiada data perbelanjaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-percentage mr-2 text-warning"></i>Top 3: Penggunaan Bajet Tertinggi (%)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="bg-light small text-uppercase font-weight-bold">
                            <tr>
                                <th>PTS</th>
                                <th>Had Bulanan</th>
                                <th>% Digunakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUtilization as $pts)
                            <tr>
                                <td class="font-weight-bold align-middle">{{ $pts['pts_lokasi'] }}</td>
                                <td class="align-middle text-muted">RM {{ number_format($pts['limit'], 0) }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="font-weight-bold mr-2 {{ $pts['percent'] > 90 ? 'text-danger' : 'text-warning' }}">
                                            {{ number_format($pts['percent'], 1) }}%
                                        </span>
                                        <div class="progress w-50" style="height: 6px;">
                                            <div class="progress-bar {{ $pts['percent'] > 90 ? 'bg-danger' : 'bg-warning' }}" 
                                                 style="width: {{ $pts['percent'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-4 text-muted text-center">Tiada data penggunaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .opacity-5 { opacity: 0.5; }
    .table th { border-top: none; }
</style>
@endsection