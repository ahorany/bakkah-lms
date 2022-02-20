<?php
use Illuminate\Support\Facades\Gate;

class Builder {
	protected static $trash = null;
	public static $eloquent = null;
	protected static $folder = 'posts';
	public static $object = 'post';
	protected static $getPublishName = 'publish';
	protected static $getIndex = 'index';
	protected static $post_type = null;
	protected static $btn_param = [];
	public static $prefix = 'admin.';
	public static $namespace = 'admin.';

	static function SetTrash($trash=null){
		self::$trash = $trash;
	}

	static function SetFolder($folder='posts'){
		self::$folder = $folder;
	}

	static function SetObject($object='post'){
		self::$object = $object;
	}

	static function SetEloquent($eloquent=null){
		self::$eloquent = $eloquent;
	}

	static function SetPublishName($name='save'){
		self::$getPublishName = $name;
	}

	static function SetIndex($name){
		self::$getIndex = $name;
	}

	static function SetPostType($post_type){
		self::$post_type = $post_type;
	}

    static function SetBtnParam($btn_param){
		self::$btn_param = $btn_param;
	}

    static function SetPrefix($prefix='admin.'){
        self::$prefix = $prefix;
    }

    static function SetNameSpace($namespace='admin.'){
        self::$namespace = $namespace;
    }

	static function Title($title, $db_trans=false, $prefix=null){
        $prefix1 = is_null($prefix)?self::$prefix:$prefix;
		return !$db_trans?__($prefix1.$title):$title;
	}

	static function SidebarHref($sidebar, $href=null, $active=null){

		$div = '';
		$args = [];
		if(!is_null($sidebar->route_param)){
          $args = array_merge($args, json_decode($sidebar->route_param, true));
        }

        if(Route::has($sidebar->route_name)){

            $href = !is_null($sidebar->route_name)?route($sidebar->route_name, $args):'#';
            $psth_to__check = str_replace(Request::root().'/',"", $href);

            $active = ($sidebar->id == session('Infrastructure_id')) ? 'active' : $active;
//            $active = ($sidebar->id== (url()->full() == route($sidebar->route_name, $args)) ) && (url()->full() != CustomRoute('user.home'))  ? 'active' : $active;
    //        $active = (session('Infrastructure_id') != 'user') ? 'active' : '';
    //        dump(session('Infrastructure_id'));

            $div .= '<a href="'.$href.'" class="nav-link '.$active.'">';

                // $div .= '<i class="nav-icon '.($sidebar->icon??'fas fa-chart-pie').'"></i>';

               $icon_content = '';
                if (file_exists(public_path('icons/sidebar/'.$sidebar->icon))){
                    $icon_content = file_get_contents(public_path('icons/sidebar/'.$sidebar->icon));

                }

                $div .= '<span class="sidebar-icon d-flex align-items-center">'.$icon_content.'</span>';

            //     $div .= '<svg xmlns="http://www.w3.org/2000/svg" width="40.86" height="39.254" viewBox="0 0 40.86 39.254">
            //     <path id="Path_131" data-name="Path 131" d="M40.428,11.8,20.848,22.039a.851.851,0,0,1-.787,0L.484,11.8A.854.854,0,0,1,.53,10.263L20.064.045a.855.855,0,0,1,.787,0l19.577,10.24a.855.855,0,0,1,0,1.513ZM20.457,1.764,2.719,11.043,20.454,20.32l17.739-9.278ZM.491,18.423,3.044,17.11a.853.853,0,0,1,.777,1.518L2.705,19.2l17.752,9.643L38.21,19.2l-1.117-.575a.853.853,0,0,1,.778-1.518l2.531,1.3a.854.854,0,0,1,.038,1.518L20.863,30.566a.848.848,0,0,1-.811,0L.473,19.932a.854.854,0,0,1,.017-1.509Zm0,8.533,2.553-1.313a.853.853,0,0,1,.777,1.518l-1.116.575,17.752,9.643L38.21,27.737l-1.117-.576a.853.853,0,0,1,.778-1.518l2.531,1.3a.853.853,0,0,1,.038,1.518L20.863,39.1a.848.848,0,0,1-.811,0L.473,28.466a.854.854,0,0,1,.017-1.509Z" transform="translate(-0.026 0.051)"></path>
            // </svg>';
                $div .= '<p class="m-0">';
                $div .= $sidebar->trans_title;

                if(is_null($sidebar->route_name))
                $div .= '<i class="right fas fa-angle-left"></i>';

                $div .= '</p>';
            $div .= '</a>';
        }
        return $div;
	}

