<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;

class HabitController extends Controller
{
    public function index(StoreHabitRequest $request)
    {
        $data = $request->validated();

        $habit = Habit::create($data);

        return HabitResource::make($habit);
    }
}
