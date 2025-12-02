<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Requests\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Models\HabitLog;

class HabitController extends Controller
{
    public function index()
    {
        return HabitResource::collection(
            Habit::query()
                ->when(
                    request()->string('with', '')->contains('user'),
                    fn ($query) => $query->with('user')
                )
                ->when(
                    request()->string('with', '') ->contains('logs'),
                    fn ($query) => $query->with('logs')
                )
                ->simplePaginate()
        );
    }

    public function show(Habit $habit)
    {
        request()->validate([
            'with' => ['sometimes', 'string', 'regex:/\b(?:logs|user)(?:.*\b(?:logs|user))?/i'],
        ]);

        $load = request()->string('with')
            ->explode(',')
            ->filter(fn ($item) => strlen($item) > 0)
            ->toArray();

        return HabitResource::make($habit->load($load));
    }

    public function store(StoreHabitRequest $request)
    {
        $data = $request->only('title', 'uuid');

        $habit = Habit::create(array_merge($data, ['user_id' => 1]));

        return HabitResource::make($habit);
    }

    public function update(Habit $habit, UpdateHabitRequest $request)
    {
        $data = $request->validated();

        $habit->update($data);

        return HabitResource::make($habit);
    }

    public function destroy(Habit $habit)
    {
        HabitLog::whereHabitId($habit->id)->delete();
        $habit->delete();

        return response()->noContent();
    }
}
