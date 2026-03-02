@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                @if(auth()->user()->isSeller())
                    My LP Listings
                @else
                    Available LP's
                @endif
            </h1>
            @if(auth()->user()->isUser())
                <div class="alert alert-info mb-0">
                    <strong>Your Balance:</strong> €{{ number_format(auth()->user()->balance, 2) }}
                </div>
            @endif
        </div>

        <form action="{{ route('lps.index') }}" method="GET" class="row g-2 mb-4">
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
                    <a href="{{ route('lps.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                @if(auth()->user()->isSeller())
                    You haven't created any LP listings yet. <a href="{{ route('lps.create') }}">Create your first listing</a>
                @else
                    No LP's available at the moment.
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Album</th>
                            <th>Artist</th>
                            <th>Price</th>
                            @if(!auth()->user()->isSeller())
                                <th>Seller</th>
                            @endif
                            <th>In Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lps as $lp)
                        <tr>
                            <td>{{ $lp->album }}</td>
                            <td>{{ $lp->artist }}</td>
                            <td><strong>€{{ number_format($lp->price, 2) }}</strong></td>
                            @if(!auth()->user()->isSeller())
                                <td>{{ $lp->seller ? $lp->seller->name : 'N/A' }}</td>
                            @endif
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
                                <a href="{{ route('lps.show', $lp->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                
                                @if(auth()->user()->isAdmin() || (auth()->user()->isSeller() && $lp->user_id === auth()->id()))
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
                                @endif

                                @if(auth()->user()->isUser() && !$lp->sold)
                                    <form action="{{ route('lps.purchase', $lp->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                                @if(auth()->user()->balance < $lp->price) disabled @endif>
                                            <i class="bi bi-cart-plus"></i> 
                                            @if(auth()->user()->balance < $lp->price)
                                                Insufficient Funds
                                            @else
                                                Buy Now
                                            @endif
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
