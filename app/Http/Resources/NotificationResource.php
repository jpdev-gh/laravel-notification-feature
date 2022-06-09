<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'            => $this->id,
            'type'          => $this->type,
            'title'         => $this->data['title'],
            'body'          => $this->data['body'],
            'click_action'  => $this->data['click_action'],
            'sender'    => [
                'name'  => $this->data['sender']['name'],
                'photo'   => $this->data['sender']['photo'],
            ],
            'read_at'       => [
                'self'  => is_null($this->read_at) ? $this->read_at : Carbon::parse($this->read_at)->format('Y-m-d H:i:s'),
            ],
            'created_at'    => [
                'self'  => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
                'fdy'   => Carbon::parse($this->created_at)->format('F d, Y'),
                'dfh'   => Carbon::parse($this->created_at)->diffForHumans(Carbon::now()),
            ],
            'updated_at'    => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
