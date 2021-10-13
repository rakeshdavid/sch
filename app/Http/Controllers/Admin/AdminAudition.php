<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use App\Models\Auditions;
use App\Models\AuditionList;
use App\Models\AuditionReview;
use App\Models\User;
class AdminAudition extends Controller
{
    private $_user = NULL;

    public function __construct(Request $request, Redirector $redirect)
    {
        $this->_user = auth()->user();
        $this->middleware(['auth']);

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    public function agencyAuditions(Request $request, Redirector $redirect){
    	$agency_id = (int)request()->route('agency_id');
    	$user = User::find($agency_id);
    	$auditions = Auditions::where('agency_id', $agency_id)->get();

    	return view('admin.agency.agencyaudition',['auditions'=>$auditions,'agency'=>$user]);
    }

    public function auditionParticpants(Request $request, Redirector $redirect){
    	$audition_id = (int)request()->route('audition_id');
    	
    	$auditions = Auditions::where('id', $audition_id)->first();
    	$user = User::find($auditions->agency_id);
    	$participants = AuditionList::with(['user','audition','auditionreviewnew'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('audition_id',$audition_id)->get();
        $model = AuditionReview::find(2);
    	return view('admin.agency.participants',['participants'=>$participants,'agency'=>$user])->withModel($model);
    }

}
