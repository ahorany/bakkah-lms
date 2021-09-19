<?php

namespace App\Http\Controllers\Admin;

use Algolia\AlgoliaSearch\Http\Psr7\Request;
use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgreementRequest;
use App\Models\Admin\Agreement;
use App\Models\Admin\Partner;
use Carbon\Carbon;
use App\Models\Admin\Note;


class AgreementController extends Controller
{

    public function __construct()
    {
        Active::$folder = 'agreement';
    }

    public function index()
    {
        $post_type = GetPostType('agreement');
        $trash = GetTrash();
        $agreement = Agreement::whereNotNull('id')->with('partner','notes','notes.user');
        $partners = Partner::whereNotNull('id')->where('post_type','partners')->page();
        if(!is_null(request()->partners) && request()->partners != -1) {
            $agreement = $agreement->where('partner_id', request()->partners);
        }

        $now_date = Carbon::now()->format('Y-m-d');
        $count = $agreement->count();
        $agreement = $agreement->page();
        return Active::Index(compact('partners','now_date','agreement', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $partners = Partner::where('post_type','partners')->get();
        $eloquent = null;
        return Active::Create(compact('eloquent','partners'));
    }

    public function store(AgreementRequest $request)
    {
        $validated = $request->validated();
        if(isset($validated['is_active']) && $validated['is_active'] == "is_active"){
            $validated['is_active'] = 1;
        }else{
            $validated['is_active'] = 0;
        }

        $agreement = Agreement::create($validated);
        return Active::Inserted($agreement->trans_name, ['post_type'=>$agreement->post_type]);
    }

    public function edit($agreementId)
    {
        $agreement = Agreement::with(['notes.user', 'notes'=>function($query){ $query->orderBy('id', 'DESC'); }])->findOrFail($agreementId);
        $agreementId = $agreement->id??null;
        $partners = Partner::where('post_type','partners')->get();
        return Active::Edit(['eloquent'=>$agreement, 'partners' => $partners, 'agreementId' => $agreementId]);
    }

    public function update(AgreementRequest $request, Agreement $agreement)
    {
        $validated = $request->validated();
        if(isset($validated['is_active']) && $validated['is_active'] == "is_active"){
            $validated['is_active'] = 1;
        }else{
            $validated['is_active'] = 0;
        }

        Agreement::find($agreement->id)->update($validated);
        Agreement::UploadFile($agreement, ['method'=>'update']);
        return Active::Updated($agreement->trans_name);
    }

    public function destroy(Agreement $agreement)
    {
        Agreement::where('id', $agreement->id)->SoftTrash();
        return Active::Deleted($agreement->trans_name);
    }

    public function restore($agreement){
        Agreement::where('id', $agreement)->RestoreFromTrash();
        $redirect = agreement::where('id', $agreement)->first();
        return Active::Restored($redirect->trans_name);
    }

    public function getNotes($agreementId)
    {
        $comment = Agreement::with(['notes', 'notes.user'])->orderBy('id', 'DESC')->findOrFail($agreementId);

        return response()->json($comment);
    }

    public function comment($agreementId)
    {
        $agreement = Agreement::findOrFail($agreementId);
        $comments = $agreement->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => request()->comment
        ]);

        $comment = Agreement::with(['notes'=>function($query)use($comments){
            $query->where('id', $comments->id);
        }, 'notes.user'])
        ->findOrFail($agreementId);

        return response()->json($comment->notes);
    }

    public function deleteComments($ids) {
        Note::whereIn('id', request()->ids)->delete();
    }

    public function addNote($agreementId=null, $comment=null, Request $request=null){
        return request();
        $agreementId = $agreementId??request()->agreementId;
        $comment = $comment??request()->comment;

        $agreement = Agreement::findOrFail($agreementId);
        $agreement->notes()->create([
            'user_id' => auth()->user()->id,
            'comment' => $comment,
        ]);

    }

}
