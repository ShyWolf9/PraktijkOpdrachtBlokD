@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Create New LP - Step 4/5</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="alert alert-info mb-3">
                            <strong>Artist:</strong> {{ session('lp_create.artist') }} | <strong>Album:</strong> {{ session('lp_create.album') }}
                        </div>

                        <form action="{{ route('lps.create-step5') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="release_year" class="form-label fw-bold">Release Year</label>
                                    <input type="number" class="form-control @error('release_year') is-invalid @enderror"
                                           id="release_year" name="release_year" value="{{ old('release_year', date('Y')) }}" required>
                                    @error('release_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="genre" class="form-label fw-bold">Genre</label>
                                    <input type="text" class="form-control @error('genre') is-invalid @enderror"
                                           id="genre" name="genre" value="{{ old('genre') }}" required>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="number_of_tracks" class="form-label fw-bold">Number of Tracks</label>
                                    <input type="number" class="form-control @error('number_of_tracks') is-invalid @enderror"
                                           id="number_of_tracks" name="number_of_tracks" value="{{ old('number_of_tracks') }}" required>
                                    @error('number_of_tracks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold">Status</label>
                                    <input type="text" class="form-control @error('status') is-invalid @enderror"
                                           id="status" name="status" value="{{ old('status') }}" required>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="in_stock" class="form-label fw-bold">In Stock</label>
                                    <input type="text" class="form-control @error('in_stock') is-invalid @enderror"
                                           id="in_stock" name="in_stock" value="{{ old('in_stock') }}" required>
                                    @error('in_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label fw-bold">Price (€)</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', '10.00') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1" onclick="history.back();">
                                    <i class="bi bi-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                    <i class="bi bi-arrow-right"></i> Next: Optional Sale
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

