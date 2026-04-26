@extends('layouts.dashboard_layout')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Budget Management Center</h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-clock mr-2"></i> Pending Additional Budget Requests
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light small text-uppercase">
                    <tr>
                        <th>PTS</th>
                        <th>Amount Requested</th>
                        <th>Reason</th>
                        <th>Quotation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $req)
                    <tr>
                        <td class="font-weight-bold">{{ $req->pts_lokasi }}</td>
                        <td class="text-primary">RM {{ number_format($req->jumlah_dipohon, 2) }}</td>
                        <td><small>{{ $req->sebab }}</small></td>
                        <td>
                            @if($req->lampiran_quotation)
                                <a href="{{ asset('storage/' . $req->lampiran_quotation) }}" target="_blank" class="btn btn-xs btn-info">View File</a>
                            @else
                                <span class="text-muted small">No File</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.budgets.approve', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('admin.budgets.reject', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-4 text-muted">No pending requests at the moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white font-weight-bold">
            <i class="fas fa-cog mr-2"></i> Set Monthly Base Budget per PTS
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center">
                <thead class="bg-light small text-uppercase">
                    <tr>
                        <th>Location (PTS)</th>
                        <th>Current Limit</th>
                        <th>Update New Limit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supervisors as $s)
                    <tr>
                        <td>{{ $s->pts_lokasi }}</td>
                        <td class="font-weight-bold">RM {{ number_format($s->budget_bulanan_maintenance, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.budgets.updateBase', $s->id) }}" method="POST" class="form-inline justify-content-center">
    @csrf
    @method('PATCH') {{-- PENTING: Untuk operasi update --}}
    
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text bg-light font-weight-bold" style="font-size: 0.75rem;">RM</span>
        </div>
        <input type="number" 
               name="amount" 
               class="form-control form-control-sm" 
               value="{{ $s->budget_bulanan_maintenance }}" 
               placeholder="Amount" 
               step="0.01" 
               style="width: 100px;">
        <div class="input-group-append">
            <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
    </div>
</form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection