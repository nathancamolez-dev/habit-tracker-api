<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('report:weekly')
    ->sundays()->at('23:59');
