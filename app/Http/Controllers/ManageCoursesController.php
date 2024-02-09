<?php

namespace App\Http\Controllers;

use App\Models\CourseModifier;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Courses;
use App\Models\Section;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManageCoursesController extends Controller
{
    //
    public function index()
    {
        $showing_archived = 'all';
        if (request('is_archived') || request('is_archived') == 0) {
            $showing_archived = request('is_archived');
        }

        $user = auth()->user();
        if (request()->ajax()) {

            if ($user->role == "instructor") {
                if ($showing_archived == 'all') {
                    $data = Courses::where('created_by', $user->id)->orWhereHas('authorized_users', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->with('module')->get();
                } else {
                    $data = Courses::where('is_archived', $showing_archived)->where('created_by', $user->id)->orWhereHas('authorized_users', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })->with('module')->get();
                }
            } else {
                if ($showing_archived == 'all') {
                    $data = Courses::with(['owner', 'module'])->get();
                } else {
                    $data = Courses::where('is_archived', $showing_archived)->with(['owner', 'module'])->get();
                }
            }
            return datatables()->of($data)->addIndexColumn()->make(true);
        }
        $modules = Module::where('is_archived', '0')->get();
        return view('dashboard.courses-page', ['modules' => $modules]);
    }

    public function archiveCourse(Request $request)
    {
        $id = $request->id;
        $course = Courses::find($id);
        if ($course->is_archived == 0) {
            $course->is_archived = 1;
        } else {
            $course->is_archived = 0;
        }
        $course->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function linkCourseModule(Request $request)
    {
        $course_id = $request->course_id;
        $module_id = $request->module_id;
        $module = Module::find($module_id);
        $module->updated_at = now();
        $module->save();

        Courses::find($course_id)->update([
            'module_id' => $module_id
        ]);

        return redirect()->back();
    }
    public function editCourse($course_id)
    {
        $user = auth()->user();
        $courseId = Courses::findOrFail($course_id);
        $users = User::where('role', 'instructor')->where('id', '<>', $user->id)->where('id', '<>', $courseId->created_by)->get();
        $selectedUsers = $courseId->authorized_users()->pluck('user_id')->toArray();
        $can_add_authorize=false;
        if(($user->role == "instructor"&&$user->id==$courseId->created_by)||$user->role == "admin"){
            $can_add_authorize=true;
        }
        if ($user->role == "instructor"||$user->role == "admin") {
            $authorized = CourseModifier::where('user_id', $user->id)->first();
            if ($user->id == $courseId->created_by || $authorized||$user->role == "admin") {
                return view('dashboard.manage-courses', compact('courseId', 'users', 'selectedUsers','can_add_authorize'));
            } 
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
    public function deleteCourse($id)
    {
        $user = auth()->user();
        $success = false;
        if ($user->role == 'admin') {
            Courses::find($id)->delete();
            $success = true;
        }
        return response()->json([
            'success' => $success
        ]);
    }
    public function update(Request $request, $course_id)
    {


        //        return $request->free;

        //        Start validation
        $price_free = 'required';
        if ($request->has('free')) {
            $price_free = '';
        }
        if (isset($request->imageExist)) {
            $image_require = '';
        } else {
            $image_require = 'required|';
        }
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => $image_require . 'image',
            'price' => $price_free,

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }
        //    =====================================================

        //      Check the course

        $courses = Courses::findOrFail($course_id);

        $courseId = Courses::find($course_id);
        //    =====================================================

        //        Update the course data

        if ($request->image) {

            $file_extension = $request->image->getClientOriginalExtension();
            $file_name = time() . '.' . $file_extension;
            $path = 'images/courses';
            $request->image->move($path, $file_name);
            $courseId->image = $file_name;
        }

        $price = null;
        if (!$request->has('free')) {
            $price = $request->price;
        }

        $courseId->name = $request->name;
        $courseId->description = $request->description;
        $courseId->price = $price;
        $courseId->free = $request->has('free');

        // $courseId->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'image' => $file_name,
        //     'price' => $price,
        //     'free' => $request->has('free'),
        // ]);
        $courseId->save();

        CourseModifier::where('course_id', $courseId->id)->delete();
        if ($request->users) {
            $request->collect('users')->each(function ($userId) use($courseId) {
                CourseModifier::create([
                    'course_id' => $courseId->id,
                    'user_id' => $userId
                ]);
            });
        }
        return redirect()->back()->with(['success' => 'The Course updated successfully']);
    }


    public function createSections(Request $request)
    {
        $rules = [
            'section_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }
        //        ======================== Create Section=====================
        $data = $request->all();
        Section::create($data);
        return redirect()->back();
        //        ======================== End Create Section=====================
    }

    public function createLessons(Request $request)
    {
        $rules = [
            'lesson_name' => 'required',
            'url' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }


        $data = $request->all();

        if ($request->pdf_attach) {
            $file_extension = $request->pdf_attach->getClientOriginalExtension();
            $file_name = time() . '.' . $file_extension;
            $path = 'uploaded/attachments';
            $request->pdf_attach->move($path, $file_name);
            $data['pdf_attach'] = $file_name;
        }


        Lesson::create($data);


        return redirect()->back();
    }

    //    Start delete and update section

    public function deleteSection($id)
    {
        //        Lesson::where('course_id', $id)->delete();
        Section::find($id)->delete();
        return redirect()->back();
    }

    public function updateSection(Request $request, $id)
    {
        $section_id = Section::find($id);
        $section_id->update([
            'section_name' => $request->section_name,

        ]);
        return redirect()->back();
    }
    //    End delete and update section

    //    Start delete and update lesson

    public function deleteLesson($id)
    {

        Lesson::find($id)->delete();
        return redirect()->back();
    }

    public function updateLesson(Request $request, $id)
    {


        $rules = [
            'lesson_name' => 'required',
            'url' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }


        $data = $request->all();

        if ($request->pdf_attach) {
            $file_extension = $request->pdf_attach->getClientOriginalExtension();
            $file_name = time() . '.' . $file_extension;
            $path = 'uploaded/attachments';
            $request->pdf_attach->move($path, $file_name);
            $data['pdf_attach'] = $file_name;
        }

        Lesson::find($id)->update($data);


        //        $section_name->update([
        //            'section_name'=>$request->section_name
        //        ]);

        return redirect()->back();
    }
    public function rateCourse($course_id, $rate)
    {
        Courses::find($course_id)->update(['rate' => $rate]);
        return response()->json([
            'success' => true
        ]);
    }


    public function orderLesons(Request $request){
        foreach($request['lessons'] as $key => $value){
            $lesson = Lesson::find($value);
            if($lesson){
                $lesson->update([
                    'order' => $key,
                ]);
            }
            
        }
        return response()->json([
            'success' => true
        ]);
    }
}
