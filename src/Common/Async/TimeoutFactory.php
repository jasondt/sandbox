<?php

namespace Common\Async;

use React\EventLoop\LoopInterface;

/**
 * Description of TimeoutFactory
 *
 * @author jasondt <jason.townsend@techdata.com>
 */
class TimeoutFactory {

    private $loop;

    public function __construct(LoopInterface $loop) {
        $this->loop = $loop;
    }

    public function create($milliseconds) {
        $deferred = new \React\Promise\Deferred();
        $this->loop->addTimer($milliseconds / 1000, function() use ($deferred, $milliseconds) {
            $message = sprintf("Timeout: %s milliseconds have passed.", $milliseconds);
            $deferred->reject(new TimeoutException($message));
        });
        return $deferred->promise();
    }

}
