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

class DeauthCommand extends Command
{
	protected $name = 'deauth';
	protected $description = '';
	protected $usage = '/deauth>';
	protected $version = '1.0.0';
	protected $enabled = true;
	protected $public = true;

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
			$text = 'Telegram is niet gekoppeld aan een account';
		} else {
			$this->tgram->delete();
			$text = 'Telegram is ontkoppled van uw CalculatieTool account';
		}

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['text'] = $text;

		$result = Request::sendMessage($data);
		return $result;
	}
}
