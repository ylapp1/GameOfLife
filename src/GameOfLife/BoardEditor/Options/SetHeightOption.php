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
        parent::__construct(
            $_parentBoardEditor,
            "height",
            array(),
            "setHeight",
            "Sets the board height",
            array("Height" => "int")
        );
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
    public function setHeight(int $_height): Bool
    {
        if ($_height < 1) throw new \Exception("The board height may not be less than 1.");
        elseif ($_height == $this->parentBoardEditor->board()->height())
        {
            throw new \Exception("The board height is already " . $_height . ".");
        }
        else
        {
            $fields = $this->parentBoardEditor->board()->fields();

            // Update selection coordinates
            $selectionCoordinates = $this->parentBoardEditor->selectionCoordinates();
            if ($selectionCoordinates != array() && $_height - 1 < $selectionCoordinates["B"]["y"])
            {
                $selectionCoordinates["B"]["y"] = $_height - 1;
                if ($selectionCoordinates["B"]["y"] - $selectionCoordinates["A"]["y"] < 1)
                {
                    $selectionCoordinates = array();
                }

                $this->parentBoardEditor->setSelectionCoordinates($selectionCoordinates);
            }

            $this->parentBoardEditor->board()->setHeight($_height);
            $this->parentBoardEditor->board()->resetFields();

            foreach ($fields as $row)
            {
                /** @var Field $field */
                foreach ($row as $field)
                {
                    if ($field->coordinate()->y() < $this->parentBoardEditor()->board()->height())
                    {
                        $this->parentBoardEditor()->board()->setFieldState(
                        	$field->coordinate()->x(),
	                        $field->coordinate()->y(),
	                        $field->isAlive()
                        );
                    }
                }
            }

            $this->parentBoardEditor->outputBoard();
        }

        return false;
    }
}
