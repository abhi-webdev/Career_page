<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoleUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $oldRole,
        public string $newRole,
        public string $changedByName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $displayRole = strtoupper($this->newRole);

        return [
            'title' => 'Role Updated',
            'message' => "Your account role has been updated to {$displayRole} by {$this->changedByName}.",
            'type' => 'role_change',
            'old_role' => $this->oldRole,
            'new_role' => $this->newRole,
        ];
    }
}
