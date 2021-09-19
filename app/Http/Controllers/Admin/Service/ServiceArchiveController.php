<?php

namespace App\Http\Controllers\Admin\Service;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\ServiceArchiveRequest;
use App\Models\Admin\Post;
use App\Models\Admin\Service\ServiceArchive;
use Illuminate\Http\Request;

class ServiceArchiveController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'service_archives';
    }

    public function index(){

        $trash = GetTrash();

        if(request()->has('master_id')){

            $post = Post::findOrFail(request()->master_id);

            $serviceArchives = ServiceArchive::with('user')
            ->where('master_id', $post->id)
            ->orderBy('order');
            $count = $serviceArchives->count();
            $serviceArchives = $serviceArchives->page();

            $title = $post->title;// . ' ( ' . $post->constant->trans_name . ' ) ';
            session()->put('infastructure__title', $title);
            session()->put('infastructure__icon', 'fas fa-chalkboard');
            return Active::Index(compact('serviceArchives', 'count', 'trash'));
        }
    }

    public function create(){
        return Active::Create(['object'=>Service::class, 'master_id'=>request()->master_id,]);
    }

    public function store(ServiceArchiveRequest $request){
        $validated = $this->validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['master_id'] = $request->master_id;

        $serviceArchive = ServiceArchive::create($validated);

        return Active::Inserted($serviceArchive->trans_title, [
            'master_id'=>$validated['master_id'],
        ]);
    }

    public function edit(ServiceArchive $serviceArchive){
        return Active::Edit(['eloquent'=>$serviceArchive]);
    }

    public function update(ServiceArchiveRequest $request, ServiceArchive $serviceArchive){

        $validated = $this->Validated($request->validated());

        ServiceArchive::find($serviceArchive->id)->update($validated);

        return Active::Updated($serviceArchive->trans_title);
    }

    public function destroy(ServiceArchive $serviceArchive, Request $request){
        ServiceArchive::where('id', $serviceArchive->id)->SoftTrash();
        return Active::Deleted($serviceArchive->trans_title);
    }

    public function restore($serviceArchive){
        ServiceArchive::where('id', $serviceArchive)->RestoreFromTrash();

        $serviceArchive = ServiceArchive::where('id', $serviceArchive)->first();
        return Active::Restored($serviceArchive->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['excerpt'] = null;
        $validated['details'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }
}
