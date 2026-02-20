<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EbookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'deskripsi' => $this->deskripsi,
            'cover' => $this->cover,
            'cover_url' => $this->cover ? asset($this->cover) : null,
            'file' => $this->file,
            'file_url' => $this->file ? asset($this->file) : null,
            'folder' => $this->whenLoaded('folderEbook', function () {
                return [
                    'id' => $this->folderEbook->id,
                    'folder_name' => $this->folderEbook->folder_name,
                    'slug' => $this->folderEbook->slug,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
