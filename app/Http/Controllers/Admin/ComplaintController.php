<?php

namespace App\Http\Controllers\Admin;

use App\Constant;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ComplaintRequest;
use App\Models\Admin\Complaint;
use App\Models\Admin\Partner;
use App\Models\Admin\Note;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct()
    {
        Active::$folder = 'complaint';
    }

    public function index()
    {
        $post_type = GetPostType('complaint');
        $trash = GetTrash();
        $complaint = Complaint::whereNotNull('id')->with('partner','departments','states','notes','notes.user');
        if(!is_null(request()->title_search)) {
            $complaint = $complaint->whereHas('partner', function($q){
            return $q->where('name', 'like', '%'.request()->title_search.'%');
        });
        }

        $count = $complaint->count();
        $complaint = $complaint->page();

        return Active::Index(compact('complaint', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $partners = Partner::where('post_type','partners')->get();
        $department = Constant::where('post_type','departments')->get();
        $status = Constant::where('post_type','status_partner')->get();
        $eloquent = null;
        return Active::Create(compact('eloquent', 'partners','department','status'));
    }

    public function store(ComplaintRequest $request)
    {
        $department = Constant::where('post_type','departments')->get();
        $status = Constant::where('post_type','status_partner')->get();
        $validated = $request->validated();
        $complaint = Complaint::create($validated);
        return Active::Inserted($complaint->trans_name, ['post_type'=>$complaint->post_type]);
    }

    public function edit($complaintId)
    {

        $complaint = Complaint::with(['notes.user', 'notes'=>function($query){ $query->orderBy('id', 'DESC'); }])->findOrFail($complaintId);
        $complaintId = $complaint->id??null;
        $partners = Partner::where('post_type','partners')->get();
        $department = Constant::where('post_type','departments')->get();
        $status = Constant::where('post_type','status_partner')->get();
        return Active::Edit(['eloquent'=>$complaint, 'partners' => $partners, 'department' => $department, 'status' => $status, 'complaintId' => $complaintId]);
    }

    public function update(ComplaintRequest $request, Complaint $complaint)
    {
        $validated = $request->validated();
        Complaint::find($complaint->id)->update($validated);
        Complaint::UploadFile($complaint, ['method'=>'update']);
        return Active::Updated($complaint->trans_name);
    }

    public function destroy(Complaint $complaint)
    {
        Complaint::where('id', $complaint->id)->SoftTrash();
        return Active::Deleted($complaint->trans_name);
    }

    public function restore($complaint){
        Complaint::where('id', $complaint)->RestoreFromTrash();
        $redirect = complaint::where('id', $complaint)->first();
        return Active::Restored($redirect->trans_name);
    }

    public function getNotes($complaintId)
    {
        $comment = Complaint::with(['notes', 'notes.user'])->orderBy('id', 'DESC')->findOrFail($complaintId);

        return response()->json($comment);
    }

    public function comment($complaintId)
    {
        $complaint = Complaint::findOrFail($complaintId);
        $comments = $complaint->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => request()->comment
        ]);

        $comment = Complaint::with(['notes'=>function($query)use($comments){
            $query->where('id', $comments->id);
        }, 'notes.user'])
        ->findOrFail($complaintId);

        return response()->json($comment->notes);
    }

    public function deleteComments($ids) {
        Note::whereIn('id', request()->ids)->delete();
    }

    public function addNote($complaintId=null, $comment=null, Request $request=null){

        $complaintId = $complaintId??request()->complaintId;
        $comment = $comment??request()->comment;

        $complaint = Complaint::findOrFail($complaintId);
        $complaint->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => $comment,
        ]);
    }
}
