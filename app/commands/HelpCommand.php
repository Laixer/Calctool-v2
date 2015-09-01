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

class HelpCommand extends Command
{
    protected $name = 'help';
    protected $description = 'Geef beschikbare opdrachten';
    protected $usage = '/help';
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

        $commands = $this->telegram->getCommandsList();

        $msg = 'Opdrachten:' . "\n";
    $msg .= '/auth <api> - Koppel Telegram aan uw CalculatieTool profiel' . "\n";
    $msg .= '/deauth - Ontkoppel Telegram' . "\n";
    $msg .= '/project [<id>] - Bekijk projectgegevens' . "\n";
    $msg .= '/account - Geef eigen profiel weer' . "\n";
    $msg .= '/settings - Verander account instellingen' . "\n";
    $msg .= '/about - Over de CalculatieTool' . "\n";

        $data = array();
        $data['chat_id'] = $chat_id;
        $data['text'] = $msg;

        $result = Request::sendMessage($data);
        return $result;
    }
}
