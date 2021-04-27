<?php

namespace App\Http\Controllers\Admin;

use App\Users;
use App\Models\User;
use App\Models\Rieltors;
use Illuminate\Http\Request;

class AdminController extends AC
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexAdmin()
    {
        return view('adminHome');
    }

    public function indexRieltor()
    {
        return view('admin.rieltor.index');
    }

    // START - RIELTORS //

    public function getRieltors()
    {
        $dataRieltors = User::where('is_admin', 0)->get();

        return view('admin.rieltor.index', compact("dataRieltors"));
    }

    public function insertRieltor(Request $request)
    {
        // new
    }

    public function deleteRieltor($id)
    {
        // remove
        $data = User::find($id);
        $data->delete();
        return redirect('/home');
    }

    public function editRieltor(Request $request, $id)
    {
        // edit
    }

    public function viewRieltor($id)
    {
        // show detail page about rieltor

    }

    // END - RIELTORS //
}

