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

class AdminCommand extends Command
{
	protected $name = 'admin';
	protected $description = '';
	protected $usage = '/admin';
	protected $version = '1.0.0';
	protected $enabled = true;
	protected $public = false;

	private $user;
	private $tgram;

	private function getAuthStatus($telid)
	{
			$this->tgram = \Telegram::where('uid','=',$telid)->first();
			if ($this->tgram) {
					$this->user = \User::find($this->tgram->user_id);
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
			$text = 'Command: admin not found.. :(';
		} else {
			if ($this->user->isAdmin()) {
				if (!empty($text)) {
					switch ($text) {
						case "gebruikers":
							$text = \User::where('active','=','true')->count('id')." actieve gebruikers:\n";
							foreach(\User::where('active','=','true')->get() as $luser) {
								$text .= $luser->id . ' | ' . $luser->username . ' | ' . $luser->email . "\n";
							}
							break;
						default:
							$text = "Ongeldige optie";
					}
				} else {
					$text  = "Opties:\n";
					$text .= "/admin gebruikers - Lijst van actieve gebruikers\n";
				}
			} else {
				$text = 'Command: admin not found.. :(';
			}

		}

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['text'] = $text;

		$result = Request::sendMessage($data);
		return $result;
	}
}
