<?php

namespace BynqIO\Dynq\Models;

use Illuminate\Database\Eloquent\Model;

use \Auth;
use Jenssegers\Agent\Agent;

class Audit extends Model {

    protected $table = 'audit';
    protected $guarded = array('id', 'ip');

    // public function user() {
    //     return $this->hasOne('User');
    // }

    public static function UserAgent()
    {
        $agent = new Agent();
        return $agent->platform() . ' ' . $agent->device() . ' ' . $agent->browser() . '/' . $agent->version($agent->browser());
    }

    public static function CreateEvent($module, $event, $user = null)
    {
        $log = new Audit($module, $event);
        $log->setUserId($user ? $user : Auth::id());
        $log->save();
    }

    public function __construct($module = null, $event = null)
    {
        if (!empty($module) && !empty($event)) {
            $this->event = $module . "\n" . $event;
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];//TODO: replace with function
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
        return $this;
    }
}
