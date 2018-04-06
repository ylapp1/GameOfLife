<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Utils\Shell;

/**
 * Prints tables to the shell.
 */
class ShellTablePrinter extends ShellOutputHelper
{
    /**
     * Prints a table to the console.
     *
     * @param String[][] $_tableRows The table rows in the format array(0 => array("fieldA", "fieldB"))
     * @param int $_paddingOnAutoLineBreak The amount of padding for every new line
     * @param int[] $_columnWidthPercentages The custom column width percentages in the format array("x" => "percentage")
     * @param bool $_lineBreakOnEmptyWord Indicates whether the line breaks will only be placed between words
     */
    public function printTable(array $_tableRows, int $_paddingOnAutoLineBreak = 0, array $_columnWidthPercentages = null, bool $_lineBreakOnEmptyWord = false)
    {
        $columnWidths = $this->getTableColumnWidths(count($_tableRows[0]), $_columnWidthPercentages);

        // Print the table
        foreach ($_tableRows as $y => $row)
        {
            $subFields = array();

            foreach ($row as $x => $field)
            {
                $fieldSubRows = $this->getFieldSubRows($field, $columnWidths[$x], $_paddingOnAutoLineBreak, $_lineBreakOnEmptyWord);
                foreach ($fieldSubRows as $subY => $subField)
                {
                    $subFields[$subY][$x] = $subField;
                }
            }

            foreach ($subFields as $subY => $subRow)
            {
                foreach ($subRow as $subX => $subField)
                {
                    echo str_pad($subField, $columnWidths[$subX]);
                }

                echo "\n";
            }
        }
    }

    /**
     * Returns the table column widths in numbers of symbols.
     *
     * @param int $_numberOfColumns The number of columns
     * @param int[] $_columnWidthPercentages The custom column width percentages in the format array("x" => "percentage")
     *
     * @return int[] The table column widths in numbers of symbols
     */
    private function getTableColumnWidths(int $_numberOfColumns, array $_columnWidthPercentages = null)
    {
        // Determine the column widths
        $columnWidths = array();

        for ($x = 0; $x < $_numberOfColumns; $x++)
        {
            $columnWidthPercentage = 100 / $_numberOfColumns;
            if ($_columnWidthPercentages && $_columnWidthPercentages[$x])
            {
                $columnWidthPercentage = $_columnWidthPercentages[$x];
            }

            $columnWidths[$x] = floor(($columnWidthPercentage / 100) * $this->numberOfShellColumns);
        }

        $currentColumn = count($columnWidths) - 1;
        while (array_sum($columnWidths) >= $this->numberOfShellColumns - 1)
        {
            $columnWidths[$currentColumn] -= 1;
            if ($currentColumn == count($columnWidths) - 1) $currentColumn = 0;
        }

        return $columnWidths;
    }

    /**
     * Returns the sub rows of a field that are created because the field exceeds the column width.
     *
     * @param String $_field The field text
     * @param int $_columnWidth The column width
     * @param int $_paddingOnAutoLineBreak The amount of padding for every new line
     * @param bool $_lineBreakOnEmptyWord Indicates whether the line breaks will only be placed between words
     *
     * @return String[] The sub rows in the format array("subY" => "subRow")
     */
    private function getFieldSubRows(String $_field, int $_columnWidth, int $_paddingOnAutoLineBreak = 0, bool $_lineBreakOnEmptyWord = false)
    {
        $subRows = array();
        $currentRow = 0;

        while (mb_strlen($_field) > $_columnWidth)
        {
            if (! $_lineBreakOnEmptyWord) $endCharacterPosition = $_columnWidth - 1;
            else
            {
                $fieldWords = explode(" ", $_field);

                if ($currentRow == 0) $padding = 0;
                else $padding = $_paddingOnAutoLineBreak;

                $endCharacterPosition = 0;
                $currentWord = 0;

                while (true)
                {
                    if ($currentWord == 0) $emptySpaceBeforeWord = 0;
                    else $emptySpaceBeforeWord = 1;

                    $wordLength = mb_strlen($fieldWords[$currentWord]);

                    $newEndCharacterPosition = $endCharacterPosition + $padding + $emptySpaceBeforeWord + $wordLength;
                    if ($newEndCharacterPosition <= $_columnWidth - 1)
                    {
                        $endCharacterPosition = $newEndCharacterPosition;
                    }
                    else break;

                    $currentWord++;
                }

                if ($currentWord == 0) $endCharacterPosition = $_columnWidth - 1;
            }

            $subRows[$currentRow] = substr($_field, 0, $endCharacterPosition);

            $padding = str_repeat(" ", $_paddingOnAutoLineBreak);
            $_field = substr_replace($_field, "", 0, $endCharacterPosition);
            $_field = $padding . trim($_field);
            $currentRow++;
        }
        $subRows[$currentRow] = $_field;

        return $subRows;
    }
}
