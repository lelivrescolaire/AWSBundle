<?php

namespace LLS\Bundle\AWSBundle\Tests\Units\Annotation;

use \mageekguy\atoum\test;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyAccess\PropertyAccess;

use LLS\Bundle\AWSBundle\Annotation\Identity as IdentityAnnotation;
use LLS\Bundle\AWSBundle\Tests\Fixtures\AnnotatedModel;

/**
 * Test class for Identity Annotation
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class Identity extends test
{
    protected $reader;
    protected $accessor;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->reader   = new AnnotationReader();
    }

    public function testRetrieveDataFromAnnotation()
    {
        $resultArr   = array();
        $class       = new \ReflectionClass($model = new AnnotatedModel());
        $methods     = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $properties  = $class->getproperties();

        foreach ($methods as $method) {
            $annotations = $this->reader->getMethodAnnotations($method);

            foreach ($annotations as $annotation) {
                switch (true)
                {
                    case $annotation instanceof IdentityAnnotation:
                        if (!empty($annotation->name) && ($annotation->type == 'getter')) {
                            $resultArr[$annotation->name] = $method->invoke($model);
                        }
                        break;
                }
            }
        }

        foreach ($properties as $property) {
            $annotations = $this->reader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation) {
                switch (true)
                {
                    case $annotation instanceof IdentityAnnotation:
                        if (empty($annotation->name)) {
                            $annotation->name = $property->getName();
                        }

                        $resultArr[$annotation->name] = $this->accessor->getValue($model, $property->getName());
                        break;
                }
            }
        }

        $this
            ->assert
                ->array($resultArr)
                    ->hasSize(3)
                    ->hasKeys(array("implicitName", "explicitName", "virtualName"))
                    ->isIdenticalTo(array(
                        "virtualName"  => "virtualValue",
                        "implicitName" => "implicitValue",
                        "explicitName" => "explicitValue"
                    ));
    }
}
