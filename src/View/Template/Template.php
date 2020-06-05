<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pagerfanta\View\Template;

use Pagerfanta\Exception\InvalidArgumentException;
use Pagerfanta\Exception\RuntimeException;

/**
 * @author Pablo Díez <pablodip@gmail.com>
 */
abstract class Template implements TemplateInterface
{
    /**
     * @var array
     */
    protected static $defaultOptions = [];

    /**
     * @var callable
     */
    private $routeGenerator;

    /**
     * @var array
     */
    private $options;

    public function __construct()
    {
        $this->options = static::$defaultOptions;
    }

    /**
     * @param callable $routeGenerator
     *
     * @throws InvalidArgumentException if the route generator is not a callable
     */
    public function setRouteGenerator($routeGenerator): void
    {
        if (!is_callable($routeGenerator)) {
            throw new InvalidArgumentException(sprintf('The $routeGenerator argument of %s() must be a callable, a %s was given.', __METHOD__, gettype($routeGenerator)));
        }

        $this->routeGenerator = $routeGenerator;
    }

    public function setOptions(array $options): void
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Generate the route (URL) for the given page
     *
     * @param int $page
     *
     * @return string
     */
    protected function generateRoute($page)
    {
        return \call_user_func($this->getRouteGenerator(), $page);
    }

    /**
     * @throws RuntimeException if the route generator has not been set
     */
    private function getRouteGenerator(): callable
    {
        if (!$this->routeGenerator) {
            throw new RuntimeException(sprintf('The route generator was not set to the template, ensure you call %s::setRouteGenerator().', static::class));
        }

        return $this->routeGenerator;
    }

    /**
     * @param string $name The name of the option to look up
     *
     * @return mixed The option value if it exists
     *
     * @throws InvalidArgumentException if the option does not exist
     */
    protected function option($name)
    {
        if (!isset($this->options[$name])) {
            throw new InvalidArgumentException(sprintf('The option "%s" does not exist.', $name));
        }

        return $this->options[$name];
    }
}