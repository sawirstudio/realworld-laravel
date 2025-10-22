<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class ListTagsController extends Controller
{
    /**
     * @unauthenticated
     */
    public function __invoke()
    {
        return Tag::query()->get()->toResourceCollection();
    }
}
