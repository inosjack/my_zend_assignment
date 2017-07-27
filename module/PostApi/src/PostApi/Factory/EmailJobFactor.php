<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 7/28/2017
 * Time: 1:51 AM
 */

namespace PostApi\Factory;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use PostApi\Job\EmailJob;

abstract class EmailJobFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $transport = $sl->getServiceLocator()->get('my-transport-service');

        $job = new EmailJob($transport);
        return $job;
    }
}
