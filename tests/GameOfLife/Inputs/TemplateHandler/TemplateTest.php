<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

use GameOfLife\Field;
use Input\TemplateHandler\Template;
use PHPUnit\Framework\TestCase;

/**
 * Checks whether \Input\TemplateHandler\Template works as expected.
 */
class TemplateTest extends TestCase
{
    /**
     * Checks whether the constructor sets the attributes as expected.
     */
    public function testCanBeConstructed()
    {
        $field = new Field(null, 0, 0);
        $fields = array(
            array($field, $field, $field),
            array($field, $field, $field),
            array($field, $field, $field),
            array($field, $field, $field)
        );

        $template = new Template($fields);

        $this->assertEquals($fields, $template->fields());
        $this->assertEquals(3, $template->width());
        $this->assertEquals(4, $template->height());
    }

    /**
     * Checks whether getters/setters work as expected.
     *
     * @dataProvider setAttributesProvider
     * @covers \Input\TemplateHandler\Template::setWidth()
     * @covers \Input\TemplateHandler\Template::width()
     * @covers \Input\TemplateHandler\Template::setHeight()
     * @covers \Input\TemplateHandler\Template::height()
     * @covers \Input\TemplateHandler\Template::setFields()
     * @covers \Input\TemplateHandler\Template::fields()
     *
     * @param int $_width Template width
     * @param int $_height Template height
     * @param Field[][] $_fields Template fields
     */
    public function testCanSetAttributes(int $_width, int $_height, array $_fields)
    {
        $template = new Template(array(array()));

        $template->setWidth($_width);
        $template->setHeight($_height);
        $template->setFields($_fields);

        $this->assertEquals($_width, $template->width());
        $this->assertEquals($_height, $template->height());
        $this->assertEquals($_fields, $template->fields());
    }

    /**
     * DataProvider for TemplateTest::testCanSetAttributes
     *
     * @return array Test values
     */
    public function setAttributesProvider()
    {
        $field = new Field(null, 0, 0);

        return array(
            array(1, 2, array(array($field), array($field))),
            array(2, 3, array(array($field, $field), array($field, $field), array($field, $field))),
            array(4, 5, array(array($field, $field, $field), array($field, $field, $field))),
        );
    }
}