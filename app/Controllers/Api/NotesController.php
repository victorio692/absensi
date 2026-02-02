<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class NotesController extends ResourceController
{
    protected $modelName = 'App\Models\NotesModel';
    protected $format    = 'json';

    /**
     * GET /api/notes
     * Get user's unread notes
     */
    public function index()
    {
        if (!session()->has('user_id')) {
            return $this->failUnauthorized('Not logged in');
        }

        $notes = $this->model->getUnreadNotes();

        return $this->respond([
            'status' => 'success',
            'data'   => $notes,
            'count'  => count($notes),
        ]);
    }

    /**
     * POST /api/notes/{id}/read
     * Mark note as read
     */
    public function markRead($id = null)
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        if (!$id) {
            return $this->fail('Note ID is required');
        }

        $note = $this->model->find($id);

        if (!$note) {
            return $this->failNotFound('Note not found');
        }

        // Ensure user can only mark their own notes
        if ($note['user_id'] != $userId) {
            return $this->failForbidden('Cannot modify other users notes');
        }

        $this->model->markAsRead($id);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Note marked as read',
            'note_id' => $id,
        ]);
    }

    /**
     * DELETE /api/notes/{id}
     * Delete note
     */
    public function delete($id = null)
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->failUnauthorized('Not logged in');
        }

        if (!$id) {
            return $this->fail('Note ID is required');
        }

        $note = $this->model->find($id);

        if (!$note) {
            return $this->failNotFound('Note not found');
        }

        // Ensure user can only delete their own notes
        if ($note['user_id'] != $userId) {
            return $this->failForbidden('Cannot delete other users notes');
        }

        $this->model->deleteNote($id);

        return $this->respondDeleted([
            'status'  => 'success',
            'message' => 'Note deleted',
            'note_id' => $id,
        ]);
    }
}
