@extends('layouts.dashboard_layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 font-weight-bold">HQ Admin Dashboard: <span class="text-primary">All Locations</span></h2>
        <a href="{{ route('reports.index') }}" class="btn btn-primary shadow-sm">View All Reports</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-uppercase small opacity-8">Total Spending ({{ now()->format('F') }})</h6>
                    <h2 class="mb-0">RM {{ number_format($totalKosBulanIni, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase small text-muted">Budget Utilization (HQ)</h6>
                    <h2 class="mb-2">{{ number_format($peratusBudget, 1) }}%</h2>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar {{ $peratusBudget > 80 ? 'bg-danger' : 'bg-success' }}" 
                             role="progressbar" style="width: {{ $peratusBudget }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase small">Pending Budget Requests</h6>
                    <h2 class="mb-0">--</h2> 
                    <small>Module coming soon</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-dark">Top 3 Locations by Spending</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="text-center">Rank</th>
                        <th class="text-center">Location (PTS)</th>
                        <th class="text-center">Total Maintenance Cost</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPTS as $index => $pts)
                    <tr>
                        <td>
                            <span class="badge badge-pill {{ $index == 0 ? 'badge-danger' : 'badge-secondary' }}">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="font-weight-bold">{{ $pts->pts_lokasi }}</td>
                        <td>RM {{ number_format($pts->total, 2) }}</td>
                        <td>
                            @if($pts->total > ($totalHqBudget / 5)) <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> High Spending</span>
                            @else
                                <span class="text-success small">Normal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-4 text-muted text-center">No data available for this month.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection