<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProductUpdateNotification extends Notification
{
    use Queueable;

    public $product;
    public $version;

    public function __construct(Product $product, ProductVersion $version)
    {
        $this->product = $product;
        $this->version = $version;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $changes = $this->version->changed_fields;
        $changeList = '';

        foreach ($changes as $field) {
            $old = $this->version->old_data[$field] ?? 'N/A';
            $new = $this->version->new_data[$field] ?? 'N/A';
            $changeList .= "- $field: $old → $new\n";
        }

        return (new MailMessage)
            ->subject('تغییرات محصول: ' . $this->product->title)
            ->line('محصول شما با موفقیت به‌روزرسانی شد.')
            ->line('تغییرات:')
            ->line($changeList)
            ->action('مشاهده محصول', url('/products/' . $this->product->id))
            ->line('با تشکر از شما!');
    }
}
