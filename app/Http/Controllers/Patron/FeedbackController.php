<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('patron.feedback');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'required|string',
        ]);

        Feedback::create($request->only('name', 'email', 'message'));

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
