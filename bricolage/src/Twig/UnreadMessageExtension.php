<?php

namespace App\Twig;

use App\Repository\MessageRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UnreadMessageExtension extends AbstractExtension
{
    private MessageRepository $messageRepository;
    private Security $security;

    public function __construct(MessageRepository $messageRepository, Security $security)
    {
        $this->messageRepository = $messageRepository;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('unread_message_count', [$this, 'getUnreadMessageCount']),
        ];
    }

    public function getUnreadMessageCount(): int
    {
        return $this->messageRepository->countUnreadMessagesForUser($this->security->getUser());
    }
}
