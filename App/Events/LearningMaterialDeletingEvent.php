<?php

namespace App\Events;

use App\Facades\AssociatedListSortingService;
use App\Models\LearningMaterial;
use Illuminate\Foundation\Events\Dispatchable;

class LearningMaterialDeletingEvent
{
    use Dispatchable;

    public function __construct(LearningMaterial $learningMaterial)
    {
        // If it is of the trivia type, just detach the learning material from the lessons.
        if ($learningMaterial->learning_material_type === 'learning_material_trivia') {
            $learningMaterial->lessons()->detach();

            return;
        }

        // Cascade deletion one step downwards.
        optional($learningMaterial->learningMaterialable)->delete();

        AssociatedListSortingService::rearrangeModelsForDeletion($learningMaterial, 'following_learning_material_id');
    }
}