    //////////////////
	static function Control($name, $title, $value=null, $array=array()){
		$attr = $array['attr']??null;
		$placeholder = $array['placeholder']??$title;

        if(isset($array['type']) && $array['type'] != 'password') {
            $value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$name:$value;
        }
        $val = old($name)??$value;

        $maxlength = '';
		if($array['type']=='text')
            $maxlength = 'maxlength="155"';
        else if($array['type']=='datetime' && !is_null($val)){
            $date = date_create($val);
            $val = date_format($date, 'Y-m-d H:i');
        }

        $passType = ($array['type'] == 'password') ? 'autocomplete="new-password"': '';
        $disabled = isset($array['disabled']) && $array['disabled'] ? "disabled" : "";

		$div = '<input '.$disabled.' '.$maxlength.' type="'.$array['type'].'" name="'.$name.'" '.$attr.' class="form-control" placeholder="'.ucfirst($placeholder).'" value="'.$val.'" '.$passType.'>';
		return $div;
	}

	static function Hidden($name, $value=null, $array=array()){
		$array = array_merge($array, array('type'=>'hidden'));
		return self::Control($name, null, $value, $array);
    }

    static function SelectForCheckBox($name, $value=0, $array=array()){
        return self::CheckBox($name, request()->$name?1:0, $array);
    }

	static function CheckBox($name, $value=0, $array=array()){
		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$title=isset($array['title'])?$array['title']:$name;
        $input_name=isset($array['input_name'])?$array['input_name']:$name;
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';

        $value=(!is_null(self::$eloquent)&&$value==0)?self::$eloquent->$name:$value;
		$val = old($name)??$value;
        $checked=($value!=0)?'checked="checked"':null;
		$label = '<div class="custom-control custom-checkbox">';
          $label .= '<input class="custom-control-input" type="checkbox" name="'.$input_name.'" id="'.$name.'" value="'.$name.'" '.$checked.'>';
          $label .= '<label for="'.$name.'" class="custom-control-label">'.self::title($title, $db_trans).' '.$validation.'</label>';
        $label .= '</div>';

        // $label .= '<div data-container="'.$name.'"></div>';
		return $label;
	}

	static function CheckBox1($name, $title, $value=0, $array=array()){
		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$checked=null;
		if((is_numeric($value) && $value!=0) || !is_null($value)){
			$checked = 'checked="checked"';
			$value = 1;
		}

		$col = $array['col']??'col-md-12';
		$label = '<div class="'.$col.'">';

			$array = array_merge($array, [
				'title'=>$title,
				'db_trans'=>true,
			]);
			$label .= self::CheckBox($name, $value, $array);

        $label .= '</div>';
		return $label;
	}

	static function Radio($name, $value=0, $array=array()){
		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$default=isset($array['default'])?$array['default']:0;
		$id=isset($array['id'])?$array['id']:null;
		$title=isset($array['title'])?$array['title']:null;
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';
		$checked = null;
		// $value!=0 ||
		if($value==$id){
			$checked = 'checked="checked"';
		}

		$label = '<div class="custom-control custom-radio">';
          $label .= '<input class="custom-control-input" type="radio" name="'.$name.'" id="'.$name.'_'.$id.'" value="'.$id.'" '.$checked.'>';
          $label .= '<label for="'.$name.'_'.$id.'" class="custom-control-label">'.self::title($title, $db_trans).' '.$validation.'</label>';
        $label .= '</div>';

        // $label .= '<div data-container="'.$name.'"></div>';
		return $label;
	}

