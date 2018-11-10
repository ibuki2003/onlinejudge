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
            'sender' => $this->sender,
            'status' => $this->status,
            'point' => $this->point,
            'lang' => Lang::find($this->lang)->name,
            'size' => $this->size,
            'time' => $this->time,
        ];
    }
}
