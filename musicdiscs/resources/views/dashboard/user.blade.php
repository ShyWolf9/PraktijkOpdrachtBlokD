<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard - Music Discs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Music Discs</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lps.index') }}">LP's</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">User Dashboard</h1>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>€{{ number_format(auth()->user()->balance, 2) }}</h3>
                                <p class="mb-0">Available Balance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ auth()->user()->purchases()->count() }}</h3>
                                <p class="mb-0">LP's Purchased</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>€{{ number_format(auth()->user()->purchases()->sum('amount'), 2) }}</h3>
                                <p class="mb-0">Total Spent</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Browse & Shop</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Browse LP's</h5>
                                        <p class="card-text">Explore our LP catalog and make purchases</p>
                                        <a href="{{ route('lps.index') }}" class="btn btn-primary btn-lg">Browse Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Purchase History</h5>
                                        <p class="card-text">View your purchase history and receipts</p>
                                        <span class="badge bg-secondary fs-5">{{ auth()->user()->purchases()->count() }} Purchases</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(auth()->user()->balance < 10)
                            <div class="alert alert-warning mt-3">
                                <strong>Low Balance!</strong> You have €{{ number_format(auth()->user()->balance, 2) }} remaining. 
                                Most LP's are priced around €10-20.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
