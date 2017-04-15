<?php

namespace BynqIO\CalculatieTool\Console\Commands;

use Illuminate\Console\Command;

use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\UserType;

use \Redis;
use \Hash;

class AdminReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin user';

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
        $users = User::where('user_type', UserType::where('user_type', 'system')->first()->id)->get();
        foreach ($users as $user) {
            echo 'Username: ' . $user->username . "\n";
            $passwd = $this->secret('New password');
            $user->secret = Hash::make($passwd);
            $user->active = 'Y';
            $user->save();
        }
    }
}
