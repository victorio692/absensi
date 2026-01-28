#!/usr/bin/env php
<?php
/**
 * CodeIgniter's CLI Wrapper
 * 
 * Works around environment issues
 */

// Set environment variable
putenv('CI_ENVIRONMENT=development');
define('ENVIRONMENT', 'development');

// Get the path to this file
$basePath = __DIR__;

// Load original spark
require $basePath . '/vendor/codeigniter4/framework/spark';
