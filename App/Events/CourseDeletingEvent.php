<?php

namespace App\Events;

use App\Models\Course;
use Illuminate\Foundation\Events\Dispatchable;

class CourseDeletingEvent
{
    use Dispatchable;

    public function __construct(Course $course)
    {
        // Cascade deletion one step downwards.
        optional($course->imageResource)->delete();
        optional($course->logoResource)->delete();
        optional($course->heading)->delete();
        optional($course->subheading)->delete();
        optional($course->description)->delete();
        optional($course->theme)->delete();

        // With foreach loop (because mass deletion does not trigger model events).
        foreach ($course->lessons as $lesson) {
            $lesson->delete();
        }

        optional($course->downloadSize)->delete();

        foreach ($course->trivia as $trivia) {
            // Set to course_id null temporarily to circumvent foreign key constraints.
            // Trivia will be deleted, when the cascade deletes the LearningMaterialTrivia.
            $trivia->course_id = null;
            $trivia->save();
        }
    }
}
