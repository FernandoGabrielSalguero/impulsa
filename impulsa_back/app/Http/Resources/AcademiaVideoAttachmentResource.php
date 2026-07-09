<?php

namespace App\Http\Resources;

use App\Models\AcademiaVideoAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AcademiaVideoAttachment */
class AcademiaVideoAttachmentResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'label' => $this->label,
            'file_path' => (string) $this->file_path,
            'sort_order' => (int) $this->sort_order,
        ];
    }
}
