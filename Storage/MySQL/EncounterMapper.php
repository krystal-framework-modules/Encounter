<?php

namespace Encounter\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;

final class EncounterMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('users_ecounter');
    }

    /**
     * Returns primary column name for current mapper
     * 
     * @return string
     */
    protected function getPk()
    {
        return 'id';
    }

    /**
     * Checks whether reaction has already beet set
     * 
     * @param int $senderId Owner id
     * @param int $receiverId User id to be checked against
     * @return boolean
     */
    private function hasReaction($senderId, $receiverId)
    {
        $db = $this->db->select()
                       ->count('id')
                       ->from(self::getTableName())
                       ->whereEquals('sender_id', $senderId)
                       ->andWhereEquals('receiver_id', $receiverId);

        return (bool) $db->queryScalar();
    }

    /**
     * Inserts a reaction
     * 
     * @param int $senderId Owner id
     * @param int $receiverId User id to be liked
     * @param int $like Whether liked or not
     * @return boolean
     */
    private function insertReaction($senderId, $receiverId, $like)
    {
        // Stop, if previously declared
        if ($this->hasReaction($senderId, $receiverId)) {
            return false;
        }

        $data = array(
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'like' => $like,
            'read' => 0
        );

        $db = $this->db->insert(self::getTableName(), $data);

        return $db->execute();
    }

    /**
     * Counts unread encounters
     * 
     * @param int $userId
     * @return int
     */
    public function countUnread($userId)
    {
        $db = $this->db->select()
                       ->count('id')
                       ->from(self::getTableName())
                       ->whereEquals('user_id', $userId)
                       ->andWhereEquals('read', 0);
        
        return (int) $db->queryScalar();
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
        return $this->insertReaction($senderId, $receiverId, 1);
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
        return $this->insertReaction($senderId, $receiverId, 0);
    }
}
