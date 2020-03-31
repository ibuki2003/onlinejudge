<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class Contest extends Model
{
    use Sortable;
    protected $sortable = ['id', 'start_time', 'end_time'];
    protected $fillable = ['title', 'description', 'creator', 'penalty', 'problem_ids', 'problem_points', 'start_time', 'end_time'];
    protected $dateFormat='Y-m-d H:i:s';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $perPage = 30;

    /**
     * @inheritdoc
     */
    public static function create(array $data) {
        $problem_ids = "";
        $problem_points = "";
        foreach ($data['problems'] as $problem) {
            $problem_ids .= $problem['id'] . ',';
            $problem_points .= $problem['point'] . ',';
        }
        $data['problem_ids'] = substr($problem_ids, 0, -1);
        $data['problem_points'] = substr($problem_points, 0, -1);
        $data['creator'] = auth()->id();
        $model = static::query()->create($data);
        $id=$model->id;
        return $model;
    }
    public function edit(array $data) {
        $problem_ids = "";
        $problem_points = "";
        foreach ($data['problems'] as $problem) {
            $problem_ids .= $problem['id'] . ',';
            $problem_points .= $problem['point'] . ',';
        }
        $data['problem_ids'] = substr($problem_ids, 0, -1);
        $data['problem_points'] = substr($problem_points, 0, -1);

        $this->update($data);
    }

    public function participate() {
        $this->users()->attach(auth()->id());
    }
    public function cancel_participate() {
        $this->users()->detach(auth()->id());
    }
    public function can_participate() {
        return auth()->user()->has_permission('submit') &&
               !$this->users()->where('user_id', auth()->id())->exists() && // not participated yet
               strtotime(date("Y-m-d H:i:s")) < strtotime($this->end_time); // still not ended
    }
    public function can_cancel_participate() {
        return $this->users()->where('user_id', auth()->id())->exists() &&
               strtotime(date("Y-m-d H:i:s")) < strtotime($this->start_time); // cannot cancel participation once the contest started
    }
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
