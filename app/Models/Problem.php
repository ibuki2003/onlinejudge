<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use \DateTime;

class Problem extends Model
{
    protected $fillable = ['title', 'creator', 'difficulty'];

    /**
     * filters only visible problem
     */
    public function scopeVisibleFilter($query){
        return $query->whereNull('open')
                    ->orWhereTime('open', '<=', 'now()')
                    ->orWhere('creator', auth()->id());
    }

    /**
     * returns whether the problem opened.
     * @return bool
     */
    public function is_opened(){
        $opentime=$this->open;
        if($opentime===NULL)return true;
        $opentime=new DateTime($opentime);
        return $opentime<=new DateTime();
    }

    /**
     * returns whether the problem visible for the user.
     * @return bool
     */
    public function is_visible(){
        if($this->is_opened())return true;
        return $this->creator==auth()->id();
    }

    /**
     * returns problem sentence markdown
     * @return string
     */
    public function get_content(){
        return Storage::disk('data')->get('problems/'.$this->id.'/main.md');
    }

    /**
     * returns whether the problem has editorial
     * @return bool
     */
    public function has_editorial(){
        return Storage::disk('data')->exists('problems/'.$this->id.'/main.md');
    }

    /**
     * returns problem editorial markdown
     * @return string|null
     */
    public function get_editorial(){
        if(!$this->has_editorial())return NULL;
        return Storage::disk('data')->get('problems/'.$id.'/main.md');
    }
}
