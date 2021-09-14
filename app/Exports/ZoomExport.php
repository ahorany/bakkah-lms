<?php 
namespace App\Exports;
use App\Models\Training\AttendenceZoomDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
    
class ZoomExport implements FromCollection
  {
      /**
      * @return \Illuminate\Support\Collection
      */
      public function collection()
      {
          return AttendenceZoomDetails::where('is_attended',0)->select('name','email','absent_reason')->get();;
      }
  }

