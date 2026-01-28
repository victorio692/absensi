<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugFormUrl extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:form-url';
    protected $description = 'Check form action URL';

    public function run(array $params = [])
    {
        CLI::write('=== CHECKING FORM ACTION URL ===', 'green');
        
        $baseUrl = config('App')->baseURL;
        $actionUrl = base_url('admin/qr-location/store');
        
        CLI::write("\nBase URL Config: ", 'yellow');
        CLI::write("  $baseUrl", 'white');
        
        CLI::write("\nForm action URL (base_url('admin/qr-location/store')): ", 'yellow');
        CLI::write("  $actionUrl", 'white');
        
        CLI::write("\nExpected URL: ", 'yellow');
        CLI::write("  http://localhost:8080/admin/qr-location/store", 'white');
        
        if ($actionUrl === 'http://localhost:8080/admin/qr-location/store') {
            CLI::write("\n✓ URLs MATCH!", 'green');
        } else {
            CLI::write("\n✗ URLs DO NOT MATCH!", 'red');
        }
        
        // Check routes
        CLI::write("\nChecking if route is registered:", 'yellow');
        
        $routes = config('Routes');
        
        // Check if POST route exists
        CLI::write("  Routes config file: " . get_class($routes), 'white');
        
        CLI::write("\n=== TEST COMPLETE ===", 'green');
    }
}
