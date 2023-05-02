# LMS_demo_code
This codebase is part of a Learning Management System (LMS) which is a work of fiction and is intended only as sample source code to demonstrate technical skills. Please do not assume that the concepts and solutions presented here accurately reflect real-world implementations.

## 1. AssociatedListSortingService
Models that arrange themselves into an ordered list by defining a successor model for each model need to be re-sorted when a model is either deleted or inserted. The methods in this service class do this by updating the database record in the column that contains the successor model's id if necessary. The class is used in the LessonDeletingEvent and LearningMaterialDeletingEvent classes to reorder lessons and learning materials.

## 2. Events
The events here are model events. They hook into the event that a model is about to be deleted (...DeletingEvent) or has been deleted (...DeletedEvent). They form a cascade in which the deletion of one model leads to the deletion of a related model and so on, as in an 'ON DELETE CASCADE' foreign key constraint. As some members of the chain were linked via a polymorphic relationship, the 'ON DELETE CASCADE' foreign key constraint could not be used on all links. Therefore, in order to have the entire delete cascade in one place, it was implemented entirely using model events.
