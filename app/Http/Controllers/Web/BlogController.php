<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Mostrar la página principal del blog
     */
    public function noticias()
    {
        return view('blog.noticias');
    }
}
