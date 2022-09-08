<?php

namespace App\Http\Controllers\Backend\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.modules.gallery.index');
    }
}
