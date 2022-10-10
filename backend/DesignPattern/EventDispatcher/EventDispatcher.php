<?php

declare(strict_types=1);

namespace DesignPattern\EventDispatcher;

use function call_user_func_array;
use function function_exists;
use function is_array;
use function is_callable;
use function is_string;
use function ksort;

/**
 * シンプルな event dispatcherです。
 */
class EventDispatcher
{
    /**
     * Singleton pattern based instance.
     *
     * @var self|null
     */
    public static $instance;

    /**
     * The collection of events.
     *
     * @var array|null
     */
    public static $events;

    /**
     * Constructer.
     */
    private function __construct()
    {
        self::$instance = null;

        self::$events = [];
    }

    /**
     * Singleton.
     *
     * @return self
     */
    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new EventDispatcher();
        }

        return self::$instance;
    }

    /**
     * Add a listener.
     *
     * @param string        $name      The name of an event.
     * @param string|array  $func      Callable function or class.
     * @param int           $priority  The execution priority.
     *
     * @return bool
     */
    public function addListener($name, $func, $priority = 10): bool
    {
        // The priority postion has been taken.
        if (isset(self::$events[$name][$priority])) {
            return false;
        }

        // $func should be a function name or a callable function.
        self::$events[$name][$priority] = $func;

        // Or, it is an array contains Class and method name.
        if (is_array($func)) {
            self::$events[$name][$priority] = [
                $func[0],
                $func[1],
            ];
        }

        return true;
    }

    /**
     * Execute the listener.
     *
     * @param string $name The name of an event.
     * @param array  $args The arguments.
     *
     * @return mixed
     */
    public function doDispatch(string $name, array $args = [])
    {
        if (!isset(self::$events[$name])) {
            return;
        }

        $return = null;

        ksort(self::$events[$name]);

        foreach (self::$events[$name] as $action) {

            if (is_string($action) && function_exists($action)) {
                $return = call_user_func_array(
                    $action, // Callable function.
                    $args
                );
            } elseif (is_array($action)) {
                $return = call_user_func_array(
                    [
                        $action[0], // Class.
                        $action[1], // The method of that class.
                    ],
                    $args
                );
            } elseif (is_callable($action)) {
                $return = $action($args);
            }
        }

        return $return;
    }
}
