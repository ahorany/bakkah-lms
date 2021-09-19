<?php

namespace App\Traits;

use App\Constant;
use App\Models\Admin\PostMorph;

Trait PostMorphTrait
{
	public function postMorph()
	{
	    return $this->morphOne(PostMorph::class, 'postable');
	}

	public function postMorphs()
	{
	    return $this->morphMany(PostMorph::class, 'postable');
    }

    public static function SetMorph($id){

		$this_query = self::find($id);
		$query = self::find(GetBasicId($this_query->locale_id??$this_query->id));

		if(!is_null(request()->constant))
		{
			foreach(request()->constant as $constant){
			    $query->postMorphs()->firstOrCreate([
			        'constant_id'=>$constant,
			        'locale'=>$query->locale??'en',
			    ]);
			}
			$query->postMorphs()
                ->whereNotIn('constant_id', request()->constant)
                ->whereNull('table_id')
                ->delete();
		}
		else {
			$query->postMorphs()->whereNull('table_id')->delete();
		}
    }

    public static function AddMorph($id, $constant_ids=[])//$parent_id=25
    {
        $course = self::find($id);
        $constants = Constant::whereIn('id', $constant_ids)->get();
        foreach($constants as $constant){
            $course->postMorphs()->create([
                'created_by'=>auth()->user()->id,
                'updated_by'=>auth()->user()->id,
                'constant_id'=>$constant->id,
                'table_id'=>$constant->parent_id,
            ]);
        }
    }

}
