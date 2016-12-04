<?php

namespace Arcus;



use Arcus\Daemon\Worker;
use ION\Promise;
use Psr\Log\LogLevel;

interface EntityInterface {

    public function getName();

    public function __toString();

    public function enable(Worker $worker) : bool;

    public function disable() : Promise;

    public function halt();

    public function inspect();

	public function log($message, $level = LogLevel::DEBUG);

    public function logRotate();

    public function dispatch(TaskAbstract $task);

    public function fatal(\Throwable $error);

    /**
     * @param string $of (variants: self, daemon, cluster)
     *
     * @return mixed
     */
	public function getURI(string $of = 'self');

} 