<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVersionDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $changes = [];

        foreach ($this->changed_fields as $field) {
            $changes[$field] = [
                'old' => $this->old_data[$field] ?? null,
                'new' => $this->new_data[$field] ?? null
            ];
        }

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => $this->user->name,
            'changes' => $changes
        ];
    }
}
