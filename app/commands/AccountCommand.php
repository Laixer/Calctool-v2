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

class AccountCommand extends Command
{
	protected $name = 'account';
	protected $description = '';
	protected $usage = '/account';
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
			$text = 'Naam: ' . $this->user->firstname . ' ' . $this->user->lastname . "\n";
			$text .= 'Email: ' . $this->user->email . "\n";
			$text .= 'Abonnement actief tot: ' . date('j F Y', strtotime($this->user->expiration_date)) . "\n";
			$text .= 'Ontvang Telegram alerts: Nee' . "\n";
		}

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['text'] = $text;

		$result = Request::sendMessage($data);
		return $result;
	}
}