	static function Input($name, $title, $value=null, $array=array()){

		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$title = self::title($title, $db_trans);
		$col = $array['col']??'col-md-12';
		$type = $array['type']??'text';
		$attr = $array['attr']??null;
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';
		$div = '<div class="'.$col.' '.$name.'">';
			$div .= '<div class="form-group">';
            $div .= '<label>'.$title.' '.$validation.'</label>';

			$array = array_merge($array, array('type'=>$type, 'attr'=>$attr));
			$div .= self::Control($name, $title, $value, $array);

			$div .= '</div>';
		$div .= '</div>';
		return $div;

    }

	static function Number($name, $title, $value=null, $array=array()){

		$array = array_merge($array, array('type'=>'number', 'step'=>"any", 'attr'=>'@keypress="isNumber($event)"'));
		return self::Input($name, $title, $value, $array);
	}

	static function Password($name, $title, $value=null, $array=array()){
		return self::Input($name, $title, $value, $array);
	}

	static function Date($name, $title, $value=null, $array=array()){
		$args = array_merge($array, [
			'attr'=>'data-date="date" autocomplete="off" ',
        ]);
		return self::Input($name, $title, $value, $args);
    }

    static function Date1($name, $title, $value=null, $array=array()){
		$args = array_merge($array, [
			'type'=>'date',
			'attr'=>'data-date="date" autocomplete="off" ',
        ]);
		return self::Input($name, $title, $value, $args);
    }

    static function DateTime($name, $title, $value=null, $array=array()){
		$args = array_merge($array, [
            'attr'=>'data-date="datetime" autocomplete="off" ',
            'type'=>'datetime',
        ]);
		return self::Input($name, $title, $value, $args);
	}

	static function Time($name, $title, $value=null, $array=array()){

		$db_trans = $array['db_trans']??false;
		$title = self::title($title, $db_trans);
		$col = $array['col']??'col-md-12';
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';
		$div = '';
		$div = '<div class="'.$col.' '.$name.'">';
			$div .= '<div class="form-group">';
				$div .= '<label>'.$title.' '.$validation.'</label>';

				$div .= self::TimeControl($name, $title, $value, $array);
				$div .= '</div>';
			$div .= '</div>';
		$div .= '</div>';
		return $div;
	}

	static function TimeControl($name, $title, $value=null, $array=array()){

		$attr = $array['attr']??'';
		$value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$name:$value;
		$val = old($name)??$value;

		$div = '';
		$div .= '<div class="input-group date timepicker" id="'.$name.'" data-target-input="nearest">';
		$div .= '<input type="text" name="'.$name.'" class="form-control datetimepicker-input" data-target="#'.$name.'" value="'.$val.'" '.$attr.'>';
		$div .= '<div class="input-group-append" data-target="#'.$name.'" data-toggle="datetimepicker">';
		$div .= '<div class="input-group-text"><i class="far fa-clock"></i></div>';
		$div .= '</div>';
		return $div;
	}

	static function File($name, $title, $value=null, $array=array()){
		$array = array_merge($array, array('type'=>'file'));
		return self::Input($name, $title, $value, $array);
	}

	static function Excerpt($name='excerpt', $title='excerpt', $value=null, $array=array()){
		return self::Textarea($name, $title, $value, [
	        'row'=>3,
	        'attr'=>'maxlength="155"',
	      ]);
	}

	static function Textarea($name, $title, $value=null, $array=array()){

		$attr = $array['attr']??null;
		$col = $array['col']??'col-md-12';
		$tinymce = $array['tinymce']??null;
		$row = $array['row']??15;
        $db_trans=isset($array['db_trans'])?$array['db_trans']:false;
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';
        $disabled = isset($array['disabled']) && $array['disabled'] ? "disabled" : "";

		$value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$name:$value;
		$val = old($name)??$value;

		$div = '<div class="'.$col.'">';
			$div .= '<div class="form-group">';
            if($title != '')
			    $div .= '<label>'.self::title($title, $db_trans).' '.$validation.'</label>';
			$div .= '<textarea name="'.$name.'" class="form-control '.$tinymce.'" rows="'.$row.'" placeholder="'.self::title($title, $db_trans).'" '.$attr.' '.$disabled.'>'.$val.'</textarea>';
			$div .= '</div>';
		$div .= '</div>';
		return $div;
	}

