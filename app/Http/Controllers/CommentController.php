<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function store(Request $request, Artesania $artesania)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->artesania_id = $artesania->id;
        $comment->content = $request->input('content');
        $comment->rating = $request->input('rating');
        $comment->status = 'pending'; // ¡Importante para la moderación!
        $comment->save();

        return back()->with('success', __('messages.review_submitted_success'));
    
    

    }
       
}
