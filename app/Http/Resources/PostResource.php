<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'judul'=>$this->judul,
            'cover'=> $this->cover,
            'tema_id' => $this->tema_id,
            'isi' => $this->isi,
            'created_at' => $this->created_at
        ];
    }
}
