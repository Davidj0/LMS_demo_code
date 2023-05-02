<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyOwnInstitutionsLearningMaterialMiddleware extends OnlyOwnInstitutionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $this->requestParameterChangeCheck(
            $request->learningMaterial,
            'learningMaterial'
        );

        $this->preventUnauthorizedModification(
            $request->learningMaterial->lessons->first()->course->institution,
            'learningMaterial',
            $request->learningMaterial->id
        );

        return $next($request);
    }
}
