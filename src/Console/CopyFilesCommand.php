<?php

namespace Xsimarket\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Xsimarket\Database\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Xsimarket\Enums\Permission as UserPermission;
use Illuminate\Support\Facades\Validator;




class CopyFilesCommand extends Command
{
    protected $signature = 'xsimarket:copy-files';

    protected $description = 'Copy necessary files';
    public function handle()
    {
        try {
            (new Filesystem)->ensureDirectoryExists(resource_path('views/emails'));

            $this->info('Copying resources files...');

            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/emails', resource_path('views/emails'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/resources/views/pdf', resource_path('views/pdf'));

            $this->info('Installation Complete');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
