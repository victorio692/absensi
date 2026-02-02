<?php

namespace App\Models;

use CodeIgniter\Model;

class NotesModel extends Model
{
    protected $table            = 'notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['user_id', 'title', 'message', 'type', 'is_read', 'is_permanent', 'auto_dismiss_in'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $validationRules  = [
        'title'            => 'required|string|max_length[255]',
        'message'          => 'required|string',
        'type'             => 'required|in_list[success,error,warning,info]',
        'is_permanent'     => 'boolean',
        'auto_dismiss_in'  => 'integer',
    ];

    /**
     * Buat note baru
     */
    public function addNote(array $data)
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return false; // User tidak login
        }
        
        $note = [
            'user_id'         => $userId,
            'title'           => $data['title'] ?? '',
            'message'         => $data['message'],
            'type'            => $data['type'] ?? 'info',
            'is_permanent'    => $data['is_permanent'] ?? false,
            'auto_dismiss_in' => $data['auto_dismiss_in'] ?? ($data['is_permanent'] ? 0 : 5000),
        ];

        return $this->insert($note);
    }

    /**
     * Dapatkan notes untuk user yang login
     */
    public function getUnreadNotes()
    {
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return [];
        }
        
        return $this->where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Dapatkan semua notes untuk user
     */
    public function getUserNotes($userId = null)
    {
        $userId = $userId ?? session()->get('user_id');
        
        if (!$userId) {
            return [];
        }

        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mark note as read
     */
    public function markAsRead($noteId)
    {
        return $this->update($noteId, ['is_read' => true]);
    }

    /**
     * Delete note
     */
    public function deleteNote($noteId)
    {
        return $this->delete($noteId);
    }

    /**
     * Create success note
     */
    public function success($message, $title = 'Sukses', $isPermanent = false)
    {
        return $this->addNote([
            'title'        => $title,
            'message'      => $message,
            'type'         => 'success',
            'is_permanent' => $isPermanent,
        ]);
    }

    /**
     * Create error note
     */
    public function error($message, $title = 'Error', $isPermanent = true)
    {
        return $this->addNote([
            'title'        => $title,
            'message'      => $message,
            'type'         => 'error',
            'is_permanent' => $isPermanent,
        ]);
    }

    /**
     * Create warning note
     */
    public function warning($message, $title = 'Peringatan', $isPermanent = false)
    {
        return $this->addNote([
            'title'        => $title,
            'message'      => $message,
            'type'         => 'warning',
            'is_permanent' => $isPermanent,
        ]);
    }

    /**
     * Create info note
     */
    public function info($message, $title = 'Informasi', $isPermanent = false)
    {
        return $this->addNote([
            'title'        => $title,
            'message'      => $message,
            'type'         => 'info',
            'is_permanent' => $isPermanent,
        ]);
    }
}
