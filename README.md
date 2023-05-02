# LMS_demo_code
This codebase is part of a Learning Management System (LMS) which is a work of fiction and is intended only as sample source code to demonstrate technical skills. Please do not assume that the concepts and solutions presented here accurately reflect real-world implementations. The LMS offers Institutions to create courses which contain lessons which contain learning materials of different kinds. The codebase consists of three functional parts:

## 1. AssociatedListSortingService
Models that arrange themselves into an ordered list (like lessons of a course or learning materials of a lesson) need to be re-sorted when a model is either deleted or inserted. The methods in this service class do this by updating the database record in the column that contains the successor model's id. The class is used in the LessonDeletingEvent and LearningMaterialDeletingEvent classes.

## 2. Events
The events here are model events. They hook into the event that a model is about to be deleted (...DeletingEvent) or has been deleted (...DeletedEvent). They form a cascade in which the deletion of a model, e.g. a course, leads to the deletion of related models, e.g. its lessons and so on, as in an 'ON DELETE CASCADE' foreign key constraint. As some members of the chain were linked via a polymorphic relationship, the 'ON DELETE CASCADE' foreign key constraint could not be used on all links. Therefore, in order to have the entire delete cascade in one place, it was implemented entirely using model events.

## 3. Middleware
The middleware classes are added to the routes of an application. They prevent requests that attempt to delete, edit or create objects that do not belong to the user's institution.