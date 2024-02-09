<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Module;
use App\Models\PriceModule;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function getInstructorsData()
    {
        $data = User::whereNotNull('role')->has('owned_active_courses')->get();
        // foreach($data as $user){
        //     if(!$user->profile_photo_path){
        //         $user->profile_photo_path="images/logo.png";
        //     }
        // }
        $response = $data->toArray();
        return response($response, 200);
    }
    public function getModulesData($ins_id)
    {
        $data = Module::leftJoin('courses', 'courses.module_id', '=', 'modules.id')
            ->where('courses.created_by', $ins_id)
            ->where('courses.is_archived', '0')
            ->where('modules.is_archived', '0')
            ->select('courses.created_by as instructorId', 'modules.id', 'modules.name', 'modules.image as image')
            ->groupBy('id', 'name', 'image', 'instructorId')
            ->get();

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getCoursesByInstructor($ins_id)
    {
        $data = Courses::where('is_archived', '0')->where('created_by', $ins_id)->has('section')->get();

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getCoursesByModule($module_id)
    {
        $data = Courses::where('is_archived', '0')->where('module_id', $module_id)->has('section')->get();

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getCoursesByInstructorModule($ins_id, $module_id)
    {
        $data = Courses::where('is_archived', '0')->where('created_by', $ins_id)->where('module_id', $module_id)->has('section')->get();

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getModules()
    {
        $data = Module::where('is_archived', '0')->with('instructors')->has('instructors')->orderBy('updated_at', 'desc')->get();
        foreach ($data as $item) {
            foreach ($item->instructors as $i) {
                $pricemodule = PriceModule::where('module_id', $item->id)->where('user_id', $i->id)->first();
                if ($pricemodule) {
                    $i->module_price = $pricemodule->price;
                } else {
                    $i->module_price = null;
                }
            }
        }
        $response = $data->toArray();
        return response($response, 200);
    }

    public function getCourses()
    {
        $data = Courses::where('is_archived', '0')->with('owner')->has('section')->orderBy('updated_at', 'desc')->get();

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getCourse($id)
    {
        $user = auth()->user();
        $data = Courses::where('id', $id)->where('is_archived', '0')->with('section')->has('section')->get();
        foreach ($data as $item) {
            $enrollment = UserCourse::where('user_id', $user->id)->where('course_id', $item->id)->where('status', '1')->first();
            if ($enrollment) {
                $item->is_enrolled = true;
            } else {
                $item->is_enrolled = false;
                //$item->is_enrolled = true;
            }
        }

        $response = $data->toArray();
        return response($response, 200);
    }

    public function getEnrolledCourses()
    {
        $user = auth()->user();
        $data = User::where('id', $user->id)->with('enrolled_courses')->first();
        $response = $data->enrolled_courses->toArray();
        
        foreach ($response as &$course) {
            $course['price'] = strval($course['price']);
        }
        // $data = Courses::where('is_archived', '0')->has('section')->orderBy('updated_at', 'desc')->get();
        // foreach($data as $item){
        //     unset($item->created_by);
        // }
        // $response = $data->toArray();


        return response($response, 200);
    }

    public function search(Request $request)
    {
        $word = $request->word;
        $field = $request->field;
        $data = [];
        switch ($field) {
            case 'instructors':
                $data = User::whereNotNull('role')->has('owned_active_courses')->where(DB::raw('concat(firstname," ",lastname)'), 'like', '%' . $word . '%')->get();
                break;
            case 'modules':
                $data = Module::where('is_archived', '0')->where('modules.name', 'like', '%' . $word . '%')->with('instructors')->has('instructors')->orderBy('updated_at', 'desc')->get();
                foreach ($data as $item) {
                    foreach ($item->instructors as $i) {
                        $pricemodule = PriceModule::where('module_id', $item->id)->where('user_id', $i->id)->first();
                        if ($pricemodule) {
                            $i->module_price = $pricemodule->price;
                        } else {
                            $i->module_price = null;
                        }
                    }
                }
                break;
            case 'courses':
                $data = Courses::where('is_archived', '0')->where('courses.name', 'like', '%' . $word . '%')->with('owner')->has('section')->orderBy('updated_at', 'desc')->get();
                break;
            default:
                $data = 'Field error';
        }
        $response = $data;
        return response($response, 200);
    }
}
