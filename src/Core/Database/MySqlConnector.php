<?php

namespace SmsOtp\Core\Database;

use PDO;

class MySqlConnector implements DbConnector
{
    
    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected array $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    
    /**
     * Connection configuration
     *
     * @var array
     */
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * @inheritDoc
     */
    public function connect(): PDO
    {
        $dsn = $this->getDsn();
        
        [
            'username' => $username,
            'password' => $password,
        ] = $this->config;
        
        $connection = new PDO($dsn, $username, $password, $this->getOptions());
        
        if ($config['database'] ?? null) {
            $connection->exec("use `{$this->config['database']}`;");
        }
        
        $this->configureEncoding($connection);
        
        return $connection;
    }
    
    /**
     * Set the connection character set and collation.
     *
     * @param \PDO $connection
     *
     * @return void|\PDO
     */
    protected function configureEncoding(PDO $connection)
    {
        if (!isset($this->config['charset'])) {
            return $connection;
        }
        
        $connection->prepare(
            "set names '{$this->config['charset']}'" . $this->getCollation($this->config)
        )->execute();
    }
    
    /**
     * Get the collation for the configuration.
     *
     * @return string
     */
    protected function getCollation()
    {
        return isset($this->config['collation']) ? " collate '{$this->config['collation']}'" : '';
    }
    
    /**
     * Create a DSN string from a configuration.
     *
     * Chooses socket or host/port based on the 'unix_socket' config value.
     *
     * @return string
     */
    protected function getDsn()
    {
        return $this->hasSocket()
            ? $this->getSocketDsn()
            : $this->getHostDsn();
    }
    
    /**
     * Determine if the given configuration array has a UNIX socket value.
     *
     * @return bool
     */
    protected function hasSocket(): bool
    {
        return isset($this->config['unix_socket']) && !empty($this->config['unix_socket']);
    }
    
    /**
     * Get the DSN string for a socket configuration.
     *
     * @return string
     */
    protected function getSocketDsn(): string
    {
        return "mysql:unix_socket={$this->config['unix_socket']};dbname={$this->config['database']}";
    }
    
    /**
     * Get the DSN string for a host / port configuration.
     *
     * @return string
     */
    protected function getHostDsn(): string
    {
        [
            'host' => $host,
            'port' => $port,
            'database' => $database,
        ] = $this->config;
        
        return $port
            ? "mysql:host={$host};port={$port};dbname={$database}"
            : "mysql:host={$host};dbname={$database}";
    }
    
    /**
     * Get the PDO options based on the configuration.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return array_merge($this->options, $this->config['options'] ?? []);
    }
}
