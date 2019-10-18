<?php

namespace Encounter\Storage\MySQL;

use Krystal\Db\Sql\AbstractMapper;
use Krystal\Db\Sql\QueryBuilder;
use Krystal\Db\Sql\RawSqlFragment;
use User\Storage\MySQL\UserMapper;

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
     * @param int $read Whether item is read
     * @param string $datetime
     * @return boolean
     */
    private function insertReaction($senderId, $receiverId, $datetime, $like, $read)
    {
        // Stop, if previously declared
        if ($this->hasReaction($senderId, $receiverId)) {
            return false;
        }

        $data = array(
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'datetime' => $datetime,
            'like' => $like,
            'read' => $read
        );

        $db = $this->db->insert(self::getTableName(), $data);

        return $db->execute();
    }

    /**
     * Creates shared encounter query
     * 
     * @param int $userId Current user id
     * @return \Krystal\Db\Sql\Db
     */
    private function createEncounterQuery($userId)
    {
        // Ensure numeric value supplied
        $userId = (int) $userId;

        // Query to find another user Ids being reacted by current user id
        $subQuery = function($userId){
            $qb = new QueryBuilder();
            $qb->select('receiver_id')
               ->from(self::getTableName())
               ->whereEquals('sender_id', $userId);

            return $qb->getQueryString();
        };

        // Columns to be selected
        $columns = array(
            UserMapper::column('id'),
            UserMapper::column('birthday'),
            UserMapper::column('avatar')
        );

        $db = $this->db->select($columns)
                       ->from(UserMapper::getTableName())
                       ->whereNotIn('id', new RawSqlFragment($subQuery($userId))) // Discard already reacted ids
                       ->andWhereNotEquals('id', $userId); // Discard current user as well

        // Done building shared query
        return $db;
    }

    /**
     * Finds a single encounter
     * 
     * @param int $userId Current user id
     * @return array
     */
    public function findEncounter($userId)
    {
        $db = $this->createEncounterQuery($userId)
                   ->orderBy('id')
                   ->limit(1);

        return $db->query();
    }

    /**
     * Finds all encounters
     * 
     * @param int $userId Current user id
     * @return array
     */
    public function findEncounters($userId)
    {
        $db = $this->createEncounterQuery($userId)
                   ->orderBy('id');

        return $db->queryAll();
    }

    /**
     * Marks all encounters as read
     * 
     * @param int $userId
     * @return boolean Depending on success
     */
    public function markAllAsRead($userId)
    {
        $db = $this->db->update(self::getTableName(), array('read' => '0'))
                       ->whereEquals('receiver_id', $userId);

        return (bool) $db->execute(true);
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
     * @param string $datetime
     * @return mixed
     */
    public function like($senderId, $receiverId, $datetime)
    {
        return $this->insertReaction($senderId, $receiverId, $datetime, 1, 0);
    }

    /**
     * Dislikes a user
     * 
     * @param int $senderId Owner id
     * @param int $receiverId User id to be disliked
     * @param string $datetime
     * @return mixed
     */
    public function dislike($senderId, $receiverId, $datetime)
    {
        return $this->insertReaction($senderId, $receiverId, $datetime, 0, 1);
    }
}
