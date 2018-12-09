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

class Problem extends Model
{
    use Sortable;
    protected $sortable = ['id', 'difficulty', 'creator'];
    protected $fillable = ['title', 'creator', 'difficulty', 'open'];
    protected $dates = ['open'];
    const CREATED_AT = null;
    const UPDATED_AT = null;


    /**
     * @inheritdoc
     */
    public static function create(array $data, array $files) {
        $data['creator']=auth()->id();
        if($data['open']!==NULL)$data['open']=new Datetime($data['open']);

        $filepath = $files['zip_content']->store('uploads');
        $filepath=storage_path('app/'.$filepath);

        abort_unless(self::is_valid_problem_zip($filepath),400,__('ui.problem.invalid_zip'));

        $model = static::query()->create($data);
        $id=$model->id;
        Storage::disk('data')->makeDirectory('problems/'.$id);
        Storage::disk('data')->extractTo('problems/'.$id.'/', $filepath);
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
            $filepath = $files['zip_content']->store('uploads');
            $filepath=storage_path('app/'.$filepath);
            abort_unless(self::is_valid_problem_zip($filepath),400,__('ui.problem.invalid_zip'));

            Storage::disk('data')->deleteDirectory('problems/'.$this->id);
            Storage::disk('data')->makeDirectory('problems/'.$this->id);
            Storage::disk('data')->extractTo('problems/'.$this->id.'/', $filepath);
            unlink($filepath);
        }

        $model = static::query()->where('id', $this->id);
        $model->update(['title'=>$this->title,
                        'difficulty'=>$this->difficulty,
                        'open'=>$this->open]);
        return $this;
    }

    /**
     * returns whether zip file valid
     * @param string $path
     * @return bool
     */
    private static function is_valid_problem_zip(string $path){
        $zip = new ZipArchive;

        if ($zip->open($path) !== TRUE) {
            return false;
        }
        $result=$zip->getFromName('main.md',1);
        $zip->close();
        return $result;
    }

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

    /**
     * returns whether the problem solved by user
     * @param \App\User $user
     * @return bool
     */
    public function solved_by(User $user){
        return Submission::Where('sender', $user->id)->Where('problem',$this->id)->Where('status','AC')->limit(1)->count()!=0;
    }
}
