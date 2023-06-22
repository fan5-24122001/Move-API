<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'email' => $this -> email,
            'img' => $this->img,
            'gender' => $this->gender,
            'address' => $this->address,
            'birthday' => $this->birthday,
            'kol' => $this->kol,
        ];
    }
}
