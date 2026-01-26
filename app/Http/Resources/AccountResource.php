<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => gravatar($this->email, 96),
            'locale' => $this->locale,
            'timezone' => $this->timezone,
            'plan' => collect($this->plan)->only(['id', 'name', 'features']),
            'brand' => $this->brand,
            'default_privacy' => $this->default_privacy,
            'default_export_detailed' => $this->default_export_detailed,
            'created_at' => $this->created_at
        ];
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => 200
        ];
    }
}
