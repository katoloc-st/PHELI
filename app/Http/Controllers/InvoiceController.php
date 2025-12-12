<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of orders for invoice export.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.post.wasteType', 'items.post.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('invoice.index', compact('orders'));
    }

    /**
     * Display the specified order for printing.
     */
    public function print($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.post.wasteType', 'items.post.user', 'user'])
            ->findOrFail($id);

        return view('invoice.print', compact('order'));
    }

    /**
     * Generate and download PDF invoice.
     */
    public function pdf($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.post.wasteType', 'items.post.user', 'user'])
            ->findOrFail($id);

        // TODO: Implement PDF generation using a library like dompdf or snappy
        // For now, we'll redirect to print page
        // You can install: composer require barryvdh/laravel-dompdf

        return redirect()->route('invoice.print', $id);
    }
}
