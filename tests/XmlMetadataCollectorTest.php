<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:50
 */
namespace samsonframework\containerxml\tests;

use samsonframework\container\collection\attribute\ClassName;
use samsonframework\container\collection\attribute\Name;
use samsonframework\container\collection\attribute\Scope;
use samsonframework\container\collection\attribute\Service;
use samsonframework\container\collection\CollectionClassResolver;
use samsonframework\container\collection\CollectionMethodResolver;
use samsonframework\container\collection\CollectionParameterResolver;
use samsonframework\container\collection\CollectionPropertyResolver;
use samsonframework\container\metadata\ClassMetadata;
use samsonframework\container\resolver\XmlResolver;
use samsonframework\containerxml\tests\classes\FastDriver;
use samsonframework\containerxml\tests\classes\Road;
use samsonframework\container\XmlMetadataCollector;

class XmlMetadataCollectorTest extends TestCase
{
    /** @var XmlMetadataCollector */
    protected $xmlCollector;

    public function setUp()
    {
        $xmlConfigurator = new XmlResolver(new CollectionClassResolver([
            Scope::class,
            Name::class,
            ClassName::class,
            Service::class
        ]), new CollectionPropertyResolver([
            ClassName::class
        ]), new CollectionMethodResolver([], new CollectionParameterResolver([
            ClassName::class,
            Service::class
        ])));

        $this->xmlCollector = new XmlMetadataCollector($xmlConfigurator);
    }

    public function testCollect()
    {
        /** @var ClassMetadata[] $classesMetadata */
        $classesMetadata = $this->xmlCollector->collect(file_get_contents(__DIR__.'/config.xml'));

        static::assertEquals(FastDriver::class, $classesMetadata[FastDriver::class]->className);
        static::assertEquals(Road::class, $classesMetadata[Road::class]->className);
    }

    public function testMultipleConfigurations()
    {
        $xmlConfig = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance class="samsonframework\containerxml\\tests\classes\FastDriver" name="MyDriver">
    <methods>
        <__construct>
            <arguments>
                <leg class="samsonframework\containerxml\\tests\classes\Leg"></leg>
            </arguments>
        </__construct>
    </methods>
</instance>
</dependencies>
XML;
        $xmlConfig2 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<dependencies>
<instance class="samsonframework\containerxml\\tests\classes\FastDriver" name="MyDriver">
    <methods>
        <stopCar>
            <arguments>
                <leg class="samsonframework\containerxml\\tests\classes\Leg"></leg>
            </arguments>
        </stopCar>
    </methods>
</instance>
</dependencies>
XML;
        /** @var ClassMetadata[] $classesMetadata */
        $classesMetadata = $this->xmlCollector->collect($xmlConfig);
        /** @var ClassMetadata[] $classesMetadata2 */
        $classesMetadata2 = $this->xmlCollector->collect($xmlConfig2, $classesMetadata);

        static::assertArrayHasKey('__construct', $classesMetadata2[FastDriver::class]->methodsMetadata);
    }
}
