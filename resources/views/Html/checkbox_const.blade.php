<?php
$Infastructure = \App\Infastructure::where('post_type', $const_type)
  ->where('route_name', 'admin.constants.index')->first();
?>
@if(isset($Infastructure->icon))
<div class="card card-default mb-2">
  <div class="card-header"><i class="{{$Infastructure->icon}}"></i> {{$Infastructure->trans_title}}</div>
  <div class="card-body">
  	<?php
    $constants = \App\Constant::where('post_type', $const_type)->get();

    if(isset($object))
    {
        $locale_id = $eloquent->locale_id??null;
        $locale_id = Get('origin_id')??$locale_id;
        $origin_id = GetBasicId($object->locale_id??$locale_id);

        if(!is_null($origin_id))
        {
//            $morph = $object->getMorphClass();
//            dd($origin_id);
            $eloquent = $object::find($origin_id);
//            $eloquent = \App\Models\Admin\Post::find($origin_id);
        }
    }
    ?>
  	<ul class="list-unstyled">
  	@foreach($constants as $constant)
  		<li>
  			<?php
  			$value = 0;
  			if(isset($eloquent)){
                $value = $eloquent->postMorph()->where('constant_id', $constant->id)->first()->id??0;
  			}
  			?>
  			<div>
                {!!Builder::CheckBox($constant->id, $value, [
                    'title'=>$constant->trans_name,
                    'db_trans'=>true,
                    'input_name'=>'constant[]',
                ])!!}
            </div>
  		</li>
  	@endforeach
  	</ul>
  </div>
</div>
@endif
