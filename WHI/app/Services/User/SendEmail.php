<?php

namespace App\Services\User;

use SendGrid;
use SendGrid\Mail\Mail;
use \Symfony\Component\HttpFoundation\Response;

final class SendEmail
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $address, string $identification)
    {
        $this->address = $address; 
        $this->identification = $identification; 
    }

    /**
     * パスワードリセット用のメール画面を表示
     *
     * @return $this
     */
    public function sendEmail()
    {
        $identification = $this->identification;
        $email = new Mail();
        $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_USERNAME'));
        $email->setSubject('パスワードの再設定について');
        $email->addTo($this->address);
        $email->addContent(
            "text/html",
            strval(
                view('email', compact('identification'))
            )
        );

        $sendGrid = new SendGrid(env('SENDGRID_API_KEY'));
        $response = $sendGrid->send($email);
        if($response->statusCode() === Response::HTTP_ACCEPTED) {
            return true;
        } else {
            return false;
        }
    }
}
?>
