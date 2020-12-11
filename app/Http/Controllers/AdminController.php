<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Page;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $user = Auth::user();
        $pages = Page::where('id_user', $user->id)->get();

        return view('admin.index', [
            'pages' => $pages, 
        ]);
    }
}
