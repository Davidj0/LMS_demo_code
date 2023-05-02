<?php

namespace App\Events;

use App\Facades\AssociatedListSortingService;
use App\Facades\ResourceService;
use App\Facades\LessonService;
use App\Models\Gallery;
use App\Models\Lesson;
use Illuminate\Foundation\Events\Dispatchable;

class LessonDeletingEvent
{
    use Dispatchable;

    public function __construct(Lesson $lesson)
    {
        $course = $lesson->course;

        // Delete the gallery.
        if ($lesson->gallery) {
            foreach ($lesson->gallery->galleryImages as $galleryImage) {
                LessonService::unlinkGalleryImage($galleryImage);
            }
            $lesson->gallery->delete();
        }

        // Delete preview images and logos.
        if ($lesson->logoResource) {
            ResourceService::deleteResourceAndSetFilesToMediaLibraryOnlyStatusIfNeeded(
                $lesson->logoResource,
                $course
            );
        }
        if ($lesson->imageResource) {
            ResourceService::deleteResourceAndSetFilesToMediaLibraryOnlyStatusIfNeeded(
                $lesson->imageResource,
                $course
            );
        }

        optional($lesson->description)->delete();
        optional($lesson->heading)->delete();

        // Delete learning materials.
        // Detaching happens in the special learning material deleting event,
        // i.e. LearningMaterialAudioDeletingEvent, LearningMaterialVideoDeletingEvent, etc.
        // With foreach loop (because mass deletion does not trigger model events).
        foreach ($lesson->learningMaterials as $learningMaterial) {
            $learningMaterial->delete();
        }

        AssociatedListSortingService::rearrangeModelsForDeletion(
            $lesson,
            'following_lesson_id'
        );
    }
}
