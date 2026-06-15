@extends('layout.app')

@section('content')
    <div class="container mt-4">
        @php $user = auth()->user(); @endphp
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-0">LP Details <small class="text-muted">&nbsp;&mdash;&nbsp;Views: {{ $lp->clicks ?? 0 }}</small></h3>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('lps.update', $lp->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Album</label>
                            <input type="text" class="form-control form-control-lg" name="album" value="{{ $lp->album }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Artist</label>
                            <input type="text" class="form-control form-control-lg" name="artist" value="{{ $lp->artist }}" required readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Genre</label>
                            <input type="text" class="form-control form-control-lg" name="genre" value="{{ $lp->genre }}" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Release Year</label>
                            <input type="number" class="form-control form-control-lg" name="release_year" value="{{ $lp->release_year }}" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Number of Tracks</label>
                            <input type="number" class="form-control form-control-lg" name="number_of_tracks" value="{{ $lp->number_of_tracks }}" required readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <input type="text" class="form-control form-control-lg" name="status" value="{{ $lp->status }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">In Stock</label>
                            <input type="text" class="form-control form-control-lg" name="in_stock" value="{{ $lp->in_stock }}" required readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Original Price (€)</label>
                            <input type="number" step="0.01" class="form-control form-control-lg bg-light" name="price" value="{{ $lp->price }}" required 
                                   @if($user && $user->isSeller() && $lp->user_id !== $user->id) readonly @endif>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">Sale Price (€) - Optional</label>
                            <input type="number" step="0.01" class="form-control form-control-lg border-danger" name="sale_price" 
                                   value="{{ $lp->sale_price ?? '' }}" placeholder="Set sale price"
                                   @if($user && $user->isSeller() && $lp->user_id !== $user->id) readonly @endif>
                            <small class="text-muted">Leave empty to remove sale</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Current Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white fs-4">€</span>
                                <input type="text" class="form-control form-control-lg fw-bold text-success" 
                                       value="{{ number_format($lp->sale_price ?? $lp->price, 2) }}" readonly>
                            </div>
                            @if($lp->sale_price && $lp->sale_price < $lp->price)
                                <span class="badge bg-danger mt-1">-{{ round((($lp->price - $lp->sale_price) / $lp->price) * 100) }}% OFF</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Images</label>
                            @if($lp->images && $lp->images->isNotEmpty())
                                <div class="mb-2 d-flex gap-2 flex-wrap">
                                    @foreach($lp->images as $img)
                                        <div style="width:120px;">
                                            <img src="{{ asset('storage/' . $img->path) }}" alt="LP Image" class="img-thumbnail" style="width:100%;height:auto;">
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(!empty($lp->cover_image))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $lp->cover_image) }}" alt="LP Image" class="img-thumbnail" style="max-width:300px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="cover_images[]" multiple accept="image/*"
                                @if($user && $user->isSeller() && $lp->user_id !== $user->id) disabled @endif>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('lps.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                        @if(($user && $user->isAdmin()) || ($user && $user->isSeller() && $lp->user_id === $user->id))
                            <div>
                                <button type="submit" class="btn btn-success btn-lg me-2">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                                <a href="{{ route('lps.edit', $lp->id) }}" class="btn btn-warning btn-lg me-2">
                                    <i class="bi bi-pencil"></i> Full Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-lg" onclick="document.getElementById('delete-form').submit();">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        @endif
                    </div>
                </form>

                @if(($user && $user->isAdmin()) || ($user && $user->isSeller() && $lp->user_id === $user->id))
                    <form id="delete-form" action="{{ route('lps.destroy', $lp->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif

                @if($lp->sold)
                    <div class="alert alert-warning mt-3">
                        <strong>Status:</strong> This LP has been sold.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
