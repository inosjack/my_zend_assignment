<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 7/26/2017
 * Time: 11:57 PM
 */
namespace PostApi\Controller;

use SlmQueue\Queue\QueueInterface;

use PostApi\Job\EmailJob;
use Zend\Validator\EmailAddress;
use Zend\View\Model\JsonModel;
use Zend\Mail\Message;




class PostApiController extends \Zend\Mvc\Controller\AbstractRestfulController
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * PostApiController constructor.
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param mixed $data
     * @return JsonModel
     */
    public function create($data)
    {
        $email = $data['emailId'];
        $name = $data['name'];

        //Validate email Id
        $validator = new EmailAddress();

        if ($validator->isValid($email)) {
            //Email valid
//            $queue = new Queue();
            return $this->sendMail($email,$name);

        } else {
            $messages = array();
            // email is invalid; print the reasons
            foreach ($validator->getMessages() as $messageId => $message) {
                $messages[] =  "Validation failure : $message";
            }
            return new JsonModel(array('status' => 'fail', 'messages' => $messages));
        }
    }

    /**
     * @param $email
     * @param $name
     * @return JsonModel
     */
    public function sendMail($email, $name) {
        $job = new EmailJob();
        $job->setContent(array(
            'to'      => $email,
            'subject' => 'My Assignment',
            'message' => 'Hi '.$name.', This is default testing message'
        ));

        $this->queue->push($job);

        return new JsonModel(array(
            'status' => 'success',
            'message' => 'Mail successfully send to '.$name
        ));
    }
}