	static function Tinymce($name, $title, $value=null, $array=array()){
		$array = array_merge($array, array('tinymce'=>'tinymce'));
		//$array = array_merge($array, array('tinymce'=>'ckeditor'));
		return self::Textarea($name, $title, $value, $array);
    }

    static function SelectForSearch($name, $lists=null, $array=array()){
        return self::Select($name, $name, $lists, request()->$name??-1, $array);
    }

	static function Select($name, $title, $lists=null, $value=null, $array=array()){

		if(is_null($lists))
			return null;

		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$col = $array['col']??'col-md-12';
        $title = isset($array['title'])?$array['title']:self::title($title, $db_trans);
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';

		$div = '';
		$div .= '<div class="'.$col.'">';
			$div .= '<div class="form-group">';
				$div .= '<label>'.$title.' '.$validation.'</label>';
				$div .= self::SelectBody($name, $title, $lists, $value, $array);
			$div .= '</div>';
        $div .= '</div>';
		return $div;
	}

	static function SelectBody($name, $title, $lists=null, $value=null, $array=array()){

//		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$col = $array['col']??'col-md-12';
		$cls = $array['cls']??null;
		$multiple = $array['multiple']??null;
        $disabled = isset($array['disabled']) && $array['disabled'] ? "disabled" : "";
//		$title = isset($array['title'])?$array['title']:self::title($title, $db_trans);
//		if(isset($array['title_multichoice'])){
//			$title = $array['title_multichoice'];

//		}
        $model_title = isset($array['model_title'])?$array['model_title']:'trans_name';
		$value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$name:$value;
		$val = old($name)??$value;

		$div = '';
        $div .= '<select '.$disabled.' name="'.$name.'" class="form-control '.$cls.'" '.$multiple.'>';
            if($name!='coin_id_insights'){
                $div .= '<option value="-1">'.__('admin.choose_value').'</option>';
            }
			foreach($lists as $list)
			{
				$selected=($val==$list->id)?'selected="selected"':'';
				$div .= '<option value="'.$list->id.'" '.$selected.'>'.$list->$model_title.'</option>';
			}
		$div .= '</select>';
		return $div;
	}

    static function Select2($name, $title, $lists=null, $value=null, $array=array()){

		if(is_null($lists))
			return null;

		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
		$col = $array['col']??'col-md-12';
        $title = isset($array['title'])?$array['title']:self::title($title, $db_trans);
        $validation=isset($array['validation'])?'<span class="text-danger">*</span>':'';

		$div = '';
		$div .= '<div class="'.$col.'">';
			$div .= '<div class="form-group">';
				$div .= '<label>'.$title.' '.$validation.'</label>';
				$div .= self::Select2Body($name, $title, $lists, $value, $array);
			$div .= '</div>';
        $div .= '</div>';
		return $div;
	}

    static function Select2Body($name, $title, $lists=null, $value=null, $array=array()){

        //		$db_trans=isset($array['db_trans'])?$array['db_trans']:false;
                $col = $array['col']??'col-md-12';
                $cls = $array['cls']??null;
                $multiple = $array['multiple']??null;
                $disabled = isset($array['disabled']) && $array['disabled'] ? "disabled" : "";
        //		$title = isset($array['title'])?$array['title']:self::title($title, $db_trans);
        //		if(isset($array['title_multichoice'])){
        //			$title = $array['title_multichoice'];

        //		}
                $model_title = isset($array['model_title'])?$array['model_title']:'trans_name';
                $value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$name:$value;
                $val = old($name)??$value;

                $div = '';
                $div .= '<select '.$disabled.' name="'.$name.'" class="form-control '.$cls.' select2 select2-hidden-accessible" '.$multiple.'>';
                    if($name!='coin_id_insights'){
                        $div .= '<option value="-1">'.__('admin.choose_value').'</option>';
                    }
                    foreach($lists as $list)
                    {
                        if(is_array($val)) {
                            $selected=( in_array($list->id, $val))?'selected="selected"':'';
                        }else {
                            $selected=($val==$list->id)?'selected="selected"':'';
                        }

                        $div .= '<option value="'.$list->id.'" '.$selected.'>'.$list->$model_title.'</option>';
                    }
                $div .= '</select>';
                return $div;
            }

