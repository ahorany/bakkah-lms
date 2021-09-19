<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketRequest;
use App\Models\Admin\Ticket;
use App\Models\Training\Social;
use App\User;
use Carbon\Carbon;
use Mpdf\Tag\Dd;

class TicketController extends Controller
{

    public function __construct()
    {
        Active::$folder = 'ticket';
    }

    public function index()
    {
        $post_type = GetPostType('ticket');
        $trash = GetTrash();
        $status = Constant::where('post_type','status_type')->get();
        $priorities = Constant::where('post_type','priority_type')->get();
        $companies = Constant::where('post_type','company_type')->get();
        $ticket =Ticket::whereNotNull('id')->with(['issues', 'priorities', 'states','companies']);
        if(!is_null(request()->title_search)) {
            $ticket = $ticket->where(function($query){
                $query->where('title', 'like', '%'.request()->title_search.'%');
            });
        }

        if(request()->status && request()->status != -1) {
            $ticket = $ticket->where('status', request()->status);
        }
        if(request()->priority && request()->priority != -1) {
            $ticket = $ticket->where('priority', request()->priority);
        }
        if(request()->company && request()->company != -1) {
            $ticket = $ticket->where('company', request()->company);
        }

        $count = $ticket->count();
        $ticket = $ticket->page();
        $user = auth()->id();
        $ticket_user =  Ticket::where('created_by',auth()->id())->first();

        $users_id = [
            $ticket_user->created_by??null,
            1,
            2,
            3,
            10792,
        ];

        if(in_array($user , $users_id )){
            return Active::Index(compact('ticket', 'count', 'post_type', 'trash','user','users_id','status','priorities','companies'));
        }
        return abort(404);
    }

    public function create()
    {
        $status = Constant::where('post_type','status_type')->get();
        $priority = Constant::where('post_type','priority_type')->get();
        $issue = Constant::where('post_type','issue_type')->get();
        $company = Constant::where('post_type','company_type')->get();
        $users = User::whereIn('id',[1,2,3,10792])->get();
        return Active::Create(compact('status','priority','issue','company','users'));
    }

    public function store(TicketRequest $request)
    {
        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['status'] = 445;

        unset($validated['ticket_file']);
        $ticket = Ticket::create($validated);
        $this->uploadsPDF($ticket, 'ticket_file', '');
        return Active::Inserted($ticket->trans_name, ['post_type'=>$ticket->post_type]);
    }

    public function edit(Ticket $ticket)
    {
        $status =  Constant::where('post_type','status_type')->get();
        $priority =  Constant::where('post_type','priority_type')->get();
        $issue =  Constant::where('post_type','issue_type')->get();
        $company =  Constant::where('post_type','company_type')->get();
        $users = User::whereIn('id',[1,2,3,10792])->get();


        return Active::Edit([
            'eloquent'=>$ticket,
            'status'=>$status,
            'priority'=>$priority,
            'issue'=>$issue,
            'company'=>$company,
            'users' => $users,
        ]);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        $validated = $this->Validated($request->validated());
        $ticket = Ticket::find($ticket->id) ;

        if($ticket->created_by != auth()->id() ){
            $validated['tracked_by'] = auth()->id();
            $validated['updated_status'] = 1;
        }

        $date = Carbon::now();
        $validated['tracked_at'] = $date;


        $ticket->update($validated);

        Ticket::UploadFile($ticket, ['method'=>'update']);
        $this->uploadsPDF($ticket, 'ticket_file', '');
        return Active::Updated($ticket->trans_name);
    }

    public function destroy(Ticket $ticket)
    {
        Ticket::where('id', $ticket->id)->SoftTrash();
        return Active::Deleted($ticket->trans_name);
    }

    public function restore($ticket){
        Ticket::where('id', $ticket)->RestoreFromTrash();
        $social_media = Ticket::where('id', $ticket)->first();
        return Active::Restored($social_media->trans_name);
    }

    private function Validated($validated){
        // $validated['title'] = null;
        $validated['updated_by'] = auth()->user()->id;
        return $validated;
    }


    private function uploadsPDF($model, $name='pdf', $locale='en'){

        $full_name = $name;
        if(!empty($locale)) {
            $full_name = $locale.'_'.$name;
        }

        if(request()->has($full_name)){

            $upload = $model->uploads()
            ->where('post_type', $name)
            ->where('locale', $locale)
            ->first();

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $full_name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/pdf/'), $fileName)){

                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkPDF($name, $upload->file);
                    $model->uploads()->where('post_type', $name)
                    ->where('locale', $locale)
                    ->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkPDF($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/pdf/') . $image)){
                unlink(public_path('/upload/pdf/') . $image);
            }
        }
    }

}
