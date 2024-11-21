<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $documents = Document::where('user_id', auth()->id())->get();
        return view('dashboard', compact('user', 'documents'));
    }
}
