<?php

namespace LLS\Bundle\AWSBundle\Annotation;

/**
 * Identity Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
class Identity
{
    /**
     * @var string
     */
    public $name;

    /**
     * @Enum({"getter", "setter"})
     */
    public $type = "getter";
}