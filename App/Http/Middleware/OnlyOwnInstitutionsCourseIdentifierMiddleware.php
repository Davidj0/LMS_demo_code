<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyOwnInstitutionsCourseIdentifierMiddleware extends OnlyOwnInstitutionMiddleware
{
    public function handle(Request $request, Closure $next, $requiredInstitutionRole = null)
    {
        $this->requestParameterChangeCheck(
            $request->courseIdentifier,
            'courseIdentifier'
        );

        if ($requiredInstitutionRole === 'admin') {
            $this->preventUnauthorisedModificationOfAnObjectOfWhichTheUserIsNotAdmin(
                Course::whereIdentifier($request->courseIdentifier)->firstOrFail()->institution,
                'courseIdentifier',
                $request->courseIdentifier
            );
        }

        $this->preventUnauthorizedModification(
            Course::whereIdentifier($request->courseIdentifier)->firstOrFail()->institution,
            'courseIdentifier',
            $request->courseIdentifier
        );

        return $next($request);
    }
}
