<?php

namespace SmsOtp\Repositories;

use SmsOtp\Models\User;
use SmsOtp\Models\VerificationAttempt;

class VerificationAttemptRepository extends Repository
{
    public function find($id): ?VerificationAttempt
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `verification_attempts`
            WHERE `id` = :id
            LIMIT 1;
        ");
        $stmt->execute(['id' => $id]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new VerificationAttempt($resource) : null;
    }
    
    public function create(User $user, int $code): VerificationAttempt
    {
        $stmt = $this->connection()->prepare("
            INSERT INTO `verification_attempts`
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
    
    public function clearUserVerificationAttempts(User $user)
    {
        $stmt = $this->connection()->prepare("
            DELETE FROM `verification_attempts`
            WHERE `user_id` = ?;
        ");
        $stmt->execute([
            $user->id,
        ]);
    }
    
    /**
     * @param \SmsOtp\Models\User $user
     *
     * @return \SmsOtp\Models\VerificationAttempt[]
     */
    public function verificationAttempts(User $user): array
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `verification_attempts`
            WHERE `user_id` = ?
            ORDER BY `created_at` DESC
            LIMIT 3
        ");
        $stmt->execute([
            $user->id
        ]);
    
        $resources = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        return array_map(fn($resource) => new VerificationAttempt($resource), $resources);
    }
}
