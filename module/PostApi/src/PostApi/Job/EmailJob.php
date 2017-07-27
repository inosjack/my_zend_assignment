<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 7/28/2017
 * Time: 1:38 AM
 */

namespace PostApi\Job;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use SlmQueue\Job\AbstractJob;
use Zend\Mail\Transport\TransportInterface;

class EmailJob extends AbstractJob
{
    /**
     * @var array
     */
    protected $config = array(
        'name'              => 'smtp.mailtrap.io',
        'host'              => 'smtp.mailtrap.io',
        'port'              => 465 , // Notice port change for TLS is 587
        'connection_class'  => 'plain',
        'connection_config' => array(
            'username' => '8fb82c0165a162',
            'password' => '69af58bb34ab02',
            'ssl'      => 'tls',
        ),
    );

    /**
     *
     */
    public function execute()
    {
        $message = new Message;
        $payload = $this->getContent();

        $message->setTo($payload['to']);
        $message->setSubject($payload['subject']);
        $message->setContent($payload['content']);

        $transport = new SmtpTransport();
        $options   = new SmtpOptions($this->config);
        $transport->setOptions($options);
        $transport->send($message);
    }


}