	static function Submit($name, $title, $class='btn-primary', $icon=null, $array=[]){
		$type = $array['type']??'submit';
        $icon_fa=$array['icon']??'fa fa-'.$icon;
		return '<button type="'.$type.'" name="'.$name.'" class=" '.$class.'"><i class="'.$icon_fa.'"></i> '.self::title($title, false, 'admin.').'</button>';
	}

	/*
	* For Table
	*/
	static function Route($name, $post_type, $array=array()){

		$args = [];
		if(!is_null($post_type))
            $args = array_merge($args , ['post_type'=>$post_type]);

        $args = self::SetGroupSlug($args);

		$array1 = array_merge($args, $array, self::$btn_param);
		return route(self::$namespace.$name, $array1);
	}

	static function Href($name, $post_type, $class, $icon, $route=null){
		$name1 = !is_null($name)?__('admin.'.$name):null;
		return '<a name="'.$name.'" href="'.$route.'" class="mr-1 '.$class.'" title="'.$name1.' '.__('admin.'.$post_type).'"><i class="fa fa-'.$icon.'"></i> '.$name1.'</a>';
	}

	static function Create($post_type){
		// $args = self::SetPage($args);
		$route = self::Route(self::$folder.'.create', $post_type);//.self::getPage();
		return self::Href('create', $post_type, 'primary', 'plus', $route);
	}

	// To define buttons
	static function TrashOrArchiveOrSchedule($post_type, $type='trash', $icon='trash'){
		$route = self::Route(self::$folder.'.'.self::$getIndex, $post_type, ['trash'=>$type]);
		$class = self::$trash==$type?'btn-sm btn-table':'white';
		return self::Href($type, $post_type, $class, $icon, $route);
	}

	static function Trash($post_type){
		return self::TrashOrArchiveOrSchedule($post_type, 'trash', 'trash');
	}

	static function Empty($post_type){
		return null;
	}

	static function Archives($post_type){
		return self::TrashOrArchiveOrSchedule($post_type, 'archive', 'archive');
	}

	static function List($post_type, $name='list', $class='btn-success', $icon='list'){

        $route = self::Route(self::$folder.'.'.self::$getIndex, $post_type);//.self::getPage();
		return self::Href($name, $post_type, $class, $icon, $route);
	}

	static function SetPage($args){
		if(isset($_GET['page'])){
			$args = array_merge($args, ['page'=>$_GET['page']]);
		}
		return $args;
    }

    static function SetGroupSlug($args){
        if(request()->has('group_slug') && !empty(request()->group_slug)){
            $args = array_merge($args, ['group_slug'=>request()->group_slug]);
        }
		return $args;
	}

	////////////////
	static function GridHref($name, $title, $id, $class, $icon, $route,$data_target = null){

        $args = [self::$object=>$id];
		if(!is_null(self::$post_type)){
			$args = array_merge($args, ['post_type'=>self::$post_type]);
        }

		$args = self::SetPage($args);
        $args = self::SetGroupSlug($args);

		$route = route(self::$namespace.self::$folder.'.'.$route, $args);
		$name1 = !is_null($name)?__('admin.'.$name):null;
        $modal = !is_null($data_target) ? ' onclick="replicate(event)" ' : '' ;
		return '<a href="'.$route.'" class="btn '.$class.' btn-table" title="'.$name1.' '.$title.'" '.$modal.'><i class="fa fa-'.$icon.'"></i> '.$name1.'</a> ';
	}

	static function GridButton($name, $title, $id, $class, $icon, $route, $trashed_status=0){
		$route = route(self::$namespace.self::$folder.'.'.$route, [$id]);
		$name1 = !is_null($name)?__('admin.'.$name):null;

		$button = '<form name="'.$name.'" data-id="'.$id.'" method="post" action="'.$route.'" style="display:inline-block;">';
		$button .= csrf_field();
		$button .= method_field(($name=='archive' || $name=='delete' || $name=='destroy')?'delete':'PATCH');
		$button .= '<input type="hidden" name="trashed_status" value="'.$trashed_status.'">';
		$button .= '<button name="'.$name.'_btn" data-id="'.$id.'" class="btn btn-sm btn-'.$class.' btn-table" title="'.$name1.' '.$title.'"><i class="fa fa-'.$icon.'"></i> '.$name1.'</button>';
		$button .= '</form>';
		return $button;
	}

	static function Reset($name, $title, $class='btn-default', $icon=null, $array=[]){
		$type = $array['type']??'reset';
		return '<button type="'.$type.'" name="'.$name.'" class="btn btn-sm '.$class.'"><i class="fa fa-'.$icon.'"></i> '.self::title($title, false, 'admin.').'</button>';
	}

	static function softDelete($title, $id){
		return self::GridButton('delete', $title, $id, 'outline-danger', 'trash', 'softDelete', 0);
	}

	static function Destroy($title, $id){
		return self::GridButton('delete', $title, $id, 'outline-danger', 'trash', 'destroy', 0);
    }

	static function Archive($title, $id){
		return self::GridButton('archive', $title, $id, 'outline-primary', 'archive', 'destroy', 1);
	}

	static function softArchive($title, $id){
		return self::GridButton('archive', $title, $id, 'outline-primary', 'archive', 'softDelete', 1);
	}

	static function Restore($title, $id){
		return self::GridButton('restore', $title, $id, 'table', 'trash-restore', 'restore', 1);
	}

	// $name, $title, $id, $class, $icon, $route, $trashed_status=0
	static function RoleDetails($title, $id){
		return self::GridHref('role_details', $title, $id, 'btn-sm btn-outline-primary', 'user-tag', 'role_details');
	}

	static function Edit($title, $id){
		return self::GridHref('edit', $title, $id, 'yellow', 'pencil', 'edit');
	}

    static function Dublicate($title, $id){
		return self::GridHref('duplicate', $title, $id, 'info-button duplicate', 'pencil', 'duplicate','#duplicateRow');
	}

    static function Replicate($title, $id){
		return self::GridHref('replicate', $title, $id, 'info-button replicate', 'pencil', 'replicate','#replicateRow');
	}

    static function Attendance($title, $id){
		return self::GridHref('attendance', $title, $id, 'btn-sm btn-outline-info', 'users', 'attendance');
	}

	static function show($title, $id){
		return self::GridHref('show', $title, $id, 'btn-sm btn-outline-primary', 'eye', 'show');
	}

	static function Open($title, $id){
		return self::GridHref('open', $title, $id, 'btn-sm btn-outline-primary', 'folder-open', 'open');
	}

	static function Trans($title, $id){
		// return self::GridHref('trans', $title, $id, 'btn-sm btn-primary', 'plus');
	}

	static function EditPassword($title, $id){
		return self::GridHref('edit_password', $title, $id, 'btn-sm btn-outline-primary', 'key', 'edit_password');
	}

	static function Tracks($title, $id){
		return self::GridHref('tracks', $title, $id, 'btn-sm btn-outline-primary', 'key', 'tracks');
	}

	static function BtnGroupTable($use_create=true, $array=array()){

		$btn = '';
		$btn .= '<span class="BtnGroupTable">';
			if(is_null(self::$trash)){
				if($use_create)
					$btn .= self::Create(self::$post_type);
			}
			else
				$btn .= self::List(self::$post_type, 'list', 'primary', 'list');
			// ////////////////////////////////////
			if(is_null(self::$trash) && count($array)==0)
				$array = ['Trash'];
			foreach($array as $key => $value){
				$btn .= self::$value(self::$post_type);
			}
		$btn .= '</span>';

		return $btn;
	}

	static function BtnGroupForm($hasBack=true){
		$btn = '<div class="card card-default form-buttons">
		  <div class="card-body">';
		$btn .= '<div class="BtnGroupForm">';
			if($hasBack)// used in users.form_old.blade.php
				$btn .= self::List(self::$post_type, 'back', 'cyan mr-1', 'arrow-left');

			if(is_null(self::$eloquent))
				$btn .= self::Submit('submit', self::$getPublishName, 'main-color', 'save');
			else{
				$btn .= self::Submit('submit', 'update', 'main-color', 'save');
			}

		$btn .= '</div>';
		$btn .= '</div>
		</div>';
		return $btn;
	}

