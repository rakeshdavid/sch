<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Models\Proposal;
use App\Models\Video;
use Validator;

class ProposalController extends Controller
{
    public function __construct(Request $request, Redirector $redirect){
    
        $user = $request->user();

        if(empty($user)){
            $redirect->to('login')->send();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $result = Proposal::getByUserId($user->id);
        return view('proposal/index',["proposals" => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('proposal/create',['video_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatorParams = [
            'video_id' => 'required|max:255',
            'description' => 'required|min:6',
        ];
        
        $validator = Validator::make($request->all(), $validatorParams);
        if ($validator->fails())
            return back()->withInput()->withErrors($validator);
        
        $user = $request->user();
        $data = $request->only(["description", "video_id"]);
        $data["user_id"] = $user->id;
        $data["created_at"] = mysql_date();
        $data["updated_at"] = mysql_date();

        Proposal::saveProposal($data);
        return redirect()->action('ProposalController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Proposal::getByVideoId($id);
        return view('proposal/show',["proposals" => $result, "video_id" => $id]);
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
    
    public function changeStatus(Request $request)
    {
        if ($request->isMethod('post')){ 
            
            $id = $request->proposal_id;
            $video_id = $request->video_id;
            $status = $request->status;
            
            $result = Proposal::changeStatus($id, $status);
            if($status == 1){
                Video::changeStatus($video_id, Video::STATUS_ACCEPT_PROPOSAL);
            }
                
            return response()->json($result); 
        }
        
    }
}
