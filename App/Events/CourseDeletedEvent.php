<?php

namespace App\Events;

use App\Models\Course;
use Illuminate\Foundation\Events\Dispatchable;

class CourseDeletedEvent
{
    use Dispatchable;

    public function __construct(Course $course)
    {
        // Cascade deletion one step downwards.
        optional($course->position)->delete();
    }
}
