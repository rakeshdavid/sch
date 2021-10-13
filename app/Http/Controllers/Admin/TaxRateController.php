<?php

namespace App\Http\Controllers\Admin;

use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;

class TaxRateController extends AdminController
{
    public function index()
    {
        //$taxes = TaxRate::all();
        $taxes = [];
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();
        $audition_tax = DB::table('taxrate')->select('taxrate')->where('name','audition_tax')->first();
        $challenge_tax = DB::table('taxrate')->select('taxrate')->where('name','challenge_tax')->first();

        return view('admin.settings.taxrate', [
            'video_tax' => $video_tax,'audition_tax'=>$audition_tax,'challenge_tax'=>$challenge_tax
        ]);
    }

    public function store(Request $request)
    {
        $taxes = DB::table('taxrate')->select('*')->get();
        if($taxes){
            DB::table('taxrate')->where('name', 'video_tax')->update(['taxrate' => $request->video_tax]);
            DB::table('taxrate')->where('name', 'audition_tax')->update(['taxrate' => $request->audition_tax]);
            DB::table('taxrate')->where('name', 'challenge_tax')->update(['taxrate' => $request->challenge_tax]);
        }else{
            DB::table('taxrate')->insert([
                ['id'=>'','name' => 'video_tax', 'taxrate' => $request->video_tax,'created_at' => mysql_date(),'updated_at' => mysql_date()],
                ['id'=>'','name' => 'audition_tax', 'taxrate' => $request->audition_tax,'created_at' => mysql_date(),'updated_at' => mysql_date()],
                ['id'=>'','name' => 'challenge_tax', 'taxrate' => $request->challenge_tax,'created_at' => mysql_date(),'updated_at' => mysql_date()],
            ]);
        }
        
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();
        $audition_tax = DB::table('taxrate')->select('taxrate')->where('name','audition_tax')->first();
        $challenge_tax = DB::table('taxrate')->select('taxrate')->where('name','challenge_tax')->first();

        return view('admin.settings.taxrate', [
            'video_tax' => $video_tax,'audition_tax'=>$audition_tax,'challenge_tax'=>$challenge_tax
        ]);
        
    }
}
