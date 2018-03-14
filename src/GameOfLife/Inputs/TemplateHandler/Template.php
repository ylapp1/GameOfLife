<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace TemplateHandler;

use GameOfLife\Field;

/**
 * Stores a loaded template.
 */
class Template
{
    /**
     * The template width
     *
     * @var int $width
     */
    private $width;

    /**
     * The template height
     *
     * @var int $height
     */
    private $height;

    /**
     * The list of fields
     *
     * @var Field[][] $fields
     */
    private $fields;


    /**
     * Template constructor.
     *
     * @param Field[][] $_fields The list of fields
     */
    public function __construct(array $_fields)
    {
        $this->height = count($_fields);
        $this->width = count($_fields[0]);
        $this->fields = $_fields;
    }


    /**
     * Returns the template width.
     *
     * @return int The template width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Sets the template width.
     *
     * @param int $_width The template width
     */
    public function setWidth(int $_width)
    {
        $this->width = $_width;
    }

    /**
     * Returns the template height.
     *
     * @return int The template height
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Sets the template height.
     *
     * @param int $_height The template height
     */
    public function setHeight(int $_height)
    {
        $this->height = $_height;
    }

    /**
     * Returns the list of template fields.
     *
     * @return Field[][] The list of template fields
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Sets the list of template fields.
     *
     * @param Field[][] $_fields The list of template fields
     */
    public function setFields(array $_fields)
    {
        $this->fields = $_fields;
    }


    /**
     * Returns a specific field from the list of fields.
     *
     * @param int $_x The X-coordinate of the field
     * @param int $_y The Y-coordinate of the field
     *
     * @return Field The field
     */
    public function getField(int $_x, int $_y): Field
    {
        return $this->fields[$_y][$_x];
    }
}
