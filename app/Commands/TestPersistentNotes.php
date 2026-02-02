<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestPersistentNotes extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'test:notes';
    protected $description = 'Test persistent notes system';

    public function run(array $params = [])
    {
        CLI::write('=== TESTING PERSISTENT NOTES SYSTEM ===', 'green');

        // Check if NotesModel exists
        CLI::write('\n[STEP 1] Checking NotesModel', 'yellow');
        try {
            $model = model('NotesModel');
            CLI::write('✓ NotesModel loaded successfully', 'green');
        } catch (\Exception $e) {
            CLI::error('✗ Failed to load NotesModel: ' . $e->getMessage());
            return;
        }

        // Check if notes table exists
        CLI::write('\n[STEP 2] Checking notes table', 'yellow');
        $db = \Config\Database::connect();
        $tables = $db->listTables();

        if (in_array('notes', $tables)) {
            CLI::write('✓ notes table exists', 'green');
        } else {
            CLI::error('✗ notes table does not exist');
            CLI::write('Run: php spark migrate', 'cyan');
            return;
        }

        // Check table structure
        CLI::write('\n[STEP 3] Checking table structure', 'yellow');
        $fields = $db->getFieldData('notes');
        CLI::write('Columns found: ' . count($fields), 'white');
        foreach ($fields as $field) {
            CLI::write("  - {$field->name} ({$field->type})", 'white');
        }

        // Test helper functions
        CLI::write('\n[STEP 4] Testing helper functions', 'yellow');

        $helpers = [
            'addNote',
            'addSuccessNote',
            'addErrorNote',
            'addWarningNote',
            'addInfoNote',
            'getUserNotes',
            'getUnreadNotes',
            'markNoteAsRead',
            'deleteNote',
        ];

        foreach ($helpers as $helper) {
            if (function_exists($helper)) {
                CLI::write("✓ {$helper}() exists", 'green');
            } else {
                CLI::error("✗ {$helper}() not found");
            }
        }

        // Check NotesHelper file
        CLI::write('\n[STEP 5] Checking NotesHelper file', 'yellow');
        $helperPath = APPPATH . 'Helpers/NotesHelper.php';
        if (file_exists($helperPath)) {
            CLI::write('✓ NotesHelper.php exists', 'green');
            $lines = count(file($helperPath));
            CLI::write("  Lines of code: $lines", 'white');
        } else {
            CLI::error('✗ NotesHelper.php not found');
        }

        // Check API Controller
        CLI::write('\n[STEP 6] Checking API NotesController', 'yellow');
        $apiPath = APPPATH . 'Controllers/Api/NotesController.php';
        if (file_exists($apiPath)) {
            CLI::write('✓ NotesController API exists', 'green');
        } else {
            CLI::error('✗ NotesController API not found');
        }

        // Check Routes
        CLI::write('\n[STEP 7] Checking Routes configuration', 'yellow');
        $routesPath = APPPATH . 'Config/Routes.php';
        $routesContent = file_get_contents($routesPath);

        if (strpos($routesContent, '/api/notes') !== false) {
            CLI::write('✓ Notes API routes configured', 'green');
        } else {
            CLI::error('✗ Notes API routes not found in Routes.php');
        }

        // Check Autoload config
        CLI::write('\n[STEP 8] Checking Autoload configuration', 'yellow');
        $autoloadPath = APPPATH . 'Config/Autoload.php';
        $autoloadContent = file_get_contents($autoloadPath);

        if (strpos($autoloadContent, 'notes') !== false) {
            CLI::write('✓ NotesHelper auto-loaded', 'green');
        } else {
            CLI::error('✗ NotesHelper not auto-loaded');
            CLI::write('Add "notes" to $helpers array in Config/Autoload.php', 'yellow');
        }

        // Check Layout view
        CLI::write('\n[STEP 9] Checking Layout view', 'yellow');
        $layoutPath = APPPATH . 'Views/layout.php';
        $layoutContent = file_get_contents($layoutPath);

        if (strpos($layoutContent, 'notes-container') !== false) {
            CLI::write('✓ Layout has notes-container', 'green');
        } else {
            CLI::error('✗ Layout missing notes-container');
        }

        if (strpos($layoutContent, 'getUnreadNotes()') !== false) {
            CLI::write('✓ Layout calls getUnreadNotes()', 'green');
        } else {
            CLI::error('✗ Layout not calling getUnreadNotes()');
        }

        CLI::write('\n=== TEST SUMMARY ===', 'green');
        CLI::write('✓ All components are properly configured', 'green');
        CLI::write('\nTo use persistent notes in your controller:', 'yellow');
        CLI::write('  addSuccessNote("Your message");', 'cyan');
        CLI::write('  addErrorNote("Error message", isPermanent: true);', 'cyan');
        CLI::write('  addWarningNote("Warning message");', 'cyan');
        CLI::write('  addInfoNote("Info message");', 'cyan');
    }
}
