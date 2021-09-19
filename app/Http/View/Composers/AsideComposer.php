<?php

namespace App\Http\View\Composers;
use App\Infastructure;
use App\Infrastructure;
use Illuminate\View\View;

class AsideComposer
{
	public function compose(View $view){

		// if(auth()->user()->role_id==1)
		{
            if(auth()->user()->role_id==3){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16, 56])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('parent_id', [16, 56])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==4){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16, 56, 91, 92, 25])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [16, 56, 91, 92, 25, 37, 38])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==5){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [53, 16, 25,20,3,56])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                // ->whereNotNull('parent_id')
                ->whereIn('id', [40,54,66,88,2,17,18,24,29,35,36,49,96,97,49,26,27,21,22,23,4,98])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==6){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [56, 92])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [56, 92, 91, 38])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==7){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16, 92])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [17, 18])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==8){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16,68])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [17])
                ->orWhere('parent_id', 68)
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==9){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16,56,25,117])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [17,38,37,18,91,101,98,35,114,115,116])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==10){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16, 56, 91, 92, 25, 131])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [16, 56, 91, 92, 25, 37, 38, 2, 17, 18, 24, 29, 35, 36, 49, 96, 97, 26, 132, 133, 134])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==11){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [53, 16, 25, 20, 13, 68])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                // ->whereNotNull('parent_id')
                ->whereIn('id', [40,54,66,88,2,17,18,24,29,35,36,49,96,97,49,26,27,21,22,23,17,19])
                ->orWhere('parent_id', 68)
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==12){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [18])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==13){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16,56,25,117])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [17,38,37,18,91,101,98,35,114,115,116,92])
                ->orderBy('order')
                ->get();
            }
            else if(auth()->user()->role_id==14){
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->whereIn('id', [16])
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->whereIn('id', [2])
                ->orderBy('order')
                ->get();
            }
            else {
                $asides = Infastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

                $infastructures = Infastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->orderBy('order')
                ->get();
            }
		}

        $user_pages = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
        ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
        ->whereNull('infastructures.parent_id')
        ->where('user_id', auth()->user()->id)
        ->distinct('infrastructures.id')
        ->select('infastructures.*')
        ->get();

        $user_pages_child = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
        ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
        ->whereNotNull('infastructures.parent_id')
        ->where('user_id', auth()->user()->id)
        ->distinct('infastructures.id')
        ->select('infastructures.*')
        ->get();

        $view->with('asides', $asides);
        $view->with('infastructures', $infastructures);
        $view->with('user_pages', $user_pages);
        $view->with('user_pages_child', $user_pages_child);
	}
}
