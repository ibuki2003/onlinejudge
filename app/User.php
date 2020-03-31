<?php

namespace App;

use App\Models\Contest;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'password', 'permission',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var string $permission
     * @return bool
     */
    public function has_permission(string $permission){
        $permbit=0;
        switch($permission){
            case 'submit':
                $permbit=1;
                break;
            case 'create_problem':
                $permbit=2;
                break;
            case 'create_contest':
                $permbit=4;
                break;
            case 'admit_users':
                $permbit=8;
                break;
        }
        return !!($this->permission & $permbit);
    }

    public function problems(){
        return $this->hasMany('App\Models\Problem');
    }

    public function submissions(){
        return $this->hasMany('App\Models\Submission');
    }

    public function contests() {
        return $this->belongsToMany(Contest::class);
    }
}
