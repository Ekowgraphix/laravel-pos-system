<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    //profile details
    public function profileDetails(){
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_orders' => \App\Models\Order::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'pending_orders' => \App\Models\Order::where('status', '0')->count(),
            'total_revenue' => \App\Models\Order::where('status', '1')->sum('totalPrice'),
            'low_stock_items' => \App\Models\Product::whereRaw('count <= low_stock_threshold')->count(),
        ];
        
        // Get recent activities
        $recentOrders = \App\Models\Order::with('product', 'user')
            ->latest()
            ->limit(5)
            ->get();
        
        // Calculate profile completion
        $profileFields = ['name', 'email', 'phone', 'address', 'profile'];
        $completedFields = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompletion = round(($completedFields / count($profileFields)) * 100);
        
        // Get admin activity summary
        $activitySummary = [
            'member_since' => $user->created_at->diffForHumans(),
            'last_login' => $user->updated_at->diffForHumans(),
            'total_logins' => rand(10, 100), // You can track this properly with a login tracking system
        ];
        
        return view('admin.profile.details', compact('stats', 'recentOrders', 'profileCompletion', 'activitySummary'));
    }

    //create admin account
    public function createAdminAccount(){
        return view('admin.profile.createAdminAcct');
    }

    //create new admin account
    public function create(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirmpassword' => 'required|same:password'
        ]);
        $adminAccount = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'provider' => 'simple'
        ];

            User::create($adminAccount);
            Alert::success('Success', 'New Admin Account Created Successfully....');
            return back();
    }


    //profile update
    public function update(Request $request){
    //    dd($request->all()) ;
        $this->validationCheck($request);
        $adminData = $this -> requestAdminData($request);


        if($request->hasFile('image')){
            //delete old image
            $oldImageName = $request->oldImage;
            if($request->oldImage != null){
                if(file_exists(public_path('adminProfile/'.$request->oldImage))){
                    unlink(public_path('adminProfile/'.$request->oldImage));
                }
            }
            //upload new image
            $fileName = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path(). '/adminProfile/' , $fileName);
            $adminData['profile'] = $fileName;
        }
        else{
            $adminData['profile']= $request->oldImage;
        }


        // dd($adminData);
        User::where('id',Auth::user()->id)->update($adminData);
        Alert::success('Update Success', 'Profile Updated Successfully....');
        return back();

    }

    //direct account Profile
    public function accountProfile($id){
        $account = User::where('id',$id)->first();
        return view('admin.profile.accountProfile',compact('account'));
    }


    //request admin data
    private function requestAdminData($request){

        // dd($request->all()) ;
        $data =[];
        if(Auth::user()->name != null){
            $data['name'] =Auth::user()->provider == 'simple' ? $request->name : Auth::user()->name;
        }else{
            $data['nickname'] =Auth::user()->provider == 'simple' ? $request->name : Auth::user()->name;
        }

        $data['email'] = Auth::user()->provider == 'simple' ? $request->email : Auth::user()->email;
        $data['phone'] =$request->phone;
        $data['address'] =$request->address;

        return $data;
    }

      //create | update validation check
      private function validationCheck($request){
        $rules = [
            'phone' => ['required', 'unique:users,phone,' . Auth::user()->id],
            'address' => 'required',
        ];


        if (Auth::user()->provider == 'simple') {
            $rules['name'] = 'required';
            $rules['email'] = 'required|unique:users,email,' . Auth::user()->id;
        }

        $validator = $request->validate($rules);

    }
}
