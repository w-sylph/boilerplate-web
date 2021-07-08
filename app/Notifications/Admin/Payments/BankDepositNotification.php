<?php

namespace App\Notifications\Admin\Payments;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BankDepositNotification extends Notification implements ShouldQueue
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
        $this->title = "Bank Depost Slip Uploaded";
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
        $items = $item->invoice_items;
        $payment_provider = $item->payment_provider;
        $payment_type = $payment_provider->getPaymentType();
        $billing_address = $item->billing_address;
        $shipping_address = $item->shipping_address;

        return (new MailMessage)
                    ->subject(config('app.name') . ': ' . $this->title)
                    ->markdown('emails.payments.bank-deposit', [
                        'notifiable' => $notifiable,
                        'message' => 'This is to inform you that a new order has been placed.',
                        'statement' => $item,
                        'purchased_items' => $items,
                        'payment_provider' => $payment_provider,
                        'payment_type' => $payment_type,
                        'billing_address' => $billing_address,
                        'shipping_address' => $shipping_address,
                        'actionText' => 'View Invoice',
                        'actionURL' => $item->renderShowUrl(),
                        'fileUrl' => $item->renderPaymentReference(),
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
            'message' => 'Bank deposit slip has been uploaded',
            'subject_id' => $this->item->id, 
            'subject_type' => get_class($this->item),
        ];
    }
}
