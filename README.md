# LMS_demo_code
This codebase is part of a Learning Management System (LMS) which is a work of fiction and is intended only as sample source code to demonstrate technical skills. Please do not assume that the concepts and solutions presented here accurately reflect real-world implementations.


## 1. AssociatedListSortingService
Models that arrange themselves into an ordered list by defining a successor model for each model need to be re-sorted when a model is either deleted or inserted. The methods in this service class do this by updating the database record in the column that contains the successor model's id if necessary. The class is used in the LessonDeletingEvent and LearningMaterialDeletingEvent classes to reorder lessons and learning materials.
