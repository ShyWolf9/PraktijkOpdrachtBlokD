@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>My LP Listings</h1>
            <a href="{{ route('lps.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Add New LP
            </a>
        </div>

        <form action="{{ route('lps.my-listings') }}" method="GET" class="row g-2 mb-4">
            <div class="col-md-8 col-lg-6">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by album name..."
                    value="{{ $search ?? '' }}"
                >
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
                @if(!empty($search))
                    <a href="{{ route('lps.my-listings') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>

        @if(!empty($search))
            <div class="alert alert-secondary">
                Showing results for album name: <strong>{{ $search }}</strong>
            </div>
        @endif

        @if($lps->isEmpty())
            <div class="alert alert-warning">
                You haven't created any LP listings yet. <a href="{{ route('lps.create') }}">Create your first listing</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Album</th>
                            <th>Artist</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>In Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lps as $lp)
                        <tr>
                            <td><strong>{{ $lp->album }}</strong></td>
                            <td>{{ $lp->artist }}</td>
                            <td>€{{ number_format($lp->price, 2) }}</td>
                            <td>
                                @if($lp->sale_price)
                                    <span class="badge bg-danger">€{{ number_format($lp->sale_price, 2) }}</span>
                                    <br>
                                    <small class="text-danger">-{{ round((($lp->price - $lp->sale_price) / $lp->price) * 100) }}% OFF</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lp->in_stock)
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($lp->sold)
                                    <span class="badge bg-secondary">Sold</span>
                                @else
                                    <span class="badge bg-primary">Available</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $lp->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('lps.show', $lp->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('lps.edit', $lp->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('lps.destroy', $lp->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this LP?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
