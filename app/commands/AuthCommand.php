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

class AuthCommand extends Command
{
	protected $name = 'auth';
	protected $description = 'Verbindt met CalculatieTool profiel';
	protected $usage = '/auth <apikey>';
	protected $version = '1.0.0';
	protected $enabled = true;
	protected $public = true;

	public function execute()
	{
		$update = $this->getUpdate();
		$message = $this->getMessage();

		$chat_id = $message->getChat()->getId();
		$message_id = $message->getMessageId();
		$text = $message->getText(true);

		if (empty($text)) {
			$text = 'Geeft de api key op, deze is te vinden in Mijn Account';
		} else {
			/*$weather = $this->getWeatherString($text);
			if (empty($weather)) {
				$text = 'Can not find weather for location: ' . $text;
			} else {
				$text = $weather;
			}*/
		}

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['reply_to_message_id'] = $message_id;
		$data['text'] = $text;

		$result = Request::sendMessage($data);
		return $result;
	}
}
