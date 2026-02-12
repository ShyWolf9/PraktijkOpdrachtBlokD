@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <h1>All LP's</h1>
            <table>
                <thead>
                    <tr>
                        <th>Album</th>
                        <th>Artist</th>
                        <th>Price</th>
                        <th>In Stock</th>
                        <th>Cover Image</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lps as $lp)
                    <tr>
                        <td>{{ $lp->album }}</td>
                        <td>{{ $lp->artist }}</td>
                        <td>{{ $lp->price }}</td>
                        <td>{{ $lp->in_stock }}</td>
                        <td><img src="{{ asset('storage/' . $lp->cover_image) }}" alt="Cover Image" width="100"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
@endsection
