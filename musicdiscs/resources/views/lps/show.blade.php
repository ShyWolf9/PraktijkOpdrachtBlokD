@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h1>{{ $lp->album }} by {{ $lp->artist }}</h1>
        <p><strong>Release Year:</strong> {{ $lp->release_year }}</p>
        <p><strong>Price:</strong> €{{ $lp->price }},-</p>
        <p><strong>Genre:</strong> {{ $lp->genre }}</p>
        <p><strong>Status:</strong> {{ $lp->status }}</p>
        <p><strong>In Stock:</strong> {{ $lp->in_stock }}</p>
        <p><strong>Number of Tracks:</strong> {{ $lp->number_of_tracks }}</p>
        @if($lp->cover_image)
            <img src="{{ asset('storage/' . $lp->cover_image) }}" alt="Cover Image" width="200">
        @else
            <p>No cover image available.</p>
        @endif
        <a href="{{ route('lps.edit', $lp->id) }}" class="btn btn-primary mt-3">Edit LP</a>
    </div>
@endsection
