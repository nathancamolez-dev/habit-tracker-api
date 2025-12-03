<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitRequest;
use App\Http\Requests\UpdateHabitRequest;
use App\Http\Resources\HabitResource;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware("can:own,habit", except:['index', 'store']),
        ];
    }

    public function index()
    {
        request()->validate([
            'with' => ['sometimes', 'string', 'regex:/\b(?:logs|user)(?:.*\b(?:logs|user))?/i'],
        ]);

        return HabitResource::collection(
            Habit::query()
                ->where('user_id', Auth::id())
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
            ->filter(fn ($item): bool => $item !== '')
            ->toArray();

        return HabitResource::make($habit->load($load));
    }

    public function store(StoreHabitRequest $request)
    {
        $habit = Auth::user()->habits()->create($request->validated());

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
