@extends('layout.app')

@section('content')
    <div class="container mt-4">
        @php $user = auth()->user(); @endphp
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>
                @if($user && $user->isSeller())
                    My LP Listings
                @else
                    Available LP's
                @endif
            </h1>
            @if($user && $user->isUser())
                <div class="alert alert-info mb-0">
                    <strong>Your Balance:</strong> €{{ number_format($user->balance, 2) }}
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
            <div class="col-md-2 col-lg-2">
                <select name="sort_by" class="form-select">
                    <option value="">Sort by</option>
                    <option value="views" {{ (isset($sortBy) && $sortBy === 'views') ? 'selected' : '' }}>Views</option>
                    <option value="year" {{ (isset($sortBy) && $sortBy === 'year') ? 'selected' : '' }}>Year</option>
                    <option value="alphabet" {{ (isset($sortBy) && $sortBy === 'alphabet') ? 'selected' : '' }}>A-Z</option>
                    <option value="price" {{ (isset($sortBy) && $sortBy === 'price') ? 'selected' : '' }}>Price</option>
                </select>
            </div>

            <div class="col-md-2 col-lg-2 d-flex gap-2">
                <select name="order" class="form-select">
                    <option value="desc" {{ (isset($order) && $order === 'desc') ? 'selected' : '' }}>Desc</option>
                    <option value="asc" {{ (isset($order) && $order === 'asc') ? 'selected' : '' }}>Asc</option>
                </select>
            </div>
            <div class="col-12 row g-2 mt-2">
                <div class="col-md-2">
                    <input type="number" name="year" class="form-control" placeholder="Year" value="{{ $year ?? '' }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="min_price" class="form-control" placeholder="Min price" value="{{ $minPrice ?? '' }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="max_price" class="form-control" placeholder="Max price" value="{{ $maxPrice ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('lps.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if(!empty($search))
            <div class="alert alert-secondary">
                Showing results for album name: <strong>{{ $search }}</strong>
            </div>
        @endif

        @if($lps->isEmpty())
            <div class="alert alert-warning">
                @if($user && $user->isSeller())
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
                            <th>Album</th>                            <th>Cover</th>                            <th>Artist</th>
                            <th>Price</th>
                            @if(!$user || !$user->isSeller())
                                <th>Seller</th>
                            @endif
                            <th>Views</th>
                            <th>In Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lps as $lp)
                        <tr>
                            <td>{{ $lp->album }}</td>
                            <td>
                                @if($lp->first_image)
                                    <img src="{{ asset('storage/' . $lp->first_image) }}" alt="cover" style="height:80px;object-fit:contain;">
                                @else
                                    <span class="text-muted small">No image</span>
                                @endif
                            </td>
                            <td>{{ $lp->artist }}</td>
                            <td>
                                @if($lp->sale_price && $lp->sale_price < $lp->price)
                                    <div>
                                        <span class="text-decoration-line-through text-muted">€{{ number_format($lp->price, 2) }}</span>
                                        <strong class="text-danger">€{{ number_format($lp->sale_price, 2) }}</strong>
                                    </div>
                                    <span class="badge bg-danger">-{{ round((($lp->price - $lp->sale_price) / $lp->price) * 100) }}% OFF</span>
                                @else
                                    <strong>€{{ number_format($lp->price, 2) }}</strong>
                                @endif
                            </td>
                            @if(!$user || !$user->isSeller())
                                <td>{{ $lp->seller ? $lp->seller->name : 'N/A' }}</td>
                            @endif
                            <td>{{ $lp->clicks ?? 0 }}</td>
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

                                @if(($user && $user->isAdmin()) || ($user && $user->isSeller() && $lp->user_id === $user->id))
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

                                @if($user && $user->isUser() && !$lp->sold)
                                    <form action="{{ route('lps.purchase', $lp->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @php
                                            $purchasePrice = $lp->sale_price ?? $lp->price;
                                        @endphp
                                        <button type="submit" class="btn btn-success btn-sm"
                                                @if($user && $user->balance < $purchasePrice) disabled @endif>
                                            <i class="bi bi-cart-plus"></i>
                                            @if($user && $user->balance < $purchasePrice)
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
