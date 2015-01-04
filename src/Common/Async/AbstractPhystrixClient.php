<?php

namespace Common\Async;

use Zend\Config\Config;
use Odesk\Phystrix\ApcStateStorage;
use Odesk\Phystrix\CircuitBreakerFactory;
use Odesk\Phystrix\CommandMetricsFactory;
use Odesk\Phystrix\CommandFactory;

/**
 * Description of AbstractPhystrixClient
 *
 * @author jasondt
 */
abstract class AbstractPhystrixClient {

    private $phystrix;

    function __construct() {

        $stateStorage = new ApcStateStorage();

        $this->phystrix = new CommandFactory(
                new Config(static::config())
                , new \Zend\Di\ServiceLocator()
                , new CircuitBreakerFactory($stateStorage)
                , new CommandMetricsFactory($stateStorage)
                , new \Odesk\Phystrix\RequestCache()
                , new \Odesk\Phystrix\RequestLog()
        );
    }

    private static function config() {
        //default config taken from documentation
        return array(
            'default' => array(// Default command configuration
                'fallback' => array(
                    // Whether fallback logic of the phystrix command is enabled
                    'enabled' => true,
                ),
                'circuitBreaker' => array(
                    // Whether circuit breaker is enabled, if not Phystrix will always allow a request
                    'enabled' => true,
                    // How many failed request it might be before we open the circuit (disallow consecutive requests)
                    'errorThresholdPercentage' => 50,
                    // If true, the circuit breaker will always be open regardless the metrics
                    'forceOpen' => false,
                    // If true, the circuit breaker will always be closed, allowing all requests, regardless the metrics
                    'forceClosed' => false,
                    // How many requests we need minimally before we can start making decisions about service stability
                    'requestVolumeThreshold' => 10,
                    // For how long to wait before attempting to access a failing service
                    'sleepWindowInMilliseconds' => 5000,
                ),
                'metrics' => array(
                    // This is for caching metrics so they are not recalculated more often than needed
                    'healthSnapshotIntervalInMilliseconds' => 1000,
                    // The period of time within which we the stats are collected
                    'rollingStatisticalWindowInMilliseconds' => 1000,
                    // The more buckets the more precise and actual the stats and slower the calculation.
                    'rollingStatisticalWindowBuckets' => 10,
                ),
                'requestCache' => array(
                    // Request cache, if enabled and a command has getCacheKey implemented
                    // caches results within current http request
                    'enabled' => true,
                ),
                'requestLog' => array(
                    // Request log collects all commands executed within current http request
                    'enabled' => false,
                ),
            )
//            ,
//            'MyCommand' => array(// Command specific configuration
//                'fallback' => array(
//                    'enabled' => false
//                )
//            )
        );
    }

}
