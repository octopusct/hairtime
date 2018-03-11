<?php
/**
 * Created by PhpStorm.
 * User: Javelin
 * Date: 10.02.2018
 * Time: 13:05
 */

namespace App\Controllers;


use PHPMailer;

class EmailSender  extends PHPMailer
{
    public $to_name;
    public $to_email;

    public function __construct()
    {
        parent::__construct();
        $this->isHTML();
        $this->isSMTP();
        $this->Priority = 1; //Email priority: Options: null (default), 1 = High, 3 = Normal, 5 = low.
        $this->From = 'noreply@hairtime.co.il'; // from (от) email адрес
        $this->FromName = 'HairTime'; // from (от) имя
        $this->Host = 'hairtime.co.il';
        $this->Port = '465';
        $this->SMTPDebug = '0'; // 0 = off (for production use) 1 = client messages  2 = client and server messages
        $this->SMTPSecure = 'ssl'; //Set the encryption system to use - ssl (deprecated) or tls
        $this->CharSet = "UTF-8";  //The character set of the message.
        $this->Username = 'noreply@hairtime.co.il';
        $this->Password = '159789';
        $this->Mailer = 'smtp';
        $this->Sender = 'noreply@hairtime.co.il';
    }

    /**
     * @param string $templateName
     * @return string
     */
    protected function _getTemplate($templateName)
    {
        return file_get_contents($templateName.'html');
    }

    public function sendMailTemplate(array $to, $templateName, array $emailData)
    {
        
    }
}