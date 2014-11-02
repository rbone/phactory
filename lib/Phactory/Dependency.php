<?php

namespace Phactory;

/**
 * Represent values you need to have exactly equal accross different objects.
 * For example, you may want the first comment author and the topic author to
 * be the same user.
 */
class Dependency
{
    /**
     * Dependency path, i.e.: "topic.author" in comments factory
     * @var string
     */
    protected $dependency;

    /**
     * @param string $dependency dependency path, i.e.: "topic.author"
     */
    public function __construct($dependency)
    {
        $this->dependency = $dependency;
    }

    /**
     * Solve the dependency path for a given blueprint or object
     * @param array|object $blueprint
     * @return Mixed
     */
    public function meet($blueprint)
    {
        $parts = explode('.', $this->dependency);

        return $this->get($parts, $blueprint);
    }

    /**
     * Recursively search for the necessary parts of the dependency path
     * @param array $parts each part of the path, i.e., "topic" and "author" in "topic.author"
     * @param object|array $subject
     * @return Mixed
     * @throws \Exception
     */
    protected function get($parts, $subject)
    {
        $part = array_shift($parts);

        if (!$this->has($part, $subject)) {
            $type = is_object($subject) ? get_class($subject) : gettype($subject);
            throw new \Exception(sprintf("Can't find %s in %s", $part, $type));
        } elseif (is_array($subject)) {
            $value = $subject[$part];
        } elseif (method_exists($subject, $part)) {
            $value = call_user_func(array($subject, $part));
        } else {
            $value = $subject->$part;
        }

        if (count($parts) == 0) {
            return $value;
        } else {
            return $this->get($parts, $value);
        }
    }

    /**
     * Check if the necessary part exists in the dependency
     * @param $part i.e., "author" in "topic.author"
     * @param object|array $subject
     * @return boolean
     */
    protected function has($part, $subject)
    {
        return (
            method_exists($subject, $part)
            || (is_array($subject) && array_key_exists($part, $subject))
            || (is_object($subject) && property_exists($subject, $part))
        );
    }
}
