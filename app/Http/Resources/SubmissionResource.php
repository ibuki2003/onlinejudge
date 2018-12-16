<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Lang;

class SubmissionResource extends JsonResource
{
    /**
     * リソースを配列へ変換する
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'problem' => $this->problem,
            'sender' => $this->user_id,
            'status' => $this->status,
            'point' => $this->point,
            'lang' => $this->lang->name,
            'size' => $this->size,
            'time' => $this->time->format('Y-m-d H:i:s'),
            'exec_time' => $this->exec_time,
        ];
    }
}
