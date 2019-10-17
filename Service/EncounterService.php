<?php

namespace Encounter\Service;

use Encounter\Storage\MySQL\EncounterMapper;

final class EncounterService
{
    /**
     * Encounter mapper
     * 
     * @var \Encounter\Storage\MySQL\EncounterMapper
     */
    private $encounterMapper;

    /**
     * State initialization
     * 
     * @param \Encounter\Storage\MySQL\EncounterMapper $encounterMapper
     * @return void
     */
    public function __construct(EncounterMapper $encounterMapper)
    {
        $this->encounterMapper = $encounterMapper;
    }

    /**
     * Marks all encounters as read
     * 
     * @param int $userId
     * @return boolean Depending on success
     */
    public function markAllAsRead($userId)
    {
        return $this->encounterMapper->markAllAsRead($userId);
    }

    /**
     * Counts unread encounters
     * 
     * @param int $userId
     * @return int
     */
    public function countUnread($userId)
    {
        return $this->encounterMapper->countUnread($userId);
    }

    /**
     * Likes a user
     * 
     * @param int $senderId Owner id
     * @param int $receiverId User id to be liked
     * @return mixed
     */
    public function like($senderId, $receiverId)
    {
        return $this->encounterMapper->like($senderId, $receiverId);
    }

    /**
     * Dislikes a user
     * 
     * @param int $senderId Owner id
     * @param int $receiverId User id to be disliked
     * @return mixed
     */
    public function dislike($senderId, $receiverId)
    {
        return $this->encounterMapper->dislike($senderId, $receiverId);
    }
}
