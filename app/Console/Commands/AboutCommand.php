<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class AboutCommand extends Command
{
	protected $name = 'about';
	protected $description = 'Over de CalculatieTool';
	protected $usage = '/about';
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

		$msg = '-= Calctool v' . $_ENV['CT_VERSION'] . ' =-' . "\n\n";
		$msg .= 'Services: Online' . "\n";
		$msg .= 'COPYRIGHT © ' . date('Y') . ' CalculatieTool';

		$data = array();
		$data['chat_id'] = $chat_id;
		$data['text'] = $msg;

		$result = Request::sendMessage($data);
		return $result;
	}
}
