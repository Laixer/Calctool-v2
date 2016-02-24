<?php

namespace Calctool\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class Snapshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database snapshot';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $snapshot = 'snap' . date('yp_master(domain, map)dhi');
        DB::statement("select clone_schema('public', '" . $snapshot . "',true)");
        $this->comment('Database snapshot: ' . $snapshot);
    }
}
