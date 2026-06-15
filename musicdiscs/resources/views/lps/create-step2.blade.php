@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Create New LP - Step 2/3</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="alert alert-info mb-3">
                            <strong>Artist:</strong> {{ session('lp_create.artist') }}
                        </div>

                        <form action="{{ route('lps.create-step3') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="album" class="form-label fw-bold">Album Name</label>
                                <input type="text" class="form-control form-control-lg @error('album') is-invalid @enderror" 
                                       id="album" name="album" value="{{ old('album') }}" required autofocus>
                                @error('album')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the album or LP name</small>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1" onclick="history.back();">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                    Next <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
