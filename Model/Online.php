<?php

namespace App\Module\User\Model;

/**
 * Class Online
 * @package App\Module\User\Model
 */
class Online
{
    /** @var \PDO */
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * List of online users
     * @return type
     */
    public function listOnlineUsers()
    {
        $sql = 'SELECT * FROM `sessions` WHERE `userId` > 0 AND `timestamp` >= ' . (time() - 300) . ' ORDER BY `timestamp` DESC';
        $STH = $this->db->prepare($sql);
        $STH->execute();
        return $STH->fetchAll();
    }

    /**
     * List of online visitors
     * @return type
     */
    public function listOnlineGuests()
    {
        $sql = 'SELECT * FROM `sessions` WHERE `userId` = 0 AND `timestamp` >= ' . (time() - 300) . ' ORDER BY `timestamp` DESC';
        $STH = $this->db->prepare($sql);
        $STH->execute();
        return $STH->fetchAll();
    }

}
