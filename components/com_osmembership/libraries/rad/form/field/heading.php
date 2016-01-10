<?php
class RADFormFieldHeading extends RADFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *	 
	 */
	protected  $type = 'Heading';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *	 
	 */
	protected function getInput()
	{
		return '<h3 class="osm-heading">'.JText::_($this->title).'</h3>';
	}
	
	
	public function getControlGroup()
	{
		return $this->getInput();
	}
}