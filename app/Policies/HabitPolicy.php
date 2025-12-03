<?php

declare(strict_types = 1);

namespace App\Policies;

use App\Models\Habit;
use App\Models\User;

class HabitPolicy
{
    public function own(User $user, Habit $habit): bool
    {
        return $user->id === $habit->user_id;
    }
}
