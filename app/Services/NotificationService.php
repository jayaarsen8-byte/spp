<?php

namespace App\Services;

use App\Notification;
use App\User;

class NotificationService
{
    public function notify($userId, $type, $title, $message, $icon = null, $color = null, $referenceId = null, $referenceType = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    public function notifyLowStock($productId, $productName)
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            $this->notify(
                $owner->id,
                'low_stock',
                'Low Stock Alert',
                "Product '{$productName}' is running low.",
                'alert-circle',
                'warning',
                $productId,
                'product'
            );
        }
    }

    public function notifyOutOfStock($productId, $productName)
    {
        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            $this->notify(
                $owner->id,
                'out_of_stock',
                'Out of Stock',
                "Product '{$productName}' is out of stock.",
                'x-circle',
                'danger',
                $productId,
                'product'
            );
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && !$notification->is_read) {
            $notification->update(['is_read' => true, 'read_at' => now()]);
        }
        return $notification;
    }
}
