<?php

namespace App\Traits;
use Intervention\Image\Facades\Image;
use App\Models\Training\Upload;
use Illuminate\Support\Facades\Mail;

Trait ImgTrait
{
	public static function create(array $attributes = [])
	{
	    $model = static::query()->create($attributes);
	    static::query()->UploadFile($model);
	    return $model;
	}

	public function upload(){
    	return $this->morphOne(Upload::class, 'uploadable');
    }

    public function uploads(){
    	return $this->morphMany(Upload::class, 'uploadable');
    }

    private function SaveFileToFolders($name='file')
    {
    	$fileName = null;
    	if(request()->hasFile($name))
    	{
    	    // dd($name);
    		$fileName = $this->NameManipulation($fileName, $name);
    		$this->SaveFull($fileName, $name);
    		$this->SaveThumb($fileName, $name, ['size_x'=>100, 'path'=>'upload/thumb100/']);
    		$this->SaveThumb($fileName, $name, ['size_x'=>160, 'path'=>'upload/thumb160/']);
    		$this->SaveThumb($fileName, $name, ['size_x'=>200, 'path'=>'upload/thumb200/']);
    		$this->SaveThumb($fileName, $name, ['size_x'=>300, 'path'=>'upload/thumb300/']);
    		$this->SaveThumb($fileName, $name, ['size_x'=>450, 'path'=>'upload/thumb450/']);
    		// $this->SaveThumb($fileName, $name, ['size_x'=>450, 'size_y'=>250, 'path'=>'upload/thumb450_250/']);
    	}
    	return $fileName;
    }

	public function scopeUploadFile($query, $post, $array=[], $name='file'){

		$method = $array['method']??'create';
		$post_type = $array['post_type']??'image';
		$locale = $array['locale']??'en';
		$upload_title = $array['upload_title']??'upload_title';
		$upload_excerpt = $array['upload_excerpt']??'upload_excerpt';
		$upload_caption = $array['upload_caption']??'upload_caption';
		$exclude_img = $array['exclude_img']??'exclude_img';

		$eloquent = $query->where('id', $post->id)->first();

		$fileName = $this->SaveFileToFolders($name);

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
		    		$this->UnLinkImage($name, $eloquent->upload()->where('post_type', $post_type)->first()->file);
		    		$eloquent->uploads()->where('post_type', $post_type)->updateOrCreate([], $args);
				}else if(isset($args['file'])){
					$eloquent->uploads()->create($args);
				}
		    	// dd($args);
			    // $eloquent->uploads()->updateOrCreate([], $args);
			}
		}

		return $fileName;
	}

	private function NameManipulation($fileName=null, $name='file'){

		$fileName = date('Y-m-d-H-i-s') . '_' . trim(request()->$name->getClientOriginalName());
        $fileName = str_replace(' ','_',$fileName);
        $fileName = str_replace(['(',')'],'_',$fileName);
		$fileName = trim(strtolower($fileName));
		return $fileName;
	}

	// private function SaveFull($fileName=null, $name='file'){
	// 	$image = Image::make(request()->$name);
	// 	$image->save(public_path('upload/full/') . $fileName);
	// 	return $image;
	// }

    private function SaveFull($fileName=null, $name='file'){
	    $filePath = public_path('upload/full/') . $fileName;

		$image = Image::make(request()->$name);
        $image->save(public_path('upload/full/') . $fileName);

        try{
            $this->apiImage($filePath,$fileName);
        }catch (\Exception $e){
            // send email to administrator
            Mail::raw('There is an issue in Api of image compress, contact the developer ASAP! in  this file app\Traits\ImgTrait.php in this apiImage function.', function ($message) {
                $message->to('hsalah@bakkah.net.sa')
                  ->subject('ImgTrait issue');
              });
        }

		return $image;
	}

	private function apiImage($filepath,$fileName){
        //Compress Image Code Here
        $mime = mime_content_type($filepath);
        $output = new \CURLFile($filepath, $mime, $fileName);
        $data = ["files" => $output];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/?qlty=80');
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = curl_error($ch);
        }
        curl_close ($ch);

        $arr_result = json_decode($result);

// store the optimized version of the image
        $ch = curl_init($arr_result->dest);
        $fp = fopen($filepath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

	private function SaveThumb($fileName=null, $name='file', $array=[]){

        $filePath = public_path($array['path']) . $fileName;
		$size_y = $array['size_y']??null;
		$image = Image::make(request()->$name)->resize($array['size_x'], $size_y, function ($constraint) {
		    $constraint->aspectRatio();
		});
		$image->save(public_path($array['path']) . $fileName);

        try{
            $this->apiImage($filePath,$fileName);
        }catch (\Exception $e){
            // send email to administrator
            Mail::raw('There is an issue in Api of image compress, contact the developer ASAP! in  this file app\Traits\ImgTrait.php in this apiImage function.', function ($message) {
                $message->to('hsalah@bakkah.net.sa')
                  ->subject('ImgTrait issue');
              });
        }
	}

	private function UnLinkImage($name, $image){
		if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
		{
			$this->UnLinkImg($image);
		}
	}

	private function UnLinkImg($image){
		if(file_exists(public_path('/upload/full/') . $image)){
			unlink(public_path('/upload/full/') . $image);
			unlink(public_path('/upload/thumb100/') . $image);
			unlink(public_path('/upload/thumb160/') . $image);
			unlink(public_path('upload/thumb200/') . $image);
			unlink(public_path('/upload/thumb300/') . $image);
			unlink(public_path('/upload/thumb450/') . $image);
		}
	}
}
