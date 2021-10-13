<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;

class SettingsController extends AdminController
{
    public function index()
    {
        $settings = Setting::all();

        return view('admin.settings.index', [
            'settings' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['platform_maintenance_mode'] = $request->has('platform_maintenance_mode') ? 1 : 0;
        $data['coaches_maintenance_mode'] = $request->has('coaches_maintenance_mode') ? 1 : 0;

        DB::beginTransaction();
        try {
            foreach ($data as $key => $value) {
                $record = Setting::whereKey($key)->update(['value' => $value]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();

            return redirect(route('admin.settings.index'))->with(['error' => 'Oops, something went wrong!']);
        }

        return redirect(route('admin.settings.index'))->with(['success' => 'Settings successfully updated!']);
    }
}
