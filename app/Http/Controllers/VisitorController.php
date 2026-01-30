<?php

namespace App\Http\Controllers;

use App\Jobs\SyncVisitorToGoogleSheets;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display a listing of visitors
     */
    public function index(Request $request)
    {
        $query = Visitor::query()->orderBy('created_at', 'desc');

        // Filter by visitor type
        if ($request->filled('type')) {
            $query->where('visitor_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search by name or mobile
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $visitors = $query->paginate(20);

        return view('admin.visitors.index', compact('visitors'));
    }

    /**
     * Display the specified visitor
     */
    public function show(Visitor $visitor)
    {
        $visitor->load('employee', 'project');
        
        return view('admin.visitors.show', compact('visitor'));
    }

    /**
     * Success page after registration
     */
    public function success(Visitor $visitor)
    {
        if (!$visitor->otp_verified) {
            return redirect()->route('visitor.register')
                ->with('error', 'Visitor not verified');
        }

        return view('visitor.success', compact('visitor'));
    }

    /**
     * Checkout a visitor
     */
    public function checkout(Visitor $visitor)
    {
        if ($visitor->status !== 'checked_in') {
            return back()->with('error', 'Visitor is not checked in');
        }

        $visitor->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        return back()->with('success', 'Visitor checked out successfully');
    }

    /**
     * Visitor self-checkout (public route)
     */
    public function visitorCheckout(Visitor $visitor)
    {
        if ($visitor->status !== 'checked_in') {
            return back()->with('error', 'You are not currently checked in');
        }

        $visitor->update([
            'status' => 'checked_out',
            'checked_out_at' => now(),
        ]);

        \Log::info('Visitor checked out', [
            'visitor_id' => $visitor->id,
            'name' => $visitor->name,
            'checked_out_at' => now()
        ]);

        return back()->with('success', 'Thank you! You have been checked out successfully');
    }

    /**
     * Show sync status
     */
    public function syncStatus()
    {
        $totalVisitors = Visitor::verified()->count();
        $syncedVisitors = Visitor::verified()->where('synced_to_sheets', true)->count();
        $unsyncedVisitors = Visitor::unsynced()->count();

        $recentUnsynced = Visitor::unsynced()
            ->orderBy('verified_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.sync.status', compact(
            'totalVisitors',
            'syncedVisitors',
            'unsyncedVisitors',
            'recentUnsynced'
        ));
    }

    /**
     * Manually trigger sync for unsynced visitors
     */
    public function manualSync()
    {
        $unsyncedVisitors = Visitor::unsynced()->get();

        if ($unsyncedVisitors->isEmpty()) {
            return back()->with('info', 'No visitors to sync');
        }

        foreach ($unsyncedVisitors as $visitor) {
            SyncVisitorToGoogleSheets::dispatch($visitor);
        }

        return back()->with('success', "Queued {$unsyncedVisitors->count()} visitors for syncing");
    }
}
