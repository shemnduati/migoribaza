<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Application;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::latest()->paginate(10);
        $applications = Application::where('status', 0)->get();

        return ['applications'=>$applications];

    }

    public function getApplications()
    {
        // return User::latest()->paginate(10);
        $applications = Application::where('year', date('Y'))->whereIn('status', [1,3])->get();

        return ['applications'=>$applications];

    }
    public function getbusary()
    {
        // return User::latest()->paginate(10);
        $ward_id = User::where('id',Auth::user()->id)->value('ward');
        $applications = Application::where('year', date('Y'))->where('ward_id',$ward_id)->get();

        return ['applications'=>$applications];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function getType($id)
    {
        if ($id==1) {
            $applications = Application::where('year', date('Y'))->whereIn('status', [1,3])->get();

            return ['applications'=>$applications];
        }elseif ($id==2) {
            $applications = Application::where('status', 0)->where('year', date('Y'))->get();

            return ['applications'=>$applications];
        }elseif ($id==3) {
            $applications = Application::where('status', 1)->where('year', date('Y'))->get();

            return ['applications'=>$applications];
        }elseif ($id==4) {
            $applications = Application::where('status', 2)->where('year', date('Y'))->get();

            return ['applications'=>$applications];
        }elseif ($id==5) {
            $applications = Application::where('status', 3)->where('year', date('Y'))->get();

            return ['applications'=>$applications];
        }
    }

    public function getstatus($id)
    {
        $ward_id = User::where('id',Auth::user()->id)->value('ward');
        if ($id==1) {
            $applications = Application::where('year', date('Y'))->where('ward_id',$ward_id)->get();

            return ['applications'=>$applications];
        }elseif ($id==2) {
            $applications = Application::where('status', 0)->where('year', date('Y'))->where('ward_id',$ward_id)->get();

            return ['applications'=>$applications];
        }elseif ($id==3) {
            $applications = Application::where('status', 1)->where('year', date('Y'))->where('ward_id',$ward_id)->get();

            return ['applications'=>$applications];
        }elseif ($id==4) {
            $applications = Application::where('status', 2)->where('year', date('Y'))->where('ward_id',$ward_id)->get();

            return ['applications'=>$applications];
        }
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
        $this->authorize('isAdmin');
        $user = User::findorFail($id);
        $this->validate($request, [
            'role' => 'required|string|max:191',
        ]);
//        $user->update($request->all());
        $user->role = $request['role'];
        $user->update();
        return ['message'=>'information updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        $user->delete();
        return ['message'=> 'user deleted'];
    }
    public function search(){
        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%")
                    ->orWhere('role','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(10);
        }
        return $users;
    }
}