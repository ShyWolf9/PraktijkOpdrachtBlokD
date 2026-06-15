@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Create New LP - Step 1/5</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <form action="{{ route('lps.create-step2') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="artist" class="form-label fw-bold">Artist Name</label>
                                <input type="text" class="form-control form-control-lg @error('artist') is-invalid @enderror"
                                       id="artist" name="artist" value="{{ old('artist') }}" required autofocus>
                                @error('artist')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the artist or band name</small>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <a href="{{ route('lps.index') }}" class="btn btn-outline-secondary btn-lg flex-grow-1">
                                    Cancel
                                </a>
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
