<?php

use Wirecard\IsoToPayPal\Converter;
use Wirecard\IsoToPayPal\Exception\CountryNotFoundException;
use Wirecard\IsoToPayPal\Exception\StateNotFoundException;

class ConverterUTest extends PHPUnit_Framework_TestCase
{
    /** @var Converter */
    private $converter;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    public function testLoadingOfMapping()
    {
        $ref = new ReflectionObject($this->converter);

        $prop = $ref->getProperty('mapping');
        $prop->setAccessible(true);

        $this->assertArrayHasKey('JP', $prop->getValue($this->converter));
    }

    public function testConversionWithState()
    {
        $state = $this->converter->convert('JP', '01');

        $this->assertEquals("HOKKAIDO", $state);
    }

    public function testConversionFailsWhenNoCountryFound()
    {
        $this->expectException(CountryNotFoundException::class);

        $this->converter->convert('AT', '1');
    }

    public function testConversionFailsWhenNoStateFound()
    {
        $this->expectException(StateNotFoundException::class);

        $this->converter->convert('US', 'ZZ');
    }

    public function testConversionWithoutStateParameter()
    {
        $state = $this->converter->convert('TH-S');

        $this->assertEquals("Phatthaya", $state);
    }

    public function testConversionFailsWithWrongFormat()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->converter->convert('US');
    }
}
