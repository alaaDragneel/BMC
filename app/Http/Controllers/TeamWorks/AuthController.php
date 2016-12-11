<?php

namespace App\Http\Controllers\TeamWorks;

use App\TeamWork;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Html;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuthController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Registration & Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users, as well as the
  | authentication of existing users. By default, this controller uses
  | a simple trait to add these behaviors. Why don't you explore it?
  |
  */

  use AuthenticatesAndRegistersUsers, ThrottlesLogins;

  protected $guard = 'teamWorks';

  protected $loginView = 'teamWork.auth.login';

  protected $registerView = 'teamWork.auth.register';

  /**
  * Where to redirect users after login / registration.
  *
  * @var string
  */
  protected $redirectTo = '/teamWork/dashboard';

  /**
  * Create a new authentication controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
  }

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {
    return Validator::make($data, [

    ]);
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return User
  */
  protected function create(Request $request)
  {
    $this->validate($request, [
      'name' => 'required|min:4|max:255',
      'job' => 'required|min:4|max:255',
      'phoneNo' => 'required|numeric|unique:teamworks',
      'email' => 'required|email|unique:teamworks',
    ]);

    $success = '';
    $td = '';
    $member = new TeamWork();
    $member->name = $request->name.'@'.Auth::user()->name.'.gudi';
    $member->job = $request->job;
    $member->phoneNo = $request->phoneNo;
    $member->email = $request->email;
    $member->password = bcrypt($request->password);
    $member->image = 'src/frontend/dist/img/avatar'.rand(1,5).'.png';
    $member->back_image = 'src/frontend/dist/img/photo'.rand(1,2).'.png';
    $member->user_id = Auth::user()->id;
    $memberSave = $member->save();
    // $this->userDirs($user); // run the userrs directiry function
    if($memberSave){
      $success .= '<div class="alert alert-success">';
      $success .= '<button type="button" aria-hidden="true" class="close">×</button>';
      $success .= '<span><b> Success - </b> the Member Successfully Join To Your Team A message Will Arrive o Him Mail Soon</span>';
      $success .= '</div>';
      //add the new record
      $td .= '<td data-name="'.$member->name.'" data-id="'.$member->id.'">'.$member->name.'</td>';
      $td .= '<td data-email="'.$member->email.'">'.$member->email.'</td>';
      $td .= '<td data-phoneNo="'. $member->phoneNo .'">'. $member->phoneNo .'</td>';
      $td .= '<td data-job="'. $member->job .'">'. $member->job .'</td>';
      $td .= '<td>'. Html::image($member->image) .'</td>';
      $td .= ' <td>'. $member->created_at->format("Y.m.d") .'</td>';
      $td .= '<td>';
      $td .= '<span class="btn btn-info btn-block editTeam"><i class="fa fa-edit"></i>Edit</span>';
      $td .= '<span class="btn btn-danger btn-block deleteMember"><i class="fa fa-close"></i>delete</span>';
      $td .= '</td>';

      return response()->json(['success' => $success, 'user' => $td], 200);
    }
  }

  protected function editMember(Request $request)
  {
    $member = TeamWork::find($request->id);
    $this->validate($request, [
      'name' => 'required|min:4|max:255',
      'job' => 'required|min:4|max:255',
      'phoneNo' => 'required|numeric',
      'email' => 'required|email',
    ]);
    $success = '';

    $member->name = $request->name;
    $request->name !== '' ? $member->name = $request->name : '';
    $member->job = $request->job;
    $member->phoneNo = $request->phoneNo;
    $member->email = $request->email;
    $member->password = bcrypt($request->password);
    $memberUpdate = $member->update();
    if($memberUpdate){
      $success .= '<div class="alert alert-info">';
      $success .= '<button type="button" aria-hidden="true" class="close">×</button>';
      $success .= '<span><b> Success - </b> the Member Successfully updated </span>';
      $success .= '</div>';

      return response()->json(['successEdit' => $success], 200);
    }
  }

  protected function getTeamWorkDelete(Request $request)
  {
    $id = $request->id;
    $success = '';
    $fail = '';
    $member = TeamWork::find($id);
    if(!$member){
      $fail .= '<div class="alert alert-danger">';
      $fail .= '<button type="button" aria-hidden="true" class="close">×</button>';
      $fail .= '<span><b> Success - </b> this Id not found</span>';
      $fail .= '</div>';
      return response()->json(['fail' => $fail], 200);
    }

    $deleteMember = $member->delete();
    if($deleteMember){
      $success .= '<div class="alert alert-warning">';
      $success .= '<button type="button" aria-hidden="true" class="close">×</button>';
      $success .= '<span><b> Success - </b> the Member Successfully deleted</span>';
      $success .= '</div>';
      return response()->json(['successDelete' => $success], 200);
    }
  }

  /**
  * Create a fresh directories for the registered user.
  *
  * @param  Object  $user
  * @var make the main and nested dirs
  * @var Main directory => contain all the users Directories and files
  * @var files directory => contain all the files
  * @var img directory => contain all the image files
  */
  // protected function userDirs($user)
  // {
  //   if ($user) {
  //     // main directory path
  //     $path = public_path() . '/src/users/user@'.$user->id;
  //     // files directory path
  //     $pathFiles = public_path() . '/src/users/user@'.$user->id.'/files';
  //     // image directory path
  //     $pathImg = public_path() . '/src/users/user@'.$user->id.'/img';
  //     if (!file_exists($path)) {
  //       // create the main directory
  //       $oldmask = umask(0);
  //       $dir = mkdir($path, 0777);
  //       umask($oldmask);
  //     }
  //     if (!file_exists($pathFiles)) {
  //       // make the files directory
  //       $oldmask = umask(0);
  //       $file = mkdir($pathFiles, 0777);
  //       umask($oldmask);
  //     }
  //     if (!file_exists($pathImg)) {
  //       // make the img directory
  //       $oldmask = umask(0);
  //       $img = mkdir($pathImg, 0777);
  //       umask($oldmask);
  //     }
  //   }
  // }
}