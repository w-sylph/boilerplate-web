<?php

namespace App\Notifications\Admin\Inventories;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowstockInventoryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $low_stock_items;
    protected $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($low_stock_items)
    {
        $this->low_stock_items = $low_stock_items;
        $this->title = 'Low stock inventory alert';
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
        $low_stock_items = $this->low_stock_items;

        return (new MailMessage)
                    ->subject(config('app.name') . ': ' . $this->title)
                    ->markdown('emails.inventories.show', [
                        'notifiable' => $notifiable,
                        'message' => 'This is to inform you that the following items are low in stocks:',
                        'low_stock_items' => $low_stock_items,
                        'actionText' => 'View Inventory',
                        'actionURL' => route('admin.inventories.index'),
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
            'message' => 'Items are low on stocks',
        ];
    }
}
