<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Create Profile with a random avatar
        $user->profile()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'avatar' => $this->randomAvatar(), // Assign a random avatar
        ]);

        // create random username
        $user->username = 'user'.$user->id;
        $user->saveQuietly();

        // Todo: Send Welcome Email

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    /**
     * Generate a random avatar path.
     */
    private function randomAvatar(): string
    {
        // Generate a random avatar number between 1 and 15
        $avatarNumber = rand(1, 15);

        // Return the avatar path
        return 'avatars/avatar'.$avatarNumber.'.jpg';
    }
}
