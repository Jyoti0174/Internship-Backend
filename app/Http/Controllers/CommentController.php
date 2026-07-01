<?php

namespace App\Http\Controllers;

use App\Mail\CommentAddedMail;
use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    use ApiResponse;

    // GET /api/tickets/{id}/comments
    public function index($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', 404);
        }

        $comments = $ticket->comments()->with('user:id,name,email')->get();

        return $this->successResponse($comments, 'Comments fetched successfully.');
    }

    // POST /api/tickets/{id}/comments
    public function store(StoreCommentRequest $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', 404);
        }

        $comment = $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->body,
        ]);

        $comment->load('user:id,name,email');

        ActivityLog::record(
            $ticket->id,
            $request->user()->id,
            'comment_added',
            $request->user()->name . ' added a comment.'
        );

        // Send email to ticket creator
        if ($ticket->user && $ticket->user->email) {
            Mail::to($ticket->user->email)->send(new CommentAddedMail($ticket, $comment));
        }

        return $this->successResponse($comment, 'Comment added successfully.', 201);
    }

    // PUT /api/tickets/{id}/comments/{commentId}
    public function update(StoreCommentRequest $request, $ticketId, $commentId)
    {
        $comment = Comment::where('ticket_id', $ticketId)->find($commentId);

        if (!$comment) {
            return $this->errorResponse('Comment not found.', 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return $this->errorResponse('You are not authorized to update this comment.', 403);
        }

        $comment->update(['body' => $request->body]);
        $comment->load('user:id,name,email');

        return $this->successResponse($comment, 'Comment updated successfully.');
    }

    // DELETE /api/tickets/{id}/comments/{commentId}
    public function destroy(Request $request, $ticketId, $commentId)
    {
        $comment = Comment::where('ticket_id', $ticketId)->find($commentId);

        if (!$comment) {
            return $this->errorResponse('Comment not found.', 404);
        }

        if ($comment->user_id !== $request->user()->id) {
            return $this->errorResponse('You are not authorized to delete this comment.', 403);
        }

        $comment->delete();

        return $this->successResponse(null, 'Comment deleted successfully.');
    }
}