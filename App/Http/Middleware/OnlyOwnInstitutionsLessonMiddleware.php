<?php

namespace App\Http\Middleware;

use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyOwnInstitutionsLessonMiddleware extends OnlyOwnInstitutionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $this->requestParameterChangeCheck(
            $request->lesson,
            'lesson'
        );

        $this->preventUnauthorizedModification(
            $request->lesson->course->institution,
            'lesson',
            $request->lesson->id
        );

        return $next($request);
    }
}
