<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    public function index()
    {
        return view('dashboard.addcourses');
    }

    public function store(Request $request)
    {
        $price_free = 'required';
        if ($request->has('free')) {
            $price_free = '';
        }
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image',
            'price' => $price_free,

        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        $file_extension = $request->image->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;
        $path = 'images/courses';
        $request->image->move($path, $file_name);

        Courses::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'image'=>$file_name,
            'price'=>$request->price,
            'created_by'=>auth()->user()->id,
        ]);

        return redirect()->back()->with(['success' => 'The Course added successfully']);

    }
}
