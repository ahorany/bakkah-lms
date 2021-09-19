<?php

namespace App\Http\Controllers\Training;
use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\OptionRequest;
use App\Models\Training\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'options';
    }

    public function index()
    {
        $trash = GetTrash();
        $post_type = GetPostType();

        $options = Option::whereNotNull('id')->page();
        $count = $options->count();

        return Active::Index(compact('options', 'trash', 'count', 'post_type'));
    }

    public function create()
    {
        return Active::Create(['post_type'=>null]);
    }

    public function store(OptionRequest $request)
    {
        $validated = $this->Validated($request->validated());

        $option = Option::create($validated);

        return Active::Inserted($option->trans_title, ['post_type'=>null]);
    }

    public function edit(Option $option)
    {
        return Active::Edit(['eloquent'=>$option]);
    }

    public function update(OptionRequest $request, Option $option)
    {
        $validated = $this->Validated($request->validated());
        $option->update($validated);

        return Active::Updated($option->trans_title);
    }

    public function destroy(Option $option)
    {
        $option->SoftTrash($option);
        return Active::Deleted($option->trans_title);
    }

    public function restore($option){
        Option::where('id', $option)->RestoreFromTrash();
        $option = Option::where('id', $option)->first();
        return Active::Restored($option->trans_title);
    }

    private function Validated($validated)
    {
        $validated['title'] = null;
        $validated['excerpt'] = null;

        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }
}
