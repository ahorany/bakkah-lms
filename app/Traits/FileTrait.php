<?php

namespace App\Traits;
use App\Models\Training\Upload;

Trait FileTrait
{
	public static function create(array $attributes = [])
	{
	    $model = static::query()->create($attributes);
//	    static::query()->UploadFile($model)]);
	    return $model;
	}

	public function upload(){
    	return $this->morphOne(Upload::class, 'uploadable');
    }

    public function uploads(){
    	return $this->morphMany(Upload::class, 'uploadable');
    }


	public function scopeUploadFile($query, $post, $array=[], $name='file'){

		$method = $array['method']??'create';
		$post_type = $array['post_type']??'file';
		$locale = $array['locale']??'en';
		$upload_title = $array['upload_title']??'upload_title';
		$upload_excerpt = $array['upload_excerpt']??'upload_excerpt';
		$upload_caption = $array['upload_caption']??'upload_caption';
		$exclude_img = $array['exclude_img']??'exclude_img';
        $folder_path = $array['folder_path']??public_path('upload/files');

        $fileName = null;

		$eloquent = $query->where('id', $post->id)->first();

		if(request()->$name){
            $fileName = $this->NameManipulation($fileName, $name);
            $extension = request()->$name->getClientOriginalExtension();
            request()->file->move($folder_path, $fileName);
        }


		$title = $_POST[$upload_title]??null;
		$excerpt = $_POST[$upload_excerpt]??null;
		$caption = $_POST[$upload_caption]??null;
        if(isset($_POST[$exclude_img])){
            $exclude_img = 1;
        }else{
            $exclude_img = 0;
        }

		$args = [
		    'title'=>$title,
		    'excerpt'=>$excerpt,
		    'caption'=>$caption,
		    'exclude_img'=>$exclude_img,
		    'extension'=>$extension,
		];

		if(!is_null($fileName) || $method == 'update'){

			if(request()->has($name)){
			    $file = request()->file($name);
			    $extension = $file->getClientOriginalExtension();
			    $file_name = $file->getClientOriginalName();

			    $args = array_merge($args, [
			    	'name'=>$file_name,
			    	'file'=>$fileName,
			    	'post_type'=>$post_type,
			    	'locale'=>$locale,
			    	'updated_by'=>auth()->user()->id,
			    ]);
			}

		    $args = array_merge($args, [
		    	'updated_by'=>auth()->user()->id,
		    ]);

		    if($method=='create'){

		    	$args = array_merge($args, ['created_by'=>auth()->user()->id,]);
		    	$eloquent->uploads()->create($args);
			}
			else if($method=='update'){

				$args = array_merge($args, ['created_by'=>auth()->user()->id,]);

				if(isset($eloquent->upload()->where('post_type', $post_type)->first()->file)){
//				    dump($eloquent->upload()->where('post_type', $post_type)->first()->file);
//		    		$this->UnLinkImage($name, $eloquent->upload()->where('post_type', $post_type)->first()->file);
		    		$eloquent->uploads()->where('post_type', $post_type)->updateOrCreate([], $args);
				}else if(isset($args['file'])){
					$eloquent->uploads()->create($args);
				}
		    	// dd($args);
			    // $eloquent->uploads()->updateOrCreate([], $args);
			}
		}
	}

	private function NameManipulation($fileName=null, $name='file'){

		$fileName = date('Y-m-d-H-i-s') . '_' . trim(request()->$name->getClientOriginalName());
        $fileName = str_replace(' ','_',$fileName);
        $fileName = str_replace(['(',')'],'_',$fileName);
		$fileName = trim(strtolower($fileName));
		return $fileName;
	}



//    private function UnLinkImage($name, $image){
//        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
//        {
//            $this->UnLinkImg($image);
//        }
//    }
//
//    private function UnLinkImg($image){
//        if(file_exists(public_path('/upload/full/') . $image)){
//            unlink(public_path('/upload/full/') . $image);
//            unlink(public_path('/upload/thumb100/') . $image);
//            unlink(public_path('/upload/thumb160/') . $image);
//            unlink(public_path('upload/thumb200/') . $image);
//            unlink(public_path('/upload/thumb300/') . $image);
//            unlink(public_path('/upload/thumb450/') . $image);
//        }
//    }



}
