<?php

namespace Drewlabs\Changelog\Amqp;

class Server
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $vhost;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * Create new server instance
     * 
     * @param string|null $host 
     * @param int|null $port 
     * @return void 
     */
    public function __construct(string $host = null, int $port = null)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Set server host property value
     * 
     * @param string $host 
     * @return static 
     */
    public function withHost(string $host)
    {
        $self = clone $this;
        $self->host = $host;
        return $self;
    }

    /**
     * Set server port property value
     * 
     * @param int $port 
     * @return static 
     */
    public function withPort(int $port)
    {
        $self = clone $this;
        $self->port = $port;
        return $self;
    }

    /**
     * Set authentication credentials for the server
     * 
     * @param string $user 
     * @param string $pass
     * 
     * @return static 
     */
    public function withAuthentication(string $user, string $pass)
    {
        $self = clone $this;
        $self->user = $user;
        $self->password = $pass;

        return $self;
    }

    public function withVirtualHost(string $path): self
    {
        $self = clone $this;
        $self->vhost = $path;
        return $self;
    }

    public function getHost()
    {
        return strval($this->host);
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getUser(): string
    {
        return $this->user ?? 'guest';
    }

    public function getPassword(): string
    {
        return $this->password ?? 'guest';
    }

    /**
     * Return the virtual host to connect to
     * 
     * @return string 
     */
    public function getVirtualHost(): string
    {
        return $this->vhost ?? '/';
    }

    /**
     * Returns a DSN connection string
     * 
     * @return string 
     */
    public function __toString()
    {
        if ($port = $this->getPort()) {
            return sprintf("%s:%s@%s:%d", $this->getUser(), $this->getPassword(), $this->getHost(), $port);
        }
        return sprintf("%s:%s@%s", $this->getUser(), $this->getPassword(), $this->getHost());
    }
}
