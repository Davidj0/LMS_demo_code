<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AssociatedListSortingService
{
    /**
     * if a model defines an order via a following_model_id field,
     * this method will rearrange the models when one model is deleted.
     *
     * @param  Model  $model
     * @param  string  $nameOfFollowingModelColumn
     *
     * @return void
     */
    public function rearrangeModelsForDeletion(Model $model, string $nameOfFollowingModelColumn)
    {
        $modelClass = get_class($model);
        $precedingModel = $modelClass::firstWhere($nameOfFollowingModelColumn, $model->id);
        if ($precedingModel) {
            $presentModelsFollowingModel = $model->$nameOfFollowingModelColumn;
            $model->$nameOfFollowingModelColumn = null;
            $model->save();
            $precedingModel->$nameOfFollowingModelColumn = $presentModelsFollowingModel;
            $precedingModel->save();
        }
    }

    /**
     * if a model defines an order via a following_model_id column,
     * this method will rearrange the models, when a new model is inserted
     *
     * @param  Collection  $parentModelsChildren
     * @param  string  $nameOfFollowingModelColumn
     * @param  Model  $presentModel
     *
     * @return void
     */
    public function rearrangeModelsForInsertion(Collection $parentModelsChildren, string $nameOfFollowingModelColumn, Model $presentModel)
    {
        // return early
        /// using this method for lessons, the present lesson is already part of the parentModelsChildren (i.e. of the course's lessons)
        /// using this method for learningMaterials, the present learningMaterial is not yet part of the parentModelsChildren (i.e. of the lesson's learningMaterials)
        if (
            ($parentModelsChildren->contains($presentModel) &&
            $parentModelsChildren->count() == 1) ||
            (! $parentModelsChildren->contains($presentModel) &&
             $parentModelsChildren->count() < 1)
        ) {
            return;
        }

        $presentModelsSiblings = $parentModelsChildren->diff([$presentModel]);
        $lastSibling = $presentModelsSiblings->sortByDesc('id')->first();
        if ($lastSibling) {
            $lastSibling->$nameOfFollowingModelColumn = $presentModel->id;
            $lastSibling->save();
        }
    }
}
