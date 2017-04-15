<?php

namespace BynqIO\CalculatieTool\Console\Commands;

use Illuminate\Console\Command;

class StorageClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all user stored content';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = scandir('public/user-content/');
        foreach ($files as $file) {
            if ($file[0] == ".")
                continue;
            unlink('public/user-content/'.$file);
        }
    }
}
