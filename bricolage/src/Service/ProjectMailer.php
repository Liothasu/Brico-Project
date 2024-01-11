<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ProjectMailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendProjectCreationNotification(string $handymanEmail, string $userEmail, string $title, string $description): void
    {
        $email = (new Email())
            ->from($userEmail)
            ->to($handymanEmail)
            ->subject($title)
            ->html('A new project has been created with the following details: ' . $description);

        $this->mailer->send($email);
    }
}