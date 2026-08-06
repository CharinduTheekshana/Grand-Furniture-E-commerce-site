<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        // Only owner or admin
        if (auth()->id() !== $order->user_id && !auth()->user()?->is_admin) {
            abort(403);
        }

        $order->load('items.product', 'items.color');

        // Use Dompdf if installed, otherwise return HTML
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('order'))
                ->setPaper('a4', 'portrait');

            return $pdf->download('invoice-GF-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf');
        }

        // Fallback — return printable HTML
        return response()->view('pdf.invoice', compact('order'))
            ->header('Content-Type', 'text/html');
    }
}