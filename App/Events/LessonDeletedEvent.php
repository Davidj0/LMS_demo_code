<?php

namespace App\Events;

use App\Models\Position;
use App\Models\Lesson;
use App\Models\TextLocalized;
use Illuminate\Foundation\Events\Dispatchable;

class LessonDeletedEvent
{
    use Dispatchable;

    public function __construct(Lesson $lesson)
    {
        // Update count of lessons in course.
        $subheading_lessons_count = $lesson->course->lessons->count();
        $subheading = $lesson->course->subheading;

        if ($subheading_lessons_count == 1) {
            $langs_text = collect(config('languages'))->mapWithKeys(function ($item) {
                return [$item['code'] => $item['singularOfLesson']];
            });
        } else {
            $langs_text = collect(config('languages'))->mapWithKeys(function ($item) {
                return [$item['code'] => $item['pluralOfLesson']];
            });
        }

        if ($subheading) {
            foreach ($lesson->course->languages as $lang) {
                $subheading->setTranslation('localized_text', $lang, $subheading_lessons_count.' '.$langs_text[$lang]);
            }
            $subheading->save();
        }
    }
}
