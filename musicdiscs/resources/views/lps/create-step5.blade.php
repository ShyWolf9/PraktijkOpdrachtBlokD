@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-0">Create New LP - Step 5/5</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="alert alert-info mb-3">
                            <strong>Artist:</strong> {{ session('lp_create.artist') }} | <strong>Album:</strong> {{ session('lp_create.album') }}
                        </div>

                        <form action="{{ route('lps.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="sale_price" class="form-label fw-bold">Optional Sale Price (€)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror"
                                       id="sale_price" name="sale_price" value="{{ old('sale_price', '') }}" placeholder="Leave empty if no sale">
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">You can leave this empty if you don't want to add a sale price.</small>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1" onclick="history.back();">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                    <i class="bi bi-check-circle"></i> Create LP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
