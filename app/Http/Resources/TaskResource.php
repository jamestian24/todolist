<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'task_descr'=>$this->task_descr,
            'member_id'=>$this->member_id,
            'member_name'=> isset($this->member) ? $this->member->name : null,
            'member_group'=> isset($this->member) ? $this->member->group : null,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at
        ];
    }
}
