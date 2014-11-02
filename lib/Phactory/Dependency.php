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
    private $dependency;

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
    private function get($parts, $subject)
    {
        $part = array_shift($parts);

        if (method_exists($subject, $part)) {
            $value = call_user_func(array($subject, $part));
        } elseif (is_array($subject) && array_key_exists($part, $subject)) {
            $value = $subject[$part];
        } elseif (is_object($subject) && property_exists($subject, $part)) {
            $value = $subject->$part;
        } else {
            $type = is_object($subject) ? get_class($subject) : gettype($subject);
            throw new \Exception(sprintf("Can't find %s in %s", $part, $type));
        }

        if (count($parts) == 0) {
            return $value;
        } else {
            return $this->get($parts, $value);
        }
    }
}
