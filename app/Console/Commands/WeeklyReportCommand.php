<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeeklyReport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WeeklyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Weekly Report of the habits.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (User::all() as $user) {
            $user->notify(
                new WeeklyReport($this->getHabits($user))
            );
        }
    }

    public function getHabits(User $user): Collection
    {
        $query = "
            with recursive calendar as (
            select DATE('now', 'weekday 0', '-6 days') as log_date
            union all
            select DATE(log_date, '+1 day')
            from calendar
            where DATE(log_date, '+1 day') <= DATE('now', 'weekday 0')
            )

            select h.id as habit_id,
            h.title as habit_name,
            c.log_date,
            case when hl.id is not null then 1 else 0 end completed
            from calendar c
            cross join habits h
            left join habit_logs hl on DATE(hl.completed_at) = c.log_date and hl.habit_id = h.id
            join users u on h.user_id = u.id
            where u.id = ?
            order by h.id, c.log_date";

        return collect(DB::select($query, [$user->id]))
            ->map(fn ($item) => (object) [

                'habit_id'   => $item->habit_id,
                'habit_name' => $item->habit_name,
                'log_date'   => Carbon::make($item->log_date),
                'completed'  => (bool) $item->completed,
            ]);
    }
}
