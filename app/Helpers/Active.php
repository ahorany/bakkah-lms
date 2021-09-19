<?php

namespace App\Helpers;
use App\Infastructure;

class Active {

	public static $namespace = 'admin';
	public static $folder = null;

	static function Index($array=[]){
		// dd($array['post_type']);
	    if(isset($array['post_type'])) {
            self::Link($array['post_type']);
        }
		$array = array_merge($array, [
			'folder'=>self::$folder,
        ]);
        if(self::$namespace) {
			// dump(self::$namespace.'.'.self::$folder.'.index');
            return view(self::$namespace.'.'.self::$folder.'.index', $array);
        }else {
            return view(self::$folder.'.index', $array);
        }
	}

	static function Create($array=[]){
	    $post_type = GetPostType('post');
		$trash = GetTrash();
		$array = array_merge($array, [
			'post_type'=>$post_type,
			'trash'=>$trash,
			'folder'=>self::$folder,
			'origin_id'=>Get('origin_id'),
			'locale'=>Get('locale'),
		]);
        if(self::$namespace) {
            return view(Active::$namespace.'.'.self::$folder.'.create', $array);
        }else {
            return view(self::$folder.'.create', $array);
        }
	}

	static function Edit($array=[]){
        // $post_type = GetPostType();
        $eloquent = $array['eloquent'];
        $post_type = isset($array['post_type']) ? $array['post_type'] : $eloquent->post_type ;
		$array = array_merge($array, [
			'post_type'=>$post_type,
			'folder'=>self::$folder,
        ]);

        /*if($post->locale != app()->getLocale()){
    	    return redirect()->route('admin.'.self::$folder.'.edit', [
    	        'post'=>2,
    	        'post_type'=>$post_type
    	    ]);
        	// if(!is_null(GetValueByLangNullable($post->locale_id)))
        	{
	        	dump($post->locale);
	        	dump($post->locale_id);
	        	dump(GetValueByLangNullable($post->locale_id));
	        }
        }*/
        // if(!is_null(GetValueByLangNullable($post->locale_id))){
        //     return redirect()->route('admin.'.self::$folder.'.edit', [
        //         'post'=>GetValueByLangNullable($post->locale_id),
        //         'post_type'=>$post_type
        //     ]);
        // }
        if(self::$namespace) {
            return view(Active::$namespace.'.'.self::$folder.'.edit', $array);
        }else {
            return view(self::$folder.'.edit', $array);
        }
	}

	static function Flash($title, $msg=null, $class='success'){
		if(!is_null($msg)){
			session()->flash('title', $title);
			session()->flash('msg', $msg);
			session()->flash('class', $class);
		}
	}

	static function Inserted($title, $array=[]){

        self::Flash($title, __('flash.inserted'), 'success');
        if(self::$namespace) {
            return redirect()->route(Active::$namespace.'.'.self::$folder.'.index', $array);
        }else {
            return redirect()->route(self::$folder.'.index', $array);
        }
	}

	static function Back($title, $msg=null, $class='success'){
	    self::Flash($title, $msg, $class);
        return back();
	}

	static function Updated($title){
	    return self::Back($title, __('flash.updated'));
	}

	static function Deleted($title){
		return self::Back($title, __('flash.deleted'), 'danger');
	}

	static function Restored($title){
		return self::Back($title, __('flash.restored'));
	}

	static function Link($post_type, $type='aside'){

		$route_name = request()->route()->getName();

        self::LinkForget();
		// dd($route_name);
        if(request()->has('group_slug') && !empty(request()->group_slug)){
            $post_type .= '-'.request()->group_slug;
        }
		$infastructure = Infastructure::where('type', $type)
		->where('post_type', $post_type)
        ->where('route_name', $route_name)
        ->first();

		if(isset($infastructure->id)){
			session()->put('infastructure_id', $infastructure->id);
			session()->put('infastructure__title', $infastructure->trans_title);
			session()->put('infastructure_parent_id', $infastructure->parent_id);
			session()->put('infastructure__icon', $infastructure->icon);

			//
			$infastructure2 = Infastructure::where('type', $type)
			->find($infastructure->parent_id);
			if($infastructure2){
				session()->put('infastructure_parent_id2', $infastructure2->parent_id);
			}
			else{
				session()->pull('infastructure_parent_id2');
			}
		}
		else {
			dump('You Must Define post_type');
		}
    }

    static function LinkForget(){
        if(session()->has('infastructure_id'))
		{
			session()->forget('infastructure_id');
			session()->forget('infastructure_parent_id');
			session()->forget('infastructure__title');
			session()->forget('infastructure__icon');

			if(session()->has('infastructure_parent_id2'))
				session()->forget('infastructure_parent_id2');
        }
    }
}
