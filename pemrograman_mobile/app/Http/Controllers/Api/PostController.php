<?php

namespace App\Http\Controllers\Api;

//import model
use App\Models\Hewan;

use App\Http\Controllers\Controller;

//import resource PostResource
use App\Http\Resources\PostResource;

class PostController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $hewan = Post::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $hewan);
    }
}