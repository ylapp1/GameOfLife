<?php
/**
 * @file
 * @version 0.1
 * @copyright 2017-2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use GameOfLife\Field;

/**
 * Sets the width of the edited board.
 */
class SetWidthOption extends BoardEditorOption
{
    /**
     * SetWidthOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "width";
        $this->callback = "setWidth";
        $this->description = "Sets the board width";
        $this->arguments = array("number");
    }

    /**
     * Sets the width of the currently edited board.
     *
     * @param int $_width New board width
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the specified with is less than 1
     */
    public function setWidth($_width)
    {
        $width = (int)$_width;

        if ($width < 1) throw new \Exception("The board width may not be less than 1.");
        else
        {
            $fields = $this->parentBoardEditor->board()->fields();

            $this->parentBoardEditor->board()->setWidth($width);
            $this->parentBoardEditor->board()->resetBoard();

            foreach ($fields as $row)
            {
                foreach ($row as $field)
                {
                    if ($field instanceof Field)
                    {
                        if ($field->x() < $this->parentBoardEditor()->board()->width() &&
                            $field->y() < $this->parentBoardEditor()->board()->height())
                        {
                            $this->parentBoardEditor()->board()->setField($field->x(), $field->y(), $field->isAlive());
                        }
                    }
                }
            }

            $this->parentBoardEditor->output()->outputBoard($this->parentBoardEditor->board());
        }

        return false;
    }
}
