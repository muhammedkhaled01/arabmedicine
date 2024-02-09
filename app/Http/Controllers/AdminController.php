<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Courses;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{


    public function index()
    {
        $num = 4;
        $user = auth()->user();
        if ($user->role == 'admin') {
            $num = 3;
            $courses = count(Courses::all());
            $lessons = count(Lesson::all());
            $enroll = count(UserCourse::all());
            $users = count(User::whereNull('role')->get());
        } else {
            $c = Courses::where('created_by', $user->id)->withCount('lessons')->get();
            $lcounter = 0;
            foreach ($c as $i) {
                $lcounter += $i->lessons_count;
            }

            $courses = count($c);
            $lessons = $lcounter;
            $enroll = null;
            $users = count(User::whereNull('role')->get());
        }

        return view('dashboard.home-dashboard', compact('courses', 'lessons', 'enroll', 'users', 'num'));
    }

    public static function isAdmin()
    {
        //        $user = DB::table('users');
        $user = Auth::user();
        if (!$user) {
            return false;
        } else {
            if ($user->role == 'admin') {
                return true;
            } else {
                return false;
            }
        }
    }

    public function historyEnroll()
    {
        if (request()->ajax()) {
            $data = UserCourse::leftJoin('users', 'users.id', '=', 'user_id')->leftJoin('courses', 'courses.id', '=', 'course_id')->orderBy('user_courses.id', 'desc')->get(['user_courses.id as cid', 'user_courses.status', 'users.*', 'courses.*']);
            // foreach($data as $item){
            //     $item->profile_photo_path=asset($item->profile_photo_path?:'images/logo.png');;
            // }
            return datatables()->of($data)->addIndexColumn()->make(true);
        }
        return view('dashboard.enrollment-history');
    }

    public function deleteUserCourse($id)
    {
        UserCourse::find($id)->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function changeStatus($id)
    {
        $c = UserCourse::find($id);
        $c->status = $c->status == 1 ? 0 : 1;
        $c->save();
        return response()->json([
            'success' => true
        ]);
    }

    public function addStudent()
    {
        $users = User::all()->except(Auth::id(1));
        $courses = Courses::all();
        $modules = Module::leftJoin('courses', 'module_id', '=', 'modules.id')
            ->leftJoin('users', 'users.id', '=', 'courses.created_by')
            ->whereNotNull('courses.module_id')
            ->select('users.id as user_id', DB::raw('concat(users.firstname," ",users.lastname) as user_name'), 'modules.id as module_id', 'modules.name as module_name')
            ->groupBy('user_id', 'user_name', 'modules.id', 'module_name')
            ->get();

        return view('dashboard.add-student', compact('users', 'courses', 'modules'));
    }

    public function enrollmentStudent(Request $request)
    {
        $failed_users = [];
        if ($request->users) {
            if (count($request->users) > 0) {
                foreach ($request->users as $userId) {
                    if (isset($request->enroll_course)) {
                        $is_enrolled = UserCourse::where('user_id', $userId)->where('course_id', $request->course_id)->first();
                        if (!$is_enrolled) {
                            $enroll = new UserCourse();
                            $enroll->course_id = $request->course_id;
                            $enroll->user_id = $userId;
                            $enroll->status = $request->status;
                            $enroll->save();
                        } else {
                            array_push($failed_users, $userId);
                        }
                    }
                    if (isset($request->enroll_module)) {
                        if ($request->module) {
                            $module_id = explode('/', $request->module)[0];
                            $user_id = explode('/', $request->module)[1];
                            $courses = Courses::where('created_by', $user_id)->where('module_id', $module_id)->get();
                            foreach ($courses as $course) {
                                $is_enrolled = UserCourse::where('user_id', $userId)->where('course_id', $course->id)->first();
                                if (!$is_enrolled) {
                                    $enroll = new UserCourse();
                                    $enroll->course_id = $course->id;
                                    $enroll->user_id = $userId;
                                    $enroll->status = $request->status;
                                    $enroll->save();
                                } else {
                                    array_push($failed_users, $userId);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (count($request->users) == count($failed_users)) {
            $message = "";
        } elseif (count($failed_users) == 0) {
            $message = 'Student(s) added successfully';
        } else {
            $message = "Student(s) added successfully and<br>";
        }
        foreach ($failed_users as $userId) {
            $user = User::find($userId);
            $message .= $user->firstname . ' ' . $user->lastname . " is already linked to this course <br>";
        }
        return redirect()->back()->with(['success' => $message]);
    }

    public function settings()
    {
        $user = Auth::user();
        // if ($user->role == 'admin') {
        //     return view('dashboard.setting', compact('user'));
        // } else {
        return view('setting', compact('user'));
        // }
    }

    public function updateUser(Request $request)
    {
        $currentUser = Auth::user();
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $currentUser->id,
            'password' => 'confirmed'
        ];
        $messages = [
            'firstname.required' => 'الأسم الأول مطلوب',
            'lastname.required' => 'الأسم الأخير مطلوب',
            'email.required' => 'الايميل الأول مطلوب',
            'email.unique' => 'الايميل موجود مسبقا',
            'password.confirmed' => 'كلمة المرور غير مطابقة'
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            $messages
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        $data = $request->all();
        if (request('profile_photo_path')) {
            $file_extension = request('profile_photo_path')->getClientOriginalExtension();
            $file_name = "profiles-" . time() . '.' . $file_extension;
            $path = 'uploaded/';
            $request->profile_photo_path->move($path, $file_name);
            $data['profile_photo_path'] =  $path . $file_name;
            $replaced_url = route('home');
            $img_file=str_replace($replaced_url . "/", "", $currentUser->profile_photo_path);
            if ($currentUser->profile_photo_path != null && !str_contains($currentUser->profile_photo_path, 'logo.png')&&file_exists($img_file)) {
                unlink($img_file);
            }
        }
        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $currentUser->update($data);
        return redirect()->back()->with(['success' => 'Changes has been changed successfully']);
    }
    public function removeImage(Request $request)
    {
        $user = Auth::user();
        $replaced_url = route('home');
        $img_file=str_replace($replaced_url . "/", "", $user->profile_photo_path);
        if ($user->profile_photo_path != null && !str_contains($user->profile_photo_path, 'logo.png')&&file_exists($img_file)) {
            unlink($img_file);
            $user->profile_photo_path = null;
        }
        $user->save();
        return redirect()->back();
    }
}
