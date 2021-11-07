<?php

namespace SmsOtp\Repositories;

use SmsOtp\Models\Notification;

class NotificationRepository extends Repository
{
    public function find($id): ?Notification
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `notifications`
            WHERE `id` = :id
            LIMIT 1;
        ");
        $stmt->execute(['id' => $id]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new Notification($resource) : null;
    }
    
    public function create($data): Notification
    {
        $stmt = $this->connection()->prepare("
            INSERT INTO `notifications`
            (`user_id`, `message`)
            VALUES
            (:user_id, :message);
        ");
        $stmt->execute($data);
        
        return $this->find($this->connection()->lastInsertId());
    }
}
