<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Illuminate\Support\Facades\Auth;

class OnlyOwnInstitutionMiddleware
{
    /**
     * Warning message if the parameter name relied on by the middleware is changed in code.
     *
     * @param $routeParameter
     * @param  string  $routeParameterName
     */
    protected function requestParameterChangeCheck(
        $routeParameter,
        string $routeParameterName
    ) {
        if (! isset($routeParameter)) {
            return abort(403, 'the route parameter "'.$routeParameterName.'" must exist'
                .' for the middleware to work.');
        }
    }

    /**
     * Check whether the institution of the object to be changed
     * is one of the current user's institutions.
     * Prevent further processing of the request if it is not.
     *
     * @param  Institution  $institutionOfToBeChangedObject
     * @param  string  $objectTypeToBeChanged
     * @param $identifierOfToBeChangedObject
     */
    protected function preventUnauthorizedModification(
        Institution $institutionOfToBeChangedObject,
        string $objectTypeToBeChanged,
        $identifierOfToBeChangedObject
    ) {
        if (! Auth::user()->institutions->pluck('id')->contains($institutionOfToBeChangedObject->id)) {
            return abort(403, 'this '.$objectTypeToBeChanged.' ('.$identifierOfToBeChangedObject
                .') does not belong to one of your institutions');
        }
    }

    /**
     * Check whether the institution of the object to be changed
     * is one for which the current user is an admin.
     * Prevent further processing of the request if it is not.
     * 
     * @param  Institution  $institutionOfToBeChangedObject
     * @param  string  $objectTypeToBeChanged
     * @param $identifierOfToBeChangedObject
     */
    protected function preventUnauthorisedModificationOfAnObjectOfWhichTheUserIsNotAdmin(
        Institution $institutionOfToBeChangedObject,
        string $objectTypeToBeChanged,
        $identifierOfToBeChangedObject
    ) {
        if ($institutionOfToBeChangedObject->users()
                ->wherePivot('role', 'admin')->first()->id != Auth::user()->id) {
            return abort(403, 'this '.$objectTypeToBeChanged.' ('.$identifierOfToBeChangedObject
                .') does not belong to the institutions of which you are admin');
        }
    }
}
