<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Auditions;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Mailer;
use App\Http\Requests\AgencyAuditionRequest;
use App\Http\Requests\AgencyAuditionUpdateRequest;
use App\Models\PerformanceLevel;
use App\Models\ActivityType;
use App\Models\ActivityGenre;
use Illuminate\Routing\Redirector;
class AgencyController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $agency = User::getAllAgency();
        //print_r($agency);
        return view('admin.agency.index',['agency'=>$agency]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.agency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email|max:255',
            'location'=>'required',
            'location_state'=>'required'
        ]);
        if ($validator->fails()) {
            return redirect('agency/create')->withErrors($validator) ->withInput();
        }
        $pass = str_random(6);

        $agency = new User;
        $agency->first_name = $request->first_name;
        $agency->last_name = $request->last_name;
        $agency->title = $request->title;
        $agency->email = $request->email;
        $agency->location = $request->location;
        $agency->location_state = $request->location_state;
        $agency->role=4;
        $agency->password=bcrypt($pass);
        $agency->save();

        $registered_user = [
            'first_name' => $agency->first_name,
            'last_name' => $agency->last_name,
            'email' => $agency->email,
            'contact_email' => $agency->contact_email,
            'location' => $agency->location,
            'location_state' => $agency->location_state,
            'password' => $pass
        ];

        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $agency->email;
        $mail->sendMail('auth.emails.adminRegisteredAgency', ['user_data' => $registered_user]);

        return redirect('agency/create')->with(['success' => 'New Agency successfully created!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
       
        $agency = User::getAgencyById($id);
        //print_r($agency);
        return view('admin.agency.edit',['agency'=>$agency]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //echo "updtae ".$id;
        if(User::isAgency($id)){
            $agency = User::find($id);
            $agency->first_name = $request->first_name;
            $agency->last_name = $request->last_name;
            $agency->title = $request->title;
            $agency->email = $request->email;
            $agency->location = $request->location;
            $agency->location_state = $request->location_state;
            $agency->save();
            return back()->with(['success' => 'Agency credentials successfully updated!']);
        }else{
            return back()->with(['error' => 'Agency is not valid!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function agencyAudition(){
        
        $auditions = Auditions::all();
        // echo "<pre>";
        // print_r($auditions);
        // echo "</pre>";
        return view('admin.agency.auditions',['auditions'=>$auditions]);
    }

    public function auditionForAgency(){
        $agency = User::getAllAgency();
        $activity_types = ActivityGenre::get();
        $performance_levels = PerformanceLevel::get();
        return view('admin.agency.add-audition',['agency'=>$agency, 'activity_types' => $activity_types,'performance_levels' => $performance_levels]);
    }

    public function addAudition(AgencyAuditionRequest $request){
        $audition = new Auditions;
        $audition->agency_id = request()->get('agency_id');
        $audition->added_by = auth()->user()->id;
        $audition->audition_name = request()->get('audition-name');
        $audition->title = request()->get('title');
        $audition->audition_fee = request()->get('audition-fee');
        $audition->deadline = request()->get('audition-deadline');
        $audition->location = request()->get('audition-location');
        //$audition->audition_detail = request()->get('audition-detail');
        $audition->description = request()->get('audition-description');
        $audition->requirement = request()->get('audition-requirement');
        $audition->talent = request()->get('audition-genres');
        $audition->level = request()->get('level');
        if ($request->hasFile('logo')) {
            $fileProfile = $request->logo;
            $fileProfileName = str_random(8) . "logo." . $fileProfile->extension();
            $path = public_path().'/uploads/auditions';
            $fileProfile->move($path, $fileProfileName);
            
            $avatar_path =  $fileProfileName;
            $audition->logo = $avatar_path;
        }
        if ($request->hasFile('header_image')) {
            $fileProfile = $request->header_image;
            $fileProfileName = str_random(8) . "header-image." . $fileProfile->extension();
            $path = public_path().'/uploads/auditions';
            $fileProfile->move($path, $fileProfileName);
            
            $image_path =  $fileProfileName;
            $audition->header_image = $image_path;
        }
        $audition->save();

        return back()->with(['success' => 'New audition for agency has been added!']);
    }

    public function editAuditionForAgency(){
        $agency = User::getAllAgency();
        $activity_types = ActivityGenre::get();
        $performance_levels = PerformanceLevel::get();
        $audition_id = (int)request()->route('audition_id');
        if($audition_id == ''){
            return abort(404);
        }
        $audition_detail = Auditions::find($audition_id);
        return view('admin.agency.edit-audition',['agency'=>$agency, 'activity_types' => $activity_types,'performance_levels' => $performance_levels,'audition'=>$audition_detail]);
    }

    public function updateAudition(AgencyAuditionUpdateRequest $request){
        $audition = Auditions::find(request()->get('audition_id'));
        $audition->agency_id = request()->get('agency_id');
        $audition->added_by = auth()->user()->id;
        $audition->audition_name = request()->get('audition-name');
        $audition->title = request()->get('title');
        $audition->audition_fee = request()->get('audition-fee');
        $audition->deadline = request()->get('audition-deadline');
        $audition->location = request()->get('audition-location');
        //$audition->audition_detail = request()->get('audition-detail');
        $audition->description = request()->get('audition-description');
        $audition->requirement = request()->get('audition-requirement');
        $audition->talent = request()->get('audition-genres');
        $audition->level = request()->get('level');
        if ($request->hasFile('logo')) {
            $fileProfile = $request->logo;
            $fileProfileName = str_random(8) . "logo." . $fileProfile->extension();
            $path = public_path().'/uploads/auditions';
            $fileProfile->move($path, $fileProfileName);
            
            $avatar_path =  $fileProfileName;
            $audition->logo = $avatar_path;
        }
        if ($request->hasFile('header_image')) {
            $fileProfile = $request->header_image;
            $fileProfileName = str_random(8) . "header-image." . $fileProfile->extension();
            $path = public_path().'/uploads/auditions';
            $fileProfile->move($path, $fileProfileName);
            
            $image_path =  $fileProfileName;
            $audition->header_image = $image_path;
        }
        $audition->save();

        return back()->with(['success' => 'Audition for agency has been updated!']);
    }
}
