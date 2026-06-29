<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Attachment;
use App\Http\Requests\StoreAttachmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    // GET /api/tickets/{id}/attachments
    public function index($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        $attachments = $ticket->attachments()->with('user:id,name,email')->get();

        return response()->json([
            'success' => true,
            'message' => 'Attachments fetched successfully.',
            'data' => $attachments,
        ], 200);
    }

    // POST /api/tickets/{id}/attachments
    public function store(StoreAttachmentRequest $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        $attachment = $ticket->attachments()->create([
            'user_id' => $request->user()->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        $attachment->load('user:id,name,email');

        return response()->json([
            'success' => true,
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment,
        ], 201);
    }

    // GET /api/tickets/{id}/attachments/{attachmentId}/download
    // GET /api/attachments/{id}/download
public function download($id)
{
    $attachment = Attachment::find($id);

    if (!$attachment) {
        return response()->json([
            'success' => false,
            'message' => 'Attachment not found.',
        ], 404);
    }

    if (!Storage::disk('public')->exists($attachment->file_path)) {
        return response()->json([
            'success' => false,
            'message' => 'File not found on server.',
        ], 404);
    }

    return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
}

    // DELETE /api/tickets/{id}/attachments/{attachmentId}
    public function destroy(Request $request, $ticketId, $attachmentId)
    {
        $attachment = Attachment::where('ticket_id', $ticketId)->find($attachmentId);

        if (!$attachment) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found.',
            ], 404);
        }

        if ($attachment->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this attachment.',
            ], 403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
        ], 200);
    }
}