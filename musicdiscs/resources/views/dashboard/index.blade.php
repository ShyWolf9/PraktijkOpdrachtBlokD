<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Music Discs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Music Discs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lps.index') }}">LP's</a>
                    </li>
                    @if($user->isAdmin() || $user->isSeller())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lps.create') }}">Add LP</a>
                        </li>
                    @endif
                    @if($user->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.admin') }}">Admin Panel</a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="mb-4">Welcome, {{ $user->name }}!</h1>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Your Role</h5>
                                        <p class="card-text display-6">{{ ucfirst($user->role) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Account Balance</h5>
                                        <p class="card-text display-6">€{{ number_format($user->balance, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Member Since</h5>
                                        <p class="card-text">{{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h3 class="mb-3">Quick Actions</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('lps.index') }}" class="btn btn-outline-primary btn-lg w-100">
                                    View All LP's
                                </a>
                            </div>
                            @if($user->isAdmin() || $user->isSeller())
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('lps.create') }}" class="btn btn-outline-success btn-lg w-100">
                                        Add New LP
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($user->isAdmin())
                            <div class="alert alert-warning mt-4" role="alert">
                                <h5 class="alert-heading">Admin Access</h5>
                                <p>You have full administrative privileges. You can manage all users, LP's, and system settings.</p>
                            </div>
                        @elseif($user->isSeller())
                            <div class="alert alert-info mt-4" role="alert">
                                <h5 class="alert-heading">Seller Access</h5>
                                <p>You can add, edit, and manage LP listings in the catalog.</p>
                            </div>
                        @else
                            <div class="alert alert-secondary mt-4" role="alert">
                                <h5 class="alert-heading">User Access</h5>
                                <p>You can browse and view all LP's in the catalog.</p>
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
