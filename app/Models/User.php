<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Courses;
use App\Models\Module;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['firstname', 'lastname', 'email', 'title', 'password', 'browser_token', 'profile_photo_path', 'device_token'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        // 'profile_photo_path'
    ];

    public function courses()
    {
        return $this->belongsToMany('App\Models\Courses', 'user_courses', 'user_id', 'course_id', 'id', 'id')->withPivot('status');
    }
    public function enrolled_courses()
    {
        return $this->courses()->where('is_archived', '0')->wherePivot('status', '=', '1')->has('lessons');
    }
    public function owned_courses()
    {
        return $this->hasMany(Courses::class, 'created_by');
    }

    public function owned_active_courses()
    {
        return $this->owned_courses()->where('is_archived', '0');
    }
    public function modules_rel()
    {
        return $this->belongsToMany(Module::class, 'courses', 'created_by', 'module_id');
    }
    public function modules()
    {
        return $this->modules_rel()->distinct();
    }
    public function authorized_courses(){
        return $this->belongsToMany(Course::class, 'course_modifiers','user_id','course_id');
    }
    protected function getProfilePhotoPathAttribute($value)
    {
        return $value ? (file_exists($value) ? asset($value) : asset('images/logo.png')) : asset('images/logo.png');
    }

    protected function getProfilePhotoUrlAttribute($value)
    {
        if (isset($this->attributes['profile_photo_path'])) {
            return  $this->attributes['profile_photo_path'] ? (file_exists($this->attributes['profile_photo_path']) ? asset($this->attributes['profile_photo_path']) : asset('images/logo.png')) : asset('images/logo.png');
        } else {
            return  asset('images/logo.png');
        }
    }
}
