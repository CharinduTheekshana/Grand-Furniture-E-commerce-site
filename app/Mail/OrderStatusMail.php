<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $oldStatus) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->order->email,
            subject: 'Order Update — #GF-' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT) . ' is now ' . ucfirst($this->order->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
        );
    }
}