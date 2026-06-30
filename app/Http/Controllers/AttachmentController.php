<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\Attachment;
use App\Http\Requests\StoreAttachmentRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    use ApiResponse;

    // GET /api/tickets/{id}/attachments
    public function index($ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', 404);
        }

        $attachments = $ticket->attachments()->with('user:id,name,email')->get();

        return $this->successResponse($attachments, 'Attachments fetched successfully.');
    }

    // POST /api/tickets/{id}/attachments
    public function store(StoreAttachmentRequest $request, $ticketId)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', 404);
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

        ActivityLog::record(
            $ticket->id,
            $request->user()->id,
            'attachment_added',
            $request->user()->name . " uploaded an attachment: {$attachment->file_name}."
        );

        return $this->successResponse($attachment, 'Attachment uploaded successfully.', 201);
    }

    // GET /api/attachments/{id}/download
    public function download($id)
    {
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return $this->errorResponse('Attachment not found.', 404);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return $this->errorResponse('File not found on server.', 404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    // DELETE /api/tickets/{id}/attachments/{attachmentId}
    public function destroy(Request $request, $ticketId, $attachmentId)
    {
        $attachment = Attachment::where('ticket_id', $ticketId)->find($attachmentId);

        if (!$attachment) {
            return $this->errorResponse('Attachment not found.', 404);
        }

        if ($attachment->user_id !== $request->user()->id) {
            return $this->errorResponse('You are not authorized to delete this attachment.', 403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return $this->successResponse(null, 'Attachment deleted successfully.');
    }
}