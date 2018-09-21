<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace GameOfLife;

/**
 * Stores the board configuration and the fields for one game step.
 * It also provides methods to get information about and to manipulate the fields.
 */
class Board
{
    // Attributes

    /**
     * Stores the fields of the current game step
     *
     * @var Field[][] $fields
     */
    private $fields;

    /**
     * Defines whether the board has a border
     *
     * True: The borders are treated like dead cells
     * False: The borders link to the opposite side of the field
     *
     * @var Bool $hasBorder
     */
    private $hasBorder;

    /**
     * The board height
     *
     * @var int $height
     */
    private $height;

    /**
     * The board width
     *
     * @var int $width
     */
    private $width;


    // Magic Methods

    /**
     * Board constructor.
     *
     * @param int $_width The board width
     * @param int $_height The board height
     * @param Bool $_hasBorder The border type
     *                         True: The borders are treated like dead cells
     *                         False: The borders link to the opposite side of the field
     */
    public function __construct(int $_width, int $_height, Bool $_hasBorder)
    {
        $this->hasBorder = $_hasBorder;
        $this->height = $_height;
        $this->width = $_width;
        $this->fields = $this->generateFieldsList(false);
    }

    /**
     * Clones the fields of the original board and updates their parent board.
     * This method is called on the new object after a shallow copy of the original object was performed.
     */
    public function __clone()
    {
        foreach ($this->fields as $y => $rowFields)
        {
            foreach ($rowFields as $x => $rowField)
            {
                $field = clone $rowField;
                $field->setParentBoard($this);
                $this->fields[$y][$x] = $field;
            }
        }
    }

    /**
     * Converts the board to a string.
     *
     * @return String The string that represents the board
     */
    public function __toString(): String
    {
        $string = "";

        for ($y = 0; $y < $this->height; $y++)
        {
            for ($x = 0; $x < $this->width; $x++)
            {
                if ($this->getFieldState($x, $y)) $string .= "X";
                else $string .= ".";
            }

            if ($y != $this->height - 1) $string .= "\r\n";
        }

        return $string;
    }

    /**
     * Copies a board into this object.
     *
     * @param Board $_board The board that will be copied
     */
    function copy(Board $_board)
    {
        $this->hasBorder = $_board->hasBorder();
        $this->height = $_board->height();
        $this->width = $_board->width();

        $this->fields = $this->generateFieldsList(false);
        foreach ($_board->fields() as $y => $rowFields)
        {
            foreach ($rowFields as $x => $rowField)
            {
                $this->fields[$y][$x]->setValue($rowField->value());
            }
        }
    }

    /**
     * Checks whether a board is the same like this board.
     *
     * @param Board $_compareBoard The board that will be compared
     *
     * @return Bool True: The boards are equal
     *              False: The boards are not equal
     */
    public function equals(Board $_compareBoard): Bool
    {
        // Check board attributes
        if ($this->hasBorder != $_compareBoard->hasBorder() ||
            $this->height != $_compareBoard->height() ||
            $this->width != $_compareBoard->width())
        {
            return false;
        }

        // Check fields

        /*
         * This check assumes that there is at least one row and
         * that each row has the same number of columns
         */
        if (count($this->fields) != count($_compareBoard->fields()) ||
            count($this->fields[0]) != count($_compareBoard->fields()[0]))
        {
            return false;
        }

        $fieldsAreEqual = true;

        foreach ($_compareBoard->fields() as $y => $rowFields)
        {
            foreach ($rowFields as $x => $rowField)
            {
                $boardField = $this->fields[$y][$x];

                if ($rowField->value() != $boardField->value() ||
                    $rowField->coordinate()->x() != $boardField->coordinate()->x() ||
                    $rowField->coordinate()->y() != $boardField->coordinate()->y())
                {
                    $fieldsAreEqual = false;
                    break;
                }
            }

            if (! $fieldsAreEqual) break;
        }

        return $fieldsAreEqual;
    }


    // Getters and Setters

    /**
     * Returns the fields of the current game step.
     *
     * @return Field[][] The fields of the current game step
     */
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Sets the fields of the current game step.
     *
     * @param Field[][] $_fields The fields of the current game step
     */
    public function setFields(array $_fields)
    {
        $this->fields = $_fields;
    }

    /**
     * Returns the border type.
     *
     * @return Bool The border type
     *              True: The borders are treated like dead cells
     *              False: The borders link to the opposite side of the field
     */
    public function hasBorder(): Bool
    {
        return $this->hasBorder;
    }

