<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lp;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $search = request('search');
        
        // If seller, show only their own LPs
        if ($user && $user->isSeller()) {
            $lps = Lp::with('seller')
                ->where('user_id', $user->id)
                ->when($search, function ($query, $search) {
                    $query->where('album', 'like', '%' . $search . '%');
                })
                ->get();
        } else {
            // Admin and users see all available LPs
            $lps = Lp::with('seller')
                ->where('sold', false)
                ->when($search, function ($query, $search) {
                    $query->where('album', 'like', '%' . $search . '%');
                })
                ->get();
        }
        
        return view('lps.index', compact('lps', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'album' => 'required',
            'artist' => 'required',
            'release_year' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'genre' => 'required',
            'status' => 'required',
            'in_stock' => 'required',
            'cover_image' => 'nullable|image',
            'number_of_tracks' => 'required|integer',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['sold'] = false;

        Lp::create($validated);

        return redirect()->route('lps.index')
            ->with('success', 'LP created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lp = Lp::with('seller')->findOrFail($id);
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
            'genre' => 'required',
            'status' => 'required',
            'in_stock' => 'required',
            'cover_image' => 'nullable|image',
            'number_of_tracks' => 'required|integer',
        ]);

        $lp = Lp::findOrFail($id);
        
        // Sellers can only update their own LPs
        if (Auth::user()->isSeller() && $lp->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own LP listings.');
        }
        
        $lp->update($validated);

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

        // Check if user has enough balance
        if ($buyer->balance < $lp->price) {
            return back()->with('error', 'Insufficient balance. You need €' . number_format($lp->price - $buyer->balance, 2) . ' more.');
        }

        DB::beginTransaction();
        try {
            // Deduct from buyer
            $buyer->balance -= $lp->price;
            $buyer->save();

            // Add to seller
            $seller = $lp->seller;
            $seller->balance += $lp->price;
            $seller->save();

            // Mark LP as sold
            $lp->sold = true;
            $lp->save();

            // Create transaction record
            Transaction::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'lp_id' => $lp->id,
                'amount' => $lp->price,
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
