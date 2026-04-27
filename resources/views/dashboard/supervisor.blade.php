@extends('layouts.dashboard_layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Supervisor Dashboard: <span class="text-primary">{{ $myLocation }}</span></h2>
    
    <a href="{{ route('assets.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-file-alt mr-1"></i> Assets Reports
            </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small opacity-7">Baki Budget ({{ now()->format('F') }})</h6>
                    <h2 class="display-6">RM {{ number_format($bakiBudget, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info shadow">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small text-muted">Total Kos Lori Bulan Ini</h6>
                    <h2 class="text-info">RM {{ number_format($kosLori, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-secondary shadow">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small text-muted">Total Kos Aset Bulan Ini</h6>
                    <h2 class="text-secondary">RM {{ number_format($kosAsetLain, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white font-weight-bold">Maintenance Lori (7 Hari Lepas)</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center"> <thead class="bg-light">
                    <tr>
                        <th class="text-center">Tarikh</th>
                        <th class="text-center">No Plat</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Vendor</th>
                        <th class="text-center">Kos (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lorryLogs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->tarikh)->format('d/m/Y') }}</td>
                        <td><span class="font-weight-bold">{{ $log->lorry->no_plat }}</span></td>
                        <td>{{ $log->jenis_maintenance }}</td>
                        <td>{{ $log->vendor }}</td>
                        <td>{{ number_format($log->kos_rm, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3">Tiada rekod maintenance lori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white font-weight-bold">
            Maintenance Aset & Fasiliti (7 Hari Lepas)
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center"> <thead class="bg-light">
                    <tr>
                        <th class="text-center">Tarikh</th>
                        <th class="text-center">Asset</th>
                        <th class="text-center">Jenis Kerja</th>
                        <th class="text-center">Kos (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assetLogs as $log)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->tarikh)->format('d/m/Y') }}</td>
                        <td>
                            <span class="font-weight-bold text-dark">{{ $log->asset->name ?? 'Unknown Asset' }}</span>
                            <br>
                            <small class="text-muted badge badge-light border">{{ $log->asset->category ?? 'No Category' }}</small>
                        </td>
                        <td>
                            <span class="text-dark">{{ $log->jenis_maintenance ?? $log->jenis_kerja }}</span>
                        </td>
                        <td class="font-weight-bold">
                            RM {{ number_format($log->kos_rm, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted italic">Tiada rekod maintenance aset.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection