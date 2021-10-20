<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PostResource::collection($this->collection),
            'links' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'next_page' => $this->nextPageUrl(),
                'previous_page' => $this->previousPageUrl(),
            ],

        ];
    }
}



