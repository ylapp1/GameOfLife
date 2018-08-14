<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Yannick Lapp <yannick.lapp@cn-consult.eu>
 */

namespace Output\BoardRenderer\Text\Border\SymbolDefinition;

/**
 * Defines the collision symbols for one border symbol position (start, center and end) of a text border part.
 */
class CollisionSymbolDefinition
{
	// Attributes

	/**
	 * The symbol to render border part collisions with border parts above the border symbol position
	 *
	 * @var String $collisionFromTopSymbol
	 */
	protected $collisionFromTopSymbol;

	/**
	 * The symbol to render border part collisions with border parts below the border symbol position
	 *
	 * @var String $collisionFromBottomSymbol
	 */
	protected $collisionFromBottomSymbol;

	/**
	 * The symbol to render border part collisions with border parts left from the border symbol position
	 *
	 * @var String $collisionFromLeftSymbol
	 */
	protected $collisionFromLeftSymbol;

	/**
	 * The symbol to render border part collisions with border parts right from the border symbol position
	 *
	 * @var String $collisionFromRightSymbol
	 */
	protected $collisionFromRightSymbol;

	/**
	 * The symbol to render border part collisions with border parts that collide with the border symbol position from the top and the bottom
	 * Must be set when collisions from the top and bottom are possible for the border symbol position
	 *
	 * @var $collisionTopAndBottomSymbol
	 */
	protected $collisionTopAndBottomSymbol;

	/**
	 * The symbol to render border part collisions with border parts that collide with the border symbol position on the left and right side
	 * Must be set when collisions from the left and right are possible for the border symbol position
	 *
	 * @var $collisionLeftAndRightSymbol
	 */
	protected $collisionLeftAndRightSymbol;

	// TODO: Add diagonal collision symbols


	// Magic Methods

	/**
	 * CollisionSymbolDefinition constructor.
	 *
	 * @param String $_collisionFromTopSymbol The symbol to render border part collisions with border parts above the border symbol position
	 * @param String $_collisionFromBottomSymbol The symbol to render border part collisions with border parts below the border symbol position
	 * @param String $_collisionFromLeftSymbol The symbol to render border part collisions with border parts left from the border symbol position
	 * @param String $_collisionFromRightSymbol The symbol to render border part collisions with border parts right from the border symbol position
	 * @param String $_collisionTopAndBottomSymbol The symbol to render border part collisions with border parts that collide with the border symbol position from the top and the bottom
	 * @param String $_collisionLeftAndRightSymbol The symbol to render border part collisions with border parts that collide with the border symbol position on the left and right side
	 */
	public function __construct(String $_collisionFromTopSymbol = "", String $_collisionFromBottomSymbol = "", String $_collisionFromLeftSymbol = "", String $_collisionFromRightSymbol = "", String $_collisionTopAndBottomSymbol = "", String $_collisionLeftAndRightSymbol = "")
	{
		$this->collisionFromTopSymbol = $_collisionFromTopSymbol;
		$this->collisionFromBottomSymbol = $_collisionFromBottomSymbol;
		$this->collisionFromLeftSymbol = $_collisionFromLeftSymbol;
		$this->collisionFromRightSymbol = $_collisionFromRightSymbol;
		$this->collisionTopAndBottomSymbol = $_collisionTopAndBottomSymbol;
		$this->collisionLeftAndRightSymbol = $_collisionLeftAndRightSymbol;
	}


	// Getters and Setters

	/**
	 * Returns the symbol to render border part collisions with border parts above the border symbol position.
	 *
	 * @return String The symbol to render border part collisions with border parts above the border symbol position
	 */
	public function collisionFromTopSymbol(): String
	{
		return $this->collisionFromTopSymbol;
	}

	/**
	 * Returns the symbol to render border part collisions with border parts below the border symbol position.
	 *
	 * @return String The symbol to render border part collisions with border parts below the border symbol position
	 */
	public function collisionFromBottomSymbol(): String
	{
		return $this->collisionFromBottomSymbol;
	}

	/**
	 * Returns the symbol to render border part collisions with border parts left from the border symbol position.
	 *
	 * @return String The symbol to render border part collisions with border parts left from the border symbol position
	 */
	public function collisionFromLeftSymbol(): String
	{
		return $this->collisionFromLeftSymbol;
	}

	/**
	 * Returns the symbol to render border part collisions with border parts right from the border symbol position.
	 *
	 * @return String The symbol to render border part collisions with border parts right from the border symbol position
	 */
	public function collisionFromRightSymbol(): String
	{
		return $this->collisionFromRightSymbol;
	}

	/**
	 * Returns the symbol to render border part collisions with border parts that collide with the border symbol position from the top and the bottom.
	 *
	 * @return String The symbol to render border part collisions with border parts that collide with the border symbol position from the top and the bottom
	 */
	public function collisionTopAndBottomSymbol(): String
	{
		return $this->collisionTopAndBottomSymbol;
	}

	/**
	 * Returns the symbol to render border part collisions with border parts that collide with the border symbol position on the left and right side.
	 *
	 * @return String The symbol to render border part collisions with border parts that collide with the border symbol position on the left and right side
	 */
	public function collisionLeftAndRightSymbol(): String
	{
		return $this->collisionLeftAndRightSymbol;
	}
}
