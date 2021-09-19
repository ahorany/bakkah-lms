<?php
namespace App\Models\Training;

use App\Models\Admin\Partner;
use App\Models\Training\Question;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use App\Traits\JsonTrait;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use UserTrait, TrashTrait, JsonTrait;

    protected $guarded = [];

    public function partners(){
        return $this->belongsTo(Partner::class);
    }

    public function questions(){
        return $this->hasMany(Question::class, 'master_id');
    }

    public static function GetQuestions($course){

        $partner_id = $course->partner_id??null;
        $exam = Exam::where('partner_id', $partner_id)->first();
        $exam_id = null;
        $questions = null;
        if(!is_null($exam))
        {
            $exam_id = $exam->id;
            $questions = Question::where('master_id', $exam_id)
            ->with(['exams'])
            ->orderby('order')
            ->get();
        }
        return [
            'exam_id'=>$exam_id,
            'questions'=>$questions,
        ];
    }

}
