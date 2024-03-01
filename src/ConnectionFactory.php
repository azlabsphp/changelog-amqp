<?php

namespace Drewlabs\Changelog\Amqp;

class ConnectionFactory
{
    /**
     * Create a rabbit mq server connection
     * 
     * @param string $host 
     * @param int $port 
     * @param string $user 
     * @param string $password 
     * @return Connection 
     */
    public function createConnection(string $user = 'guest', string $password = 'guest', string $host = '127.0.0.1', $port = 5672, string $vHost = '/')
    {
        $server = (new Server($this->resolveHost($host), $port))
            ->withAuthentication($user, $password)
            ->withVirtualHost($vHost);

        return new Connection($server);
    }

    public function createClusterConnection()
    {
        // TODO: Implements cluster connection factory
    }

    /**
     * Resolve the ip address of the provided host
     * 
     * @param string $host 
     * @return string 
     */
    public function resolveHost(string $host)
    {
        if (preg_match('/^([12]?[0-9]?[0-9]\.){3}([12]?[0-9]?[0-9])$/', $host)) {
            return $host;
        }

        if (preg_match('/^([0-9a-f:]+):[0-9a-f]{1,4}$/i', $host)) {
            return $host;
        }

        return gethostbyname($host);
    }
}
