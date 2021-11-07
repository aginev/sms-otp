<?php

namespace SmsOtp\Core\Database;

interface DbConnector
{
    /**
     * Establish a database connection.
     *
     * @return \PDO
     */
    public function connect(): \PDO;
}
