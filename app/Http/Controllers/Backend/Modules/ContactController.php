<?php

namespace App\Http\Controllers\Backend\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('admin.modules.contact.index');
    }
}
