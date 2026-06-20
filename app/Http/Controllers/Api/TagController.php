<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Support\ApiResponse;

class TagController extends Controller
{
    public function index()
    {
        return ApiResponse::success(['tags' => TagResource::collection(Tag::orderBy('name')->get())]);
    }
}
