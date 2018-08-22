<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace BoardRenderer\Text\Border\SymbolDefinition;

/**
 * Defines the symbols of a border.
 */
class BorderSymbolDefinition
{
	// Attributes

	/**
	 * The symbol for the start of the border
	 *
	 * @var String $startSymbol
	 */
	private $startSymbol;

	/**
	 * The symbol for the center parts of the border
	 *
	 * @var String $centerSymbol
	 */
	private $centerSymbol;

	/**
	 * The symbol for the end of the border
	 *
	 * @var String $endSymbol
	 */
	private $endSymbol;

	/**
	 * The collision symbol definitions
	 *
	 * @var CollisionSymbolDefinition[] $collisionSymbolDefinitions
	 */
	private $collisionSymbolDefinitions;


	// Magic Methods

	/**
	 * BorderSymbolDefinition constructor.
	 *
	 * @param String $_startSymbol The symbol for the start of the border
	 * @param String $_centerSymbol The symbol for the center parts of the border
	 * @param String $_endSymbol The symbol for the end of the border
	 * @param CollisionSymbolDefinition[] $_collisionSymbolDefinitions The collision symbol definitions
	 */
	public function __construct(String $_startSymbol, String $_centerSymbol, String $_endSymbol, array $_collisionSymbolDefinitions)
	{
		$this->startSymbol = $_startSymbol;
		$this->centerSymbol = $_centerSymbol;
		$this->endSymbol = $_endSymbol;
		$this->collisionSymbolDefinitions = $_collisionSymbolDefinitions;
	}


	// Getters and Setters

	/**
	 * Returns the symbol for the start of the border.
	 *
	 * @return String The symbol for the start of the border
	 */
	public function startSymbol(): String
	{
		return $this->startSymbol;
	}

	/**
	 * Returns the symbol for the center parts of the border.
	 *
	 * @return String The symbol for the center parts of the border
	 */
	public function centerSymbol(): String
	{
		return $this->centerSymbol;
	}

	/**
	 * Returns the symbol for the end of the border.
	 *
	 * @return String The symbol for the end of the border
	 */
	public function endSymbol(): String
	{
		return $this->endSymbol;
	}

	/**
	 * Returns the collision symbol definitions.
	 *
	 * @return CollisionSymbolDefinition[] $collisionSymbolDefinitions
	 */
	public function collisionSymbolDefinitions(): array
	{
		return $this->collisionSymbolDefinitions;
	}
}
