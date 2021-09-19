<?php

namespace App\Http\Controllers\Admin\Service;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\ServiceRequest;
use App\Models\Admin\Post;
use App\Models\Admin\Service\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'services';
    }

    public function index(){

        $trash = GetTrash();

        if(request()->has('master_id')){

            $post = Post::findOrFail(request()->master_id);

            $services = Service::with('user')
            ->where('master_id', $post->id)
            ->orderBy('order');
            $count = $services->count();
            $services = $services->page();

            $title = $post->title;// . ' ( ' . $post->constant->trans_name . ' ) ';
            session()->put('infastructure__title', $title);
            session()->put('infastructure__icon', 'fas fa-chalkboard');
            return Active::Index(compact('services', 'count', 'trash'));
        }
    }

    public function create(){
        return Active::Create(['object'=>Service::class, 'master_id'=>request()->master_id,]);
    }

    public function store(ServiceRequest $request){
        $validated = $this->validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['master_id'] = $request->master_id;

        $service = Service::create($validated);

        return Active::Inserted($service->trans_title, [
            'master_id'=>$validated['master_id'],
        ]);
    }

    public function edit(Service $service){
        return Active::Edit(['eloquent'=>$service]);
    }

    public function update(ServiceRequest $request, Service $service){

        $validated = $this->Validated($request->validated());

        Service::find($service->id)->update($validated);

        return Active::Updated($service->trans_title);
    }

    public function destroy(Service $service, Request $request){
        Service::where('id', $service->id)->SoftTrash();
        return Active::Deleted($service->trans_title);
    }

    public function restore($service){
        Service::where('id', $service)->RestoreFromTrash();

        $service = Service::where('id', $service)->first();
        return Active::Restored($service->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['excerpt'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }
}
