<?php

namespace BynqIO\Dynq\Console\Commands;

use Illuminate\Console\Command;

use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\UserType;

use \DB;

class OauthClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear any expired tokens';

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
        /* Delete expired auth codes */
        DB::table('oauth_auth_codes')
                ->where('expire_time', '<', time())
                ->delete();

        /* Delete expired refresh tokens */
        DB::table('oauth_refresh_tokens')
                ->where('expire_time', '<', time())
                ->delete();

        /* Delete expired access tokens if they cannot be refreshed */
        DB::table('oauth_access_tokens')
                ->whereNotIn('id', function ($query) {
                    $query->select('access_token_id')
                        ->from('oauth_refresh_tokens');
                })
                ->where('expire_time', '<', time())
                ->delete();
    }
}
