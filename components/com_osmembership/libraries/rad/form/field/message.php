<?php
/**
 * Form Field class for the Joomla RAD.
 * Supports a message form field
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldMessage extends RADFormField
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 *	 
	 */
	protected $type = 'Message';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *	 
	 */
	public function getInput()
	{
		return '<div class="control-group osm-message">'.$this->description.'</div>';	
	}
	
	public function getControlGroup()
	{
		return $this->getInput();
	}
}