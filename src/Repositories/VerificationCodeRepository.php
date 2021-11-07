<?php

namespace SmsOtp\Repositories;

use SmsOtp\Models\User;
use SmsOtp\Models\VerificationCode;

class VerificationCodeRepository extends Repository
{
    public function find($id): ?VerificationCode
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `verification_codes`
            WHERE `id` = :id
            LIMIT 1;
        ");
        $stmt->execute(['id' => $id]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new VerificationCode($resource) : null;
    }
    
    public function create(User $user, int $code): VerificationCode
    {
        $stmt = $this->connection()->prepare("
            INSERT INTO `verification_codes`
            (`user_id`, `code`)
            VALUES
            (?, ?);
        ");
        $stmt->execute([
            $user->id,
            $code,
        ]);
        
        return $this->find($this->connection()->lastInsertId());
    }
    
    public function verify(User $user, string $code): ?VerificationCode
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `verification_codes`
            WHERE `user_id` = :user_id AND `code` = :code
            ORDER BY `created_at` DESC
            LIMIT 1;"
        );
        $stmt->execute([
            'user_id' => $user->id,
            'code' => $code,
        ]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new VerificationCode($resource) : null;
    }
    
    public function latest(User $user): ?VerificationCode
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `verification_codes`
            WHERE `user_id` = :user_id
            ORDER BY `created_at` DESC
            LIMIT 1;
        ");
        $stmt->execute([
            'user_id' => $user->id,
        ]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new VerificationCode($resource) : null;
    }
    
    function clearUserVerificationCodes(User $user)
    {
        $stmt = $this->connection()->prepare("
            DELETE FROM `verification_codes`
            WHERE `user_id` = ?;
        ");
        $stmt->execute([
            $user->id,
        ]);
    }
}