	static function BtnGroupRows($title, $id, $array=array(), $args=array()){

		$btn = '<div class="BtnGroupRows" data-id="'.$id.'">';
		if (is_null($array)){
            $array = [];
        }else if(count($array)==0){
            $array = ['Edit', 'Destroy', 'Trans'];
        }

		if(is_null(self::$trash)){
			foreach($array as $key => $value){
				$btn .= self::$value($title, $id);
			}
		}
		else {
            $btn .= self::Restore($title, $id);
        }

		$btn .= '</div>';
		return $btn;
	}

	static function ImgRow($name, $default=null, $folder='thumb100'){
		return self::ImgShow($name, $folder, $default);
	}

	static function UploadRow($eloquent, $default=null){
		return self::ImgShow($eloquent->upload->file??null, $folder='thumb100', $default);
	}

	static function ImgForm($value=null, $file='image', $default=null, $folder='thumb200'){
		$value=(!is_null(self::$eloquent)&&is_null($value))?self::$eloquent->$file:$value;
		return self::ImgShow($value, $folder, $default);
	}

    static function UploadFormLang($value=null, $default=null, $array=[]){
        $post_type = $array['post_type']??'image';
        if(!is_null(self::$eloquent)&&is_null($value)&&isset(self::$eloquent->uploads()->where('post_type', $post_type)->first()->file)){
            $value = self::$eloquent->uploads()->where('post_type', $post_type)->first()->file;
//            $post_type = self::$eloquent->upload->where('post_type', $post_type)->post_type;
        }
        return self::ImgShow($value, $folder='thumb200', $default);
    }

	static function UploadForm($value=null, $default=null){
		$post_type = 'image';
		if(!is_null(self::$eloquent)&&is_null($value)&&isset(self::$eloquent->upload->file)){
			$value = self::$eloquent->upload->file;
			$post_type = self::$eloquent->upload->post_type;
		}
		// return self::ImgShow($value, $folder='thumb200', $default);
	}

	static function ImgShow($name, $folder='thumb300', $default=null){
		if(!is_null($name)){
			$src = CustomAsset('upload/'.$folder.'/'.$name);
			return '<img class="img-thumbnail '.$folder.'" src="'.$src.'">';
		}
		else if(!is_null($default)){
			$src = CustomAsset('upload/'.$default);
			return '<img class="img-thumbnail '.$folder.'" src="'.$src.'">';
		}
		return null;
	}

	static function Img($file_name, $title=null, $folder='thumb300', $array=array()){
		$cls = isset($array['cls'])?$array['cls']:null;
		$src = CustomAsset('upload/'.$folder.'/'.$file_name);
		return '<img src="'.$src.'" alt="'.$title.'" title="'.$title.'" class="'.$cls.'">';
	}

	static function single($title, $id, $array=array()){
		$cls = isset($array['cls'])?$array['cls']:null;
		$post_type = isset($array['post_type'])?$array['post_type']:null;
		$route_name = isset($array['route_name'])?$array['route_name']:'single';
		return '<a class="'.$cls.'" href="'.route('web.'.$route_name, [self::$object=>$id, 'post_type'=>$post_type, 'title'=>self::_replace($title)]).'" title="'.$title.'">';
	}

	static function _replace($title){
		return str_replace(' ', '-', $title);
	}

	static function _title($title){
		return ['title'=>self::_replace($title)];
	}

	static function getPDF($upload=null){
		if(!is_null($upload)){
			$href = CustomAsset('upload/pdf/'.$upload->file);
			return '<a href="'.$href.'" target="'.$upload->file.'"> <i class="far fa-file-pdf"></i> '.$upload->title.'</a>';
		}
	}

