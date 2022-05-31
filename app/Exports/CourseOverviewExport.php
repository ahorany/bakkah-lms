<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CourseOverviewExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($assigned_learners,$completed_learners,$learners_in_progress,$learners_not_started,$assigned_instructors,$from_user,$from_test,$from_scorm,$from_assessment,$course)
    {
        $this->assigned_learners = $assigned_learners;
        $this->completed_learners = $completed_learners;
        $this->learners_in_progress = $learners_in_progress;
        $this->learners_not_started = $learners_not_started;
        $this->assigned_instructors = $assigned_instructors;
        $this->from_user = $from_user;
        $this->from_test = $from_test;
        $this->from_scorm = $from_scorm;
        $this->from_assessment = $from_assessment;
        $this->course = $course;

    }
    public function sheets(): array
    {
        return [

            new OverviewExportC($this->course[0]->id,$this->assigned_learners,$this->completed_learners,$this->learners_in_progress,$this->learners_not_started,$this->assigned_instructors),
            new UsersExport($this->from_user,$this->course[0]->id,$this->course[0]->training_option_id,null,1),
            new CoursesTestsExport($this->from_test,$this->course[0]->id,null,1),
            new CoursesScormsExport($this->from_scorm,$this->course[0]->id,null,1),
            new AssessmentExport($this->from_assessment,$this->course[0]->id,null,$this->course[0]->training_option_id,null,1),

        ];
    }

}


