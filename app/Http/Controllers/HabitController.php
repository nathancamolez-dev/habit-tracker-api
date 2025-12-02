<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;

class HabitController extends Controller
{
    public function index()
    {
        return HabitResource::collection(
            Habit::all()
        );
    }

    public function show(Habit $habit)
    {
        return HabitResource::make($habit);
    }

    public function store(StoreHabitRequest $request)
    {
        $data = $request->only('title', 'uuid');

        $habit = Habit::create(array_merge($data, ['user_id' => 1]));

        return HabitResource::make($habit);
    }
}
