<?php

namespace SmsOtp\Repositories;

use SmsOtp\Core\Auth\Authenticatable;
use SmsOtp\Core\Auth\AuthUserProvider;
use SmsOtp\Models\User;

class UserRepository extends Repository implements AuthUserProvider
{
    /**
     * @return \SmsOtp\Models\User[]
     */
    public function all(): array
    {
        $stmt = $this->connection()->prepare("SELECT * FROM `users` ORDER BY `created_at` DESC;");
        $stmt->execute([]);
        
        $resources = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return array_map(fn($resource) => new User($resource), $resources);
    }
    
    /**
     * @param $id
     *
     * @return \SmsOtp\Models\User|null
     */
    public function find($id): ?User
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `users`
            WHERE `id` = :id
            LIMIT 1;
        ");
        $stmt->execute(['id' => $id]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new User($resource) : null;
    }
    
    /**
     * @param $phone
     *
     * @return \SmsOtp\Models\User|null
     */
    public function findByPhone($phone): ?User
    {
        $stmt = $this->connection()->prepare("
            SELECT *
            FROM `users`
            WHERE `phone` = :phone
            LIMIT 1;
        ");
        $stmt->execute(['phone' => $phone]);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? new User($resource) : null;
    }
    
    /**
     * @param $data
     *
     * @return \SmsOtp\Models\User
     */
    public function create($data): User
    {
        [
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ] = $data;
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 10,
        ]);
        
        if ($hashedPassword === false) {
            throw new \RuntimeException('Bcrypt hashing not supported.');
        }
        
        $stmt = $this->connection()->prepare("
            INSERT INTO `users`
            (`email`, `phone`, `password`, `phone_verified_at`)
            VALUES
            (?, ?, ?, ?);
        ");
        $stmt->execute([
            $email,
            $phone,
            $hashedPassword,
            null,
        ]);
        
        return $this->find($this->connection()->lastInsertId());
    }
    
    public function verifyPhone(User $user): User
    {
        $stmt = $this->connection()->prepare("
            UPDATE `users`
            SET `phone_verified_at` = NOW()
            WHERE `id` = :id
            LIMIT 1;
        ");
        $stmt->execute(['id' => $user->id]);
        
        return $this->find($user->id);
    }
    
    public function retrieveAuthenticatedUser($identifier): ?Authenticatable
    {
        return $this->find($identifier);
    }
    
    public function verifyCredentials(array $credentials): bool
    {
        $email = $credentials['email'] ?? null;
        $phone = $credentials['phone'] ?? null;
        
        if (!$email && !$phone) {
            throw new \Exception("At least one of email or phone should be provided!");
        }
        
        $constraints = [];
        $bindings = [];
        
        if ($email) {
            $constraints[] = '`email` = :email';
            $bindings['email'] = $email;
        }
        
        if ($phone) {
            $constraints[] = '`phone` = :phone';
            $bindings['phone'] = $phone;
        }
        
        $stmt = $this
            ->connection()
            ->prepare("
                SELECT *
                FROM `users`
                WHERE " . implode(' OR ', $constraints) . "
                LIMIT 1;
            ");
        $stmt->execute($bindings);
        
        $resource = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $resource ? false : true;
    }
}
