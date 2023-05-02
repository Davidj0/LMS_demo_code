<?php

namespace App\Events;

use App\Facades\ResourceService;
use App\Models\LearningMaterialAudio;
use Illuminate\Foundation\Events\Dispatchable;

class LearningMaterialAudioDeletingEvent
{
    use Dispatchable;

    public function __construct(LearningMaterialAudio $learningMaterialAudio)
    {
        $course = $learningMaterialAudio->learningMaterial->lessons->first()->course;

        ResourceService::deleteResourceAndSetFilesToMediaLibraryOnlyStatusIfNeeded(
            $learningMaterialAudio->audioResource,
            $course
        );

        if ($learningMaterialAudio->imageResource) {
            ResourceService::deleteResourceAndSetFilesToMediaLibraryOnlyStatusIfNeeded(
                $learningMaterialAudio->imageResource,
                $course
            );
        }

        optional($learningMaterialAudio->heading)->delete();

        $learningMaterialAudio->learningMaterial->lessons()->detach();
    }
}