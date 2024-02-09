<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Module;

class Courses extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $guarded = [];

    public function section()
    {
        return $this->hasMany('App\Models\Section', 'course_id', 'id')->with(['lesson' => function($q){
            $q->orderBy('order');
        }]);
    }
    
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class,Section::class,'course_id');
    }
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_courses', 'course_id', 'user_id', 'id', 'id');
    }
    public function authorized_users(){
        return $this->belongsToMany(User::class, 'course_modifiers','course_id','user_id');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }

    
    protected function getImageAttribute($value)
    {
        $path='';
        if($value){
            if(str_contains($value,'http://')||str_contains($value,'https://')){
                $path=$value;
            }else{
                $path='images/courses/'.$value;
            }
        }else{
            $path='images/logo.png';
        }
        return asset($path);
    }
}
