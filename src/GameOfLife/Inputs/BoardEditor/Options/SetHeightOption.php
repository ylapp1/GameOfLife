<?php
/**
 * @file
 * @version 0.1
 * @copyright 20172-108 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardEditor\Options;

use BoardEditor\BoardEditor;
use BoardEditor\BoardEditorOption;
use GameOfLife\Field;

/**
 * Sets the height of the edited board.
 */
class SetHeightOption extends BoardEditorOption
{
    /**
     * SetHeightOption constructor.
     *
     * @param BoardEditor $_parentBoardEditor Parent board editor
     */
    public function __construct(BoardEditor $_parentBoardEditor)
    {
        parent::__construct($_parentBoardEditor);

        $this->name = "height";
        $this->callback = "setHeight";
        $this->description = "Sets the board height";
        $this->arguments = array("height" => "int");
    }

    /**
     * Sets the height of the currently edited board.
     *
     * @param int $_height New board height
     *
     * @return bool Indicates whether the board editing is finished
     *
     * @throws \Exception The exception when the specified height is less than 1
     */
    public function setHeight($_height)
    {
        $height = (int)$_height;

        if ($height < 1) throw new \Exception("The board height may not be less than 1.");
        else
        {
            $fields = $this->parentBoardEditor->board()->fields();

            $this->parentBoardEditor->board()->setHeight($height);
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

            $this->parentBoardEditor->outputBoard();
        }

        return false;
    }
}
