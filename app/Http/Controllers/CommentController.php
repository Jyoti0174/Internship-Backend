<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET /api/tickets/{id}/comments
    public function index($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        $comments = $ticket->comments()->with('user:id,name,email')->get();

        return response()->json([
            'success' => true,
            'message' => 'Comments fetched successfully.',
            'data' => $comments,
        ], 200);
    }

    // POST /api/tickets/{id}/comments
    public function store(StoreCommentRequest $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        $comment = $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);

        $comment->load('user:id,name,email');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'data' => $comment,
        ], 201);
    }

    // PUT /api/tickets/{id}/comments/{commentId}
    public function update(StoreCommentRequest $request, $ticketId, $commentId)
    {
        $comment = Comment::where('ticket_id', $ticketId)->find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found.',
            ], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this comment.',
            ], 403);
        }

        $comment->update(['body' => $request->body]);
        $comment->load('user:id,name,email');

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'data' => $comment,
        ], 200);
    }

    // DELETE /api/tickets/{id}/comments/{commentId}
    public function destroy(Request $request, $ticketId, $commentId)
    {
        $comment = Comment::where('ticket_id', $ticketId)->find($commentId);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found.',
            ], 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this comment.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ], 200);
    }
}