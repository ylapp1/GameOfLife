<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Input\TemplateHandler;

use GameOfLife\Field;

/**
 * Stores a loaded template.
 */
class Template
{
    /**
     * Template width
     *
     * @var int $width
     */
    private $width;

    /**
     * Template height
     *
     * @var int $height
     */
    private $height;

    /**
     * The array of fields
     *
     * @var Field[][]
     */
    private $fields;


    /**
     * Template constructor.
     *
     * @param Field[][] $_fields Template fields
     */
    public function __construct(array $_fields)
    {
        $this->fields = $_fields;
        $this->height = count($_fields);
        $this->width = count($_fields[0]);
    }


    /**
     * Returns the template width.
     *
     * @return int Template width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Sets the template width.
     *
     * @param int $_width Template width
     */
    public function setWidth(int $_width)
    {
        $this->width = $_width;
    }

    /**
     * Returns the template height.
     *
     * @return int Template height
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Sets the template height.
     *
     * @param int $_height Template height
     */
    public function setHeight(int $_height)
    {
        $this->height = $_height;
    }

    /**
     * Returns the template fields.
     *
     * @return Field[][] Template fields
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Sets the template fields.
     *
     * @param Field[][] $_fields Template fields
     */
    public function setFields(array $_fields)
    {
        $this->fields = $_fields;
    }


    /**
     * Returns a single field of this template.
     *
     * @param int $_x X-Position of the field
     * @param int $_y Y-Position of the field
     *
     * @return Field The field
     */
    public function getField(int $_x, int $_y): Field
    {
        return $this->fields[$_y][$_x];
    }
}