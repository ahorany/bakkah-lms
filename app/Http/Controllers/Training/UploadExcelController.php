<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Active;

class UploadExcelController extends Controller
{
    public function index()
    {
        return view('training.excel_uploads');
        // return redirect()->route('excel_uploads.index');
    }

    function excel(Request $request)
    {
        $file = $request->file;

        // echo 'aaaa.<br>';
        dd($file);

        Excel::import(new ExcelImport, $file);
        // return back()->withStatus('Excel file imported Successfully.');
        Active::Flash("Inserted Successfully.", __('flash.empty'), 'success');
        return back();
        // return redirect()->route('training.excel_uploads.index');
    }
}
