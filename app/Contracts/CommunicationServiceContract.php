<?php


namespace App\Contracts;


interface CommunicationServiceContract
{
    public function send($receiver, $subject, $body);

}
