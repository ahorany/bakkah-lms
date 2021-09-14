<?php

namespace Modules\CRM\Http\Controllers;

use App\Helpers\Active;
use Illuminate\Routing\Controller;
use Modules\CRM\Http\Requests\GroupInvAdminRequest;
use Modules\CRM\Http\Models\GroupInvoiceHelper;

class GroupInvController extends Controller
{
    // public $master_id_field = 'master_id';
    public function __construct()
    {
        $this->middleware('auth');
        Active::$namespace = null;
        // $this->carts = 'gcarts';
        Active::$folder = 'crm::group_invs';
        $this->GroupInvoiceHelper = new GroupInvoiceHelper();
        // $this->GroupInvoiceHelper = new GroupInvoiceHelper($this->carts);
    }

    public function index()
    {
        return $this->GroupInvoiceHelper->index();
    }

    public function create()
    {
        return $this->GroupInvoiceHelper->create();
    }

    public function store(GroupInvAdminRequest $request)
    {
        return $this->GroupInvoiceHelper->store($request);
    }

    public function edit($cart_master_id)
    {
        return $this->GroupInvoiceHelper->edit($cart_master_id);
    }

    public function update(GroupInvAdminRequest $request, $id)
    {
        return $this->GroupInvoiceHelper->update($request, $id);
    }

    public function destroy($cart_master_id)
    {
        return $this->GroupInvoiceHelper->destroy($cart_master_id);
    }

    public function restore($cart_master_id)
    {
        return $this->GroupInvoiceHelper->restore($cart_master_id);
    }

    // public function sessionsJson()
    // {
    //     return $this->GroupInvoiceHelper->sessionsJson();
    // }

    // public function SessionsDetailsJson()
    // {
    //     return $this->GroupInvoiceHelper->SessionsDetailsJson();
    // }

    // public function register()
    // {
    //     return $this->GroupInvoiceHelper->register();
    // }

    // public function autofill()
    // {
    //     return $this->GroupInvoiceHelper->autofill();
    // }

    // public function registerStore(RFPRequest $request)
    // {
    //     return $this->GroupInvoiceHelper->registerStore($request);
    // }

    // public function registerEdit($id)
    // {
    //     return $this->GroupInvoiceHelper->registerEdit($id);
    // }

    // public function registerUpdate(RFPRequest $request, $id)
    // {
    //     return $this->GroupInvoiceHelper->registerStore($request, $id);
    // }

    public function deleteCandidates()
    {
        return $this->GroupInvoiceHelper->deleteCandidates();
    }

    public function storeCandidate()
    {
        return $this->GroupInvoiceHelper->storeCandidate('crm::group_invs.candidates');
    }

    public function updatePrices_front($course_id, $session_id, $cart_master_id, $type_id)
    {
        return $this->GroupInvoiceHelper->updatePrices_front($course_id, $session_id, $cart_master_id, $type_id);
    }

    public function getNotes($cartMasterId)
    {
        return $this->GroupInvoiceHelper->getNotes($cartMasterId);
    }
    public function comment($cartMasterId)
    {
        return $this->GroupInvoiceHelper->comment($cartMasterId);
    }
    public function deleteComments($ids)
    {
        return $this->GroupInvoiceHelper->deleteComments($ids);
    }
    // =================== End Frontend =======================

    public function escapeValue($text)
    {
        return $this->GroupInvoiceHelper->escapeValue($text);
    }

    public function exportQuotationToDoc($cart_master_id)
    {
        return $this->GroupInvoiceHelper->exportQuotationToDocGroup($cart_master_id);
    }

    public function exportInvoiceToDoc($id, $type='invoice')
    {
        return $this->GroupInvoiceHelper->exportInvoiceToDocGroup($id, $type);
    }

    // public function search()
    // {
    //     return $this->GroupInvoiceHelper->search();
    // }

    // public function SearchCandidates()
    // {
    //     return $this->GroupInvoiceHelper->SearchCandidates();
    // }
}