    /**
     * Sets the border type.
     *
     * @param Bool $_hasBorder The border type
     *                         True: The borders are treated like dead cells
     *                         False: The borders link to the opposite side of the field
     */
    public function setHasBorder(Bool $_hasBorder)
    {
        $this->hasBorder = $_hasBorder;
    }

    /**
     * Returns the board height.
     *
     * @return int The board height
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Sets the board height.
     *
     * @param int $_height The board height
     */
    public function setHeight(int $_height)
    {
        $this->height = $_height;
    }

    /**
     * Returns the board width.
     *
     * @return int The board width
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Sets the board width.
     *
     * @param int $_width The board width
     */
    public function setWidth(int $_width)
    {
        $this->width = $_width;
    }


    // Class Methods

    // Get information about the fields

    /**
     * Returns the state of the cell in a field.
     *
     * @param int $_x The X-Coordinate of the field
     * @param int $_y The Y-Coordinate of the field
     *
     * @return Bool The state of the cell in the field
     *              True: The cell in the field is alive
     *              False: The cell in the field is dead
     */
    public function getFieldState(int $_x, int $_y): Bool
    {
        return $this->fields[$_y][$_x]->isAlive();
    }

    /**
     * Returns the neighbor fields of a field.
     *
     * @param Field $_field The field
     *
     * @return Field[] The neighbor fields of the field
     */
    public function getNeighborsOfField(Field $_field): array
    {
        $x = $_field->coordinate()->x();
        $y = $_field->coordinate()->y();

        $columns = array($x);
        $rows = array($y);

        // Column to the left
        if ($x == 0)
        {
            if (! $this->hasBorder) $columns[] = $this->width - 1;
        }
        else $columns[] = $x - 1;

        // Column to the right
        if ($x + 1 == $this->width)
        {
            if (! $this->hasBorder) $columns[] = 0;
        }
        else $columns[] = $x + 1;

        // Row above
        if ($y == 0)
        {
            if (! $this->hasBorder) $rows[] = $this->height - 1;
        }
        else $rows[] = $y - 1;

        // Row below
        if ($y + 1 == $this->height)
        {
            if (! $this->hasBorder) $rows[] = 0;
        }
        else $rows[] = $y + 1;


        $neighborFields = array();
        foreach ($rows as $y)
        {
            foreach ($columns as $x)
            {
                if ($y != $_field->coordinate()->y() || $x != $_field->coordinate()->x()) $neighborFields[] = $this->fields[$y][$x];
            }
        }

        return $neighborFields;
    }

    /**
     * Returns the number of alive cells.
     *
     * @return int The number of alive cells
     */
    public function getNumberOfAliveFields(): int
    {
        $numberOfAliveFields = 0;
        foreach ($this->fields as $rowFields)
        {
            foreach ($rowFields as $rowField)
            {
                $numberOfAliveFields += $rowField->isAlive();
            }
        }
        return $numberOfAliveFields;
    }

    /**
     * Returns the percentage of cells whose state is alive.
     *
     * @return float The percentage of cells whose state is alive
     */
    public function getPercentageOfAliveFields(): float
    {
        return (float)($this->getNumberOfAliveFields() / ($this->width * $this->height));
    }


    // Manipulate fields

    /**
     * Generates an array of fields from the board width and height.
     *
     * @param Bool $_fieldsState The state to which all of the fields will be set
     *
     * @return Field[][] The list of fields
     */
    public function generateFieldsList(Bool $_fieldsState): array
    {
        $fields = array();

        for ($y = 0; $y < $this->height; $y++)
        {
            $fields[$y] = array();
            for ($x = 0; $x < $this->width; $x++)
            {
                $fields[$y][$x] = new Field($x, $y, $_fieldsState, $this);
            }
        }

        return $fields;
    }

    /**
     * Inverts all fields of the board.
     */
    public function invertFields()
    {
        foreach ($this->fields as $rowFields)
        {
            foreach ($rowFields as $rowField)
            {
                $rowField->invertValue();
            }
        }
    }

    /**
     * Sets all cells to the state dead.
     */
    public function resetFields()
    {
        $this->fields = $this->generateFieldsList(false);
    }

    /**
     * Sets the state of the cell in a field.
     *
     * @param int $_x The X-Coordinate of the field
     * @param int $_y The Y-Coordinate of the field
     * @param Bool $_isAlive The state to which the cell in the field will be set
     *                       True: The cell in the field is alive
     *                       False: The cell in the field is dead
     */
    public function setFieldState(int $_x, int $_y, Bool $_isAlive)
    {
        $this->fields[$_y][$_x]->setValue($_isAlive);
    }
}
