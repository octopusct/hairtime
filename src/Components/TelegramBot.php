<?php
/**
 * Created by PhpStorm.
 * User: Tamara
 * Date: 27.01.2018
 * Time: 16:44
 */

namespace App\Components;

use App\Controllers\EmailController;
use Slim\Http\Request;
use Slim\Http\Response;
use PHPMailer;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;


class TelegramBot
{

    protected $chatId;
    protected $messageText;
    protected $messageId;
    protected $userName;
    protected $_isEntities;
    protected $_entitiesType;
    protected $text;

    protected $bot;

    protected function getMessageId()
    {
        return $this->messageId;
    }

    protected function getChatId()
    {
        return $this->chatId;
    }

    protected function getUserName()
    {
        return $this->userName;
    }

    protected function parseParams(array $params)
    {
        $this->chatId = $params["message"]["chat"]["id"];
        $this->userName = $params["message"]["from"]["last_name"];
        $this->messageText = 'Hello '.$this->userName;
        $this->messageId = $params["message"]["id"];
        isset($params["message"]["text"]) ? $this->text = $params["message"]["text"]
            :$this->bot->sendMessage([ 'chat_id' => $this->getChatId() , 'text' => "Отправьте текстовое сообщение." ]);;
    }

    function index(Request $req, Response $res)
    {

        try {

            $this->parseParams($req->getParams());
            $keyboard = array(array("one", "two", "three"));
//                [["Список сотружников"],["Услуги"],["Адрес салона"]];

            $this->bot = new BotApi('537321684:AAE_TQGkjd2Pn5YJHy6s1gnww-A9g2nIleI');

            if ($this->text == "/start") {
                $reply_markup = new ReplyKeyboardMarkup( $keyboard);

                $this->bot->sendMessage( $this->getChatId(),
                    "Hello ".$this->getUserName(),
                    null,
                    false,
                    $this->getMessageId(),
                    $reply_markup );
            }else{
                $this->bot->sendMessage( $this->getChatId(),  "Hello ".$this->getUserName());
            }
        } catch (InvalidArgumentException $e) {
            $mail = new EmailController();
            $mail->AddAddress('mr.zalyotin@gmail.com', 'Vitaliy'); // Получатель
            $mail->Subject = htmlspecialchars('InvalidArgumentException');  // Тема письма
            $letter_body = json_encode ($req->getParams()).var_dump($e);
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Something wrong";
            $result = $mail->Send();

        } catch (Exception $e) {
            $mail = new EmailController();
            $mail->AddAddress('mr.zalyotin@gmail.com', 'Vitaliy'); // Получатель
            $mail->Subject = htmlspecialchars('Exception');  // Тема письма
            $letter_body = json_encode ($req->getParams()).'  Error: '.$e->getMessage();
            $mail->MsgHTML($letter_body); // Текст сообщения
            $mail->AltBody = "Something wrong";
            $result = $mail->Send();
        }

    }
}