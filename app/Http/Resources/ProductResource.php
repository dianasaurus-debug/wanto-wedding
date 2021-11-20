<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'nama'=>$this->nama,
            'cover'=> $this->cover,
            'harga' => $this->harga,
            'deskripsi' => $this->deskripsi,
            'galeri' => $this->media,
            'category_id' => $this->category_id,
            'category_name' => $this->category->nama_kategori,
            'category' => $this->category
        ];
    }
}
