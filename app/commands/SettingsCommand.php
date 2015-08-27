<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
*/

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class SettingsCommand extends Command
{
	protected $name = 'settings';
	protected $description = '';
	protected $usage = '/settings';
	protected $version = '1.0.0';
	protected $enabled = true;
	protected $public = true;

	private $user;

	private function getAuthStatus($telid)
	{
		$userid = \Redis::get('auth:telegram:'.$telid);
		if ($userid) {
			$this->user = \User::find($userid);
			return true;
		} else {
			return false;
		}
	}

	public function execute()
	{
		$update = $this->getUpdate();
		$message = $this->getMessage();

		$chat_id = $message->getChat()->getId();
		$message_id = $message->getMessageId();
		$text = $message->getText(true);

		if (!$this->getAuthStatus($message->getFrom()->getId())) {
			$text = 'Telegram is niet gekoppeld aan een account. Gebruik /auth';
		} else {

			if (!empty($text)) {
				$project = \Project::find($text);
				if ($project) {
					$text  = 'ID: ' . $project->id . "\n";
					$text .= 'Naam: ' . $project->project_name . "\n";
					$text .= 'Adres: ' . $project->address_street . ' ' . $project->address_number . ', ' . $project->address_postal . ', ' . $project->address_city . "\n";
					$text .= 'Uurtarief: ' . $project->hour_rate . ' ' . "\n";
					$text .= 'Uurtarief meerwerk: ' . $project->hour_rate_more . ' ' . "\n";
				} else {
					$text = 'Project niet gevonden';
				}
			} else {
				$projects = \Project::where('user_id','=',$this->user->id)->where('project_close','=',null)->get();
				$text = 'Actieve projecten:' . "\n\n";
				foreach($projects as $project) {
					$text .= $project->id . ' | ' .$project->project_name . ' | ' . $project->note . "\n";
				}
			}

		}

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['text'] = $text;

		$result = Request::sendMessage($data);
		return $result;
	}
}