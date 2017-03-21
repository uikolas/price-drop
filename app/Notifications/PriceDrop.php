<?php

namespace App\Notifications;

use App\ProductRetailer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PriceDrop extends Notification
{
    //use Queueable;
    /**
     * @var ProductRetailer
     */
    private $productRetailer;

    /**
     * Create a new notification instance.
     *
     * @param ProductRetailer $productRetailer
     */
    public function __construct(ProductRetailer $productRetailer)
    {
        $this->productRetailer = $productRetailer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Price drop for: ' . $this->productRetailer->getProduct()->name)
            ->markdown('mails.notification.price-drop', [
                'productRetailer' => $this->productRetailer
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
            //
        ];
    }
}
