# Habit Tracker API

Tables  

- habits
- habits_logs

Models

- Habit
    -> user_id: foreign id
    -> title: string

- HabitLog
    -> habit_id: foreign id
    -> completed_at: datetime

- Routes
