<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;

class ActionController extends AdminController
{
    public function changeHiddenUser(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $user = User::findOrFail($request->get('userId'));

        return response()->json(['success' => $user->changeHiddenOption()]);
    }

    public function loginAs($id)
    {
        if (User::where('id', $id)->exists()) {
            if (Auth::loginUsingId($id)) {
                return redirect('/profile');
            }
        }

        return back()->with(['user' => 'Oops! Something went wrong. Please, reload the page.']);
    }

    public function changeTestUser(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $user = User::findOrFail($request->get('userId'));

        return response()->json(['success' => $user->changeUserTest()]);
    }
}
