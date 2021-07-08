<?php

namespace App\Notifications\Admin\Invoices;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;
    protected $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->title = "Order Paid";
        $this->message = "An order has been marked as paid.";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $item = $this->item;
        $items = $this->item->invoice_items;
        $payment_type = $this->item->payment_provider->getPaymentType();
        $billing_address = $this->item->billing_address;
        $shipping_address = $this->item->shipping_address;

        return (new MailMessage)
                    ->subject(config('app.name') . ': ' . $this->title)
                    ->markdown('emails.invoices.show', [
                        'notifiable' => $notifiable,
                        'message' => 'This is to inform you that a payment has been made for the following order:',
                        'statement' => $item,
                        'purchased_items' => $items,
                        'payment_type' => $payment_type,
                        'billing_address' => $billing_address,
                        'shipping_address' => $shipping_address,
                        'actionText' => 'View Invoice',
                        'actionURL' => $item->renderShowUrl('web'),
                        'skip_instructions' => true,
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => 'Order has been marked as paid.',
            'subject_id' => $this->item->id, 
            'subject_type' => get_class($this->item),
        ];
    }
}
