<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lp;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $search = request('search');

        // filtering / sorting inputs
        $sortBy = request('sort_by'); // views, year, alphabet, price
        $order = request('order', 'desc');
        $year = request('year');
        $minPrice = request('min_price');
        $maxPrice = request('max_price');

        // Build base query
        if ($user && $user->isSeller()) {
            $query = Lp::with('seller')->where('user_id', $user->id);
        } else {
            $query = Lp::with('seller')->where('sold', false);
        }

        // Search
        if ($search) {
            $query->where('album', 'like', '%' . $search . '%');
        }

        // Year filter (exact match)
        if ($year) {
            $query->where('release_year', $year);
        }

        // Price range filter (use sale_price if present)
        if ($minPrice !== null && $minPrice !== '') {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice]);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
        }

        // Sorting
        if ($sortBy === 'views') {
            $query->orderBy('clicks', $order);
        } elseif ($sortBy === 'year') {
            $query->orderBy('release_year', $order);
        } elseif ($sortBy === 'alphabet') {
            $query->orderBy('album', $order);
        } elseif ($sortBy === 'price') {
            $query->orderByRaw('COALESCE(sale_price, price) ' . ($order === 'asc' ? 'ASC' : 'DESC'));
        } else {
            // default ordering
            $query->orderBy('created_at', 'desc');
        }

        $lps = $query->get();

        return view('lps.index', compact('lps', 'search', 'sortBy', 'order', 'year', 'minPrice', 'maxPrice'));
    }

    /**
     * Display user's own LPs (for sellers and admins)
     */
    public function myListings()
    {
        $user = Auth::user();
        $search = request('search');

        $lps = Lp::with('seller')
            ->where('user_id', $user->id)
            ->when($search, function ($query, $search) {
                $query->where('album', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('lps.my-listings', compact('lps', 'search'));
    }

    /**
     * Show the form for creating a new resource - Step 1: Artist
     */
    public function create()
    {
        return view('lps.create-step1');
    }

    /**
     * Step 2: Album name
     */
    public function createStep2(Request $request)
    {
        $request->validate([
            'artist' => 'required|string|max:255',
        ]);

        session(['lp_create.artist' => $request->artist]);

        return view('lps.create-step2');
    }

    /**
     * Step 3: Upload images
     */
    public function createStep3(Request $request)
    {
        $request->validate([
            'album' => 'required|string|max:255',
        ]);

        session(['lp_create.album' => $request->album]);

        return view('lps.create-step3');
    }

    /**
     * Step 4: LP Details
     */
    public function createStep4(Request $request)
    {
        // Handle image uploads from step 3
        if ($request->hasFile('cover_images')) {
            $files = $request->file('cover_images');
            Log::info('Received ' . count($files) . ' uploaded cover_images files in step 3');

            $storedPaths = [];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    try {
                        $storedPaths[] = $file->store('lps/tmp', 'public');
                    } catch (\Exception $e) {
                        Log::error('Failed storing temporary uploaded file: ' . $e->getMessage());
                    }
                }
            }

            session(['lp_create.cover_images_temp' => $storedPaths]);
        }

        return view('lps.create-step4');
    }

    /**
     * Step 5: Optional sale
     */
    public function createStep5(Request $request)
    {
        $validated = $request->validate([
            'release_year' => 'required|integer',
            'genre' => 'required|string|max:255',
            'number_of_tracks' => 'required|integer',
            'status' => 'required|string|max:255',
            'in_stock' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        session([
            'lp_create.release_year' => $validated['release_year'],
            'lp_create.genre' => $validated['genre'],
            'lp_create.number_of_tracks' => $validated['number_of_tracks'],
            'lp_create.status' => $validated['status'],
            'lp_create.in_stock' => $validated['in_stock'],
            'lp_create.price' => $validated['price'],
        ]);

        return view('lps.create-step5');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate optional sale price from step 5
        $validated = $request->validate([
            'sale_price' => 'nullable|numeric|min:0',
        ]);

        // Add data from all previous steps (stored in session)
        $validated['artist'] = session('lp_create.artist');
        $validated['album'] = session('lp_create.album');
        $validated['release_year'] = session('lp_create.release_year');
        $validated['price'] = session('lp_create.price');
        $validated['genre'] = session('lp_create.genre');
        $validated['status'] = session('lp_create.status');
        $validated['in_stock'] = session('lp_create.in_stock');
        $validated['number_of_tracks'] = session('lp_create.number_of_tracks');
        $validated['user_id'] = Auth::id();
        $validated['sold'] = false;

        $lp = Lp::create($validated);

        // Handle image uploads from step 3 (if any were stored in session)
        $tempFiles = session('lp_create.cover_images_temp');
        if ($tempFiles) {
            Log::info('Processing ' . count($tempFiles) . ' images from session for LP id: ' . $lp->id);
            foreach ($tempFiles as $path) {
                if ($path) {
                    $lp->images()->create(['path' => $path]);
                }
            }
        }

        session()->forget('lp_create');

        return redirect()->route('lps.index')
            ->with('success', 'LP created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lp = Lp::with('seller')->findOrFail($id);
        // increment view counter on each visit
        try {
            $lp->increment('clicks');
            $lp->refresh();
        } catch (\Exception $e) {
            // if DB column missing or increment fails, continue without breaking the page
        }

        return view('lps.show', compact('lp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lp = Lp::findOrFail($id);

        // Sellers can only edit their own LPs
        if (Auth::user()->isSeller() && $lp->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own LP listings.');
        }

        return view('lps.edit', compact('lp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'album' => 'required',
            'artist' => 'required',
            'release_year' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'genre' => 'required',
            'status' => 'required',
            'in_stock' => 'required',
            'cover_image' => 'nullable|image',
            'cover_images' => 'nullable|array',
            'cover_images.*' => 'nullable|image',
            'number_of_tracks' => 'required|integer',
        ]);

        $lp = Lp::findOrFail($id);

        // Sellers can only update their own LPs
        if (Auth::user()->isSeller() && $lp->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own LP listings.');
        }

        $lp->update($validated);

        // Handle image uploads from the edit form (single or multiple)
        if ($request->hasFile('cover_images')) {
            foreach ($request->file('cover_images') as $file) {
                if ($file && $file->isValid()) {
                    try {
                        $path = $file->store('lps', 'public');
                        $lp->images()->create(['path' => $path]);
                    } catch (\Exception $e) {
                        Log::error('Failed storing uploaded file in update: ' . $e->getMessage());
                    }
                }
            }
        }

        // Support legacy single-file input named cover_image
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            if ($file && $file->isValid()) {
                try {
                    $path = $file->store('lps', 'public');
                    $lp->images()->create(['path' => $path]);
                } catch (\Exception $e) {
                    Log::error('Failed storing uploaded single cover_image in update: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('lps.index')
            ->with('success', 'LP updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lp = Lp::findOrFail($id);

        // Only admin or the seller who created it can delete
        if (Auth::user()->isSeller() && $lp->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own LP listings.');
        }

        $lp->delete();

        return redirect()->route('lps.index')
            ->with('success', 'LP deleted successfully.');
    }

    /**
     * Purchase an LP
     */
    public function purchase(string $id)
    {
        $lp = Lp::with('seller')->findOrFail($id);
        $buyer = Auth::user();

        // Check if already sold
        if ($lp->sold) {
            return back()->with('error', 'This LP has already been sold.');
        }

        // Check if user is trying to buy their own LP
        if ($lp->user_id === $buyer->id) {
            return back()->with('error', 'You cannot buy your own LP.');
        }

        // Use sale price if available, otherwise use regular price
        $purchasePrice = $lp->sale_price ?? $lp->price;

        // Check if user has enough balance
        if ($buyer->balance < $purchasePrice) {
            return back()->with('error', 'Insufficient balance. You need €' . number_format($purchasePrice - $buyer->balance, 2) . ' more.');
        }

        DB::beginTransaction();
        try {
            // Deduct from buyer
            $buyer->balance -= $purchasePrice;
            $buyer->save();

            // Add to seller
            $seller = $lp->seller;
            $seller->balance += $purchasePrice;
            $seller->save();

            // Mark LP as sold
            $lp->sold = true;
            $lp->save();

            // Create transaction record
            Transaction::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'lp_id' => $lp->id,
                'amount' => $purchasePrice,
                'type' => 'purchase',
            ]);

            DB::commit();

            return redirect()->route('lps.index')
                ->with('success', 'LP purchased successfully! Your new balance is €' . number_format($buyer->balance, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Purchase failed. Please try again.');
        }
    }
}
