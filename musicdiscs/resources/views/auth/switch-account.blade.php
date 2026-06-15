@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Switch Account</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
        </div>

        <div class="alert alert-info">
            Current account: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})
        </div>

        @if($accounts->isEmpty())
            <div class="alert alert-warning">No other accounts found.</div>
        @else
            <div class="list-group">
                @foreach($accounts as $account)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $account->name }}</div>
                            <small class="text-muted">{{ $account->email }} • {{ $account->role }}</small>
                        </div>
                        <form method="POST" action="{{ route('account.switch', $account->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Switch</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
