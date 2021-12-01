<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\SoftDeletes;

trait TrashTrait
{
	use softDeletes;

	public function scopeByTrash($query, $alias='')
	{
	    if(isset($_GET['trash']) && !empty($_GET['trash'])){
	        return $query->where($alias.'trashed_status', 0)->onlyTrashed();
	    }
	    else{
	        return $query->whereNull($alias.'trashed_status');
	    }
	    return $query;
	}

	public function scopePage($query, $paginate=null, $alias='')
	{
	    return $query->latest($alias.'updated_at')->ByTrash($alias)->paginate($paginate??PAGINATE);
	}

	public function setDeletedAtAttribute()
	{
		$this->attributes['trashed_status'] = "0";
	}

	public function scopeSoftTrash($query)
	{
	    $query->update([
	        'trashed_status'=>0,
	        'deleted_at'=>now(),
	    ]);
	}

	public function scopeRestoreFromTrash($query)
	{
	    $query->onlyTrashed()->update([
	        'trashed_status'=>null,
	        'deleted_at'=>null,
	    ]);
	}

	public function scopeLocaleTrash($query, $locale_id)
	{
		if(!is_null($locale_id)){
			$trans = [
				'en'=>'ar',
				'ar'=>'en',
			];
			$locale = $trans[app()->getLocale()];
			$locale_id = json_decode($locale_id)->$locale??null;
		    $query->where('id', $locale_id)->SoftTrash();
		}
	}

	public function scopeRestoreFromLocaleTrash($query, $locale_id)
	{
		if(!is_null($locale_id)){
			$trans = [
				'en'=>'ar',
				'ar'=>'en',
			];
			$locale = $trans[app()->getLocale()];
			$locale_id = json_decode($locale_id)->$locale??null;
		    $query->where('id', $locale_id)->RestoreFromTrash();
		}
	}
}