	static function PDFForm($post_type = 'pdf'){
	    $div = '';
		if(isset(self::$eloquent)){
			foreach(self::$eloquent->uploads->where('post_type', $post_type) as $upload){
				$href = CustomAsset('upload/pdf/'.$upload->file);
                if($upload->locale) {
                    $div .= strtoupper($upload->locale).':  ';
                }
				$div .= '<a class="pdf-href" href="'.$href.'" target="'.$upload->title.'"> <i class="far fa-file-pdf"></i> '.$upload->title.'</a>';
				$div .= '<br>';
			}
		}
		return $div;
	}

    static function ProfileFile($post_type){
	    $div = '';
		if(isset(self::$eloquent)){
            if(self::$eloquent->profile) {
                foreach(self::$eloquent->profile->uploads->where('post_type', $post_type) as $upload){
                    $href = CustomAsset('upload/pdf/'.$upload->file);
                    //$div .= strtoupper($upload->post_type).':  ';
                    $div .= '<a class="pdf-href" href="'.$href.'" target="'.$upload->title.'"> <i class="far fa-file-pdf"></i> '.$upload->title.'</a>';
                    $div .= '<br>';
                }
            }

		}
		return $div;
	}

    static function VideoFile($post_type){
        // dd(self::$eloquent);
	    $div = '';
		if(isset(self::$eloquent)){
			foreach(self::$eloquent->uploads->where('post_type', $post_type) as $upload){
				$href = CustomAsset('upload/video/'.$upload->file);
                // $div .= strtoupper($upload->locale).':  ';
				$div .= '<a class="pdf-href" href="'.$href.'" target="'.$upload->title.'"> <i class="far fa-file-video"></i> '.$upload->title.'</a>';
				$div .= '<br>';
			}
		}
		return $div;
	}

	static function ActivePath(){
		$path_array = explode('/', Request::path());
		$path = $path_array[count($path_array) -1];
		if($path=='trash' || $path=='archive')
		  $path = $path_array[count($path_array) -2];
		return $path;
	}

	static function ActivePathParent(){
		$path_array = explode('=', self::ActivePath());
		$path = $path_array[0];
		return $path;
	}

	static function TableAllPosts($all_posts, $_count){
		return '<span class="badge text-secondary float-right">'.$_count.' / '.$all_posts.'</span>';
	}

	static function limitTitle($title, $limit=40){
		return str_limit($title, $limit);
	}

	static function limitExcerpt($excerpt, $limit=75){
		return str_limit($excerpt, $limit);
	}

	static function GetImage($post){
		$file = null;
		if(isset($post->image)){
		    $file = $post->image;
		}
		else if(isset($post->upload->file)){
		    $file = $post->upload->file;
		}
		return $file;
	}

	static function Seo($id, $eloquent='\App\Models\Statics\Post'){
		$post = $eloquent::with('seo')->with('postkeyword.seokeyword')->find($id);
		$seokeyword_arr = array();
		$seokeyword = '';
		foreach($post->postkeyword as $postkeyword){
			$seokeyword_arr[] = $postkeyword->seokeyword->title;
		}
		$seokeyword = implode(',', $seokeyword_arr);

		return array(
			'description'=>$post->seo->description??null,
			'author'=>$post->seo->author??null,
			'keywords'=>$seokeyword,
		);
	}

	static function Content(){

		/* Main content */
		// self::setPostType($post_type);
		// self::setTrash($trash);
		return '<div class="content">
		<div class="container-fluid">
		<div class="row mx-0">
		<div class="col-12">';
	}

	static function EndContent(){
		/* End content */
		return '</div><!-- /.col-12 -->
		</div><!-- /.row -->
		</div><!-- /.container-fluid -->
		</div><!-- /.content -->';
	}

	static function get_appends ($request)
	{
		$array = [];
		foreach($request as $key => $value){

			if($key != 'page'){
				if(!is_null($value)){
					$array = array_merge($array, [
						$key => $value,
					]);
				}
			}
		}
		return $array;
	}

    static function getCertificate($upload=null){
		if(!is_null($upload)){
			$href = CustomAsset('certificates/img/'.$upload->file);
			return '<a href="'.$href.'" target="'.$upload->file.'"> <i class="far fa-file-pdf"></i> '.$upload->title.'</a>';
		}
	}
}
