<?php


namespace App\Services;


use App\Contracts\CommunicationServiceContract;
use App\Mail\CompanyMail;
use Illuminate\Support\Facades\Mail;

class CommunicationService implements CommunicationServiceContract
{
    /**
     * @param $receiver
     * @param $subject
     * @param $body
     */
    public function send($receiver,$subject,$body)
    {
        Mail::to($receiver)
            ->send(new CompanyMail($subject,$body));
    }

}
