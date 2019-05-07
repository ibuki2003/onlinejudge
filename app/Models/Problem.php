<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use \DateTime;
use PHPUnit\Framework\Constraint\Exception;
use Psy\Exception\ErrorException;
use \ZipArchive;
use App\User;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

function validate_tcsets($json_text){
    $json = json_decode($json_text);
    if(!is_array($json))return false;
    foreach($json as $val){
        if(!isset($val->name))return false;
        if(!isset($val->point))return false;
        if(!isset($val->problems))return false;
        if(!is_array($val->problems))return false;
    }
    return true;
}

class Problem extends Model
{
    use Sortable;
    protected $sortable = ['id', 'difficulty', 'user_id'];
    protected $fillable = ['title', 'user_id', 'difficulty', 'open'];
    protected $hidden = ['open'];
    protected $dates = ['open'];
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $perPage = 30;

    /**
     * @inheritdoc
     */
    public static function create(array $data, array $files) {
        $data['user_id']=auth()->id();
        if($data['open']!==NULL)$data['open']=new Datetime($data['open']);

        $filepath = storage_path( 'app/' . $files['zip_content']->store('uploads') );
        $zip = new ZipArchive;
        abort_unless($zip->open($filepath) === TRUE, 400, __('ui.problem.invalid_zip'));

        abort_if($zip->locateName('main.md') === FALSE, 400, __('ui.problem.zip_not_found', ['filename' => 'main.md']));
        abort_if($zip->locateName('in/'    ) === FALSE, 400, __('ui.problem.zip_not_found', ['filename' => 'in']));
        abort_if($zip->locateName('out/'   ) === FALSE, 400, __('ui.problem.zip_not_found', ['filename' => 'out']));

        $str=$zip->getFromName('tcsets.json');
        if($str!==FALSE){
            abort_unless(validate_tcsets($str), 400, __('ui.problem.invalid_tcsets'));
        }

        $model = static::query()->create($data);
        $id=$model->id;
        $files['zip_content']->storeAs('', 'problems/'.$id.'.zip', 'data');
        Storage::disk('data')->makeDirectory('problems/'.$id);
        Storage::disk('data')->zipExtractTo($zip, 'problems/'.$id);
        unlink($filepath);
        return $model;
    }

    /**
     * edit problem with given data
     */
    public function edit(array $data, array $files) {
        $this->title = $data['title'];
        $this->difficulty = $data['difficulty'];
        if ($data['open'] !== NULL) $this->open=new Datetime($data['open']);
        else $this->open=NULL;

        if (array_key_exists('zip_content', $files)) {
            $filepath = storage_path( 'app/' . $files['zip_content']->store('uploads') );
            $zip = new ZipArchive;
            abort_unless($zip->open($filepath) === TRUE, 400, __('ui.problem.invalid_zip'));
            $files['zip_content']->storeAs('', 'problems/'.$this->id.'.zip', 'data');
            $base_dir='problems/' . $this->id . '/';
            if($zip->locateName('in/')!==FALSE)
                Storage::disk('data')->deleteDirectory($base_dir . 'in');
            if($zip->locateName('out/')!==FALSE)
                Storage::disk('data')->deleteDirectory($base_dir . 'out');

            if(Storage::disk('data')->exists($base_dir . 'judge'))
                Storage::disk('data')->delete($base_dir . 'judge');
            $str=$zip->getFromName('tcsets.json');
            if($str!==FALSE){
                abort_unless(validate_tcsets($str), 400, __('ui.problem.invalid_tcsets'));
            }

            Storage::disk('data')->zipExtractTo($zip, $base_dir);
            unlink($filepath);
        }

        $model = static::query()->where('id', $this->id);
        $model->update(['title'=>$this->title,
                        'difficulty'=>$this->difficulty,
                        'open'=>$this->open]);
        return $this;
    }

    /**
     * filters only visible problem
     */
    public function scopeVisibleFilter($query){
        return $query->whereNull('open')
                    ->orWhere('open', '<=', Carbon::now())
                    ->orWhere('user_id', auth()->id());
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
        return $this->user_id==auth()->id();
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
        return Storage::disk('data')->exists('problems/'.$this->id.'/editorial.md');
    }

    /**
     * returns problem editorial markdown
     * @return string|null
     */
    public function get_editorial(){
        if(!$this->has_editorial())return NULL;
        return Storage::disk('data')->get('problems/'.$this->id.'/editorial.md');
    }

    public function download_zip(){
        if(!Storage::disk('data')->exists('problems/'.$this->id.'.zip'))return NULL;
        return Storage::disk('data')->download('problems/'.$this->id.'.zip', ''.$this->id.'.zip');
    }

    /**
     * returns whether the problem solved by user
     * @param \App\User $user
     * @return bool
     */
    public function solved_by(User $user){
        return Submission::Where('user_id', $user->id)->Where('problem_id',$this->id)->Where('status','AC')->limit(1)->count()!=0;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function submissions(){
        return $this->hasMany('App\Models\Submission');
    }
}
