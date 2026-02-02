<?php

/**
 * Notes Helper
 * Helper untuk manajemen catatan penting yang persisten
 */

if (!function_exists('addNote')) {
    /**
     * Tambah note baru
     *
     * @param string $message
     * @param string $type (success, error, warning, info)
     * @param string|null $title
     * @param bool $isPermanent
     *
     * @return mixed
     */
    function addNote($message, $type = 'info', $title = null, $isPermanent = false)
    {
        $notesModel = model('NotesModel');

        return $notesModel->addNote([
            'title'        => $title ?? ucfirst($type),
            'message'      => $message,
            'type'         => $type,
            'is_permanent' => $isPermanent,
        ]);
    }
}

if (!function_exists('addSuccessNote')) {
    /**
     * Tambah success note
     */
    function addSuccessNote($message, $title = 'Sukses', $isPermanent = false)
    {
        return model('NotesModel')->success($message, $title, $isPermanent);
    }
}

if (!function_exists('addErrorNote')) {
    /**
     * Tambah error note
     */
    function addErrorNote($message, $title = 'Error', $isPermanent = true)
    {
        return model('NotesModel')->error($message, $title, $isPermanent);
    }
}

if (!function_exists('addWarningNote')) {
    /**
     * Tambah warning note
     */
    function addWarningNote($message, $title = 'Peringatan', $isPermanent = false)
    {
        return model('NotesModel')->warning($message, $title, $isPermanent);
    }
}

if (!function_exists('addInfoNote')) {
    /**
     * Tambah info note
     */
    function addInfoNote($message, $title = 'Informasi', $isPermanent = false)
    {
        return model('NotesModel')->info($message, $title, $isPermanent);
    }
}

if (!function_exists('getUserNotes')) {
    /**
     * Dapatkan notes untuk user
     */
    function getUserNotes($userId = null)
    {
        return model('NotesModel')->getUserNotes($userId);
    }
}

if (!function_exists('getUnreadNotes')) {
    /**
     * Dapatkan unread notes
     */
    function getUnreadNotes()
    {
        return model('NotesModel')->getUnreadNotes();
    }
}

if (!function_exists('markNoteAsRead')) {
    /**
     * Mark note sebagai sudah dibaca
     */
    function markNoteAsRead($noteId)
    {
        return model('NotesModel')->markAsRead($noteId);
    }
}

if (!function_exists('deleteNote')) {
    /**
     * Hapus note
     */
    function deleteNote($noteId)
    {
        return model('NotesModel')->deleteNote($noteId);
    }
}
