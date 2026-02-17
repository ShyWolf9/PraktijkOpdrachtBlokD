@extends('layout.app')

@section('content')
    <form action="{{ route('lps.update', $lp->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="album" class="form-label">Album Name</label>
            <input type="text" class="form-control" id="album" name="album" value="{{ $lp->album }}" required>
        </div>

        <div class="mb-3">
            <label for="artist" class="form-label">Artist</label>
            <input type="text" class="form-control" id="artist" name="artist" value="{{ $lp->artist }}" required>
        </div>

        <div class="mb-3">
            <label for="release_year" class="form-label">Release Year</label>
            <input type="number" class="form-control" id="release_year" name="release_year" value="{{ $lp->release_year }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $lp->price }}" required>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" class="form-control" id="genre" name="genre" value="{{ $lp->genre }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" class="form-control" id="status" name="status" value="{{ $lp->status }}" required>
        </div>

        <div class="mb-3">
            <label for="in_stock" class="form-label">In Stock</label>
            <input type="text" class="form-control" id="in_stock" name="in_stock" value="{{ $lp->in_stock }}" required>
        </div>

        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" id="cover_image" name="cover_image">
        </div>

        <div class="mb-3">
            <label for="number_of_tracks" class="form-label">Number of Tracks</label>
            <input type="number" class="form-control" id="number_of_tracks" name="number_of_tracks" value="{{ $lp->number_of_tracks }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update LP</button>
    </form>

    <form action="{{ route('lps.destroy', $lp->id) }}" method="POST" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete LP</button>
    </form>
@endsection

