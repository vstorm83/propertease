<?php

/**
 * Form Field class for the Joomla RAD.
 * Supports a textarea inut.
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldTextarea extends RADFormField
{

	protected $type = 'Textarea';

	/**
	 * Visable attributes, which will be displayed on field settings form
	 *
	 * @var array
	 */
	public static $visibleProperties = array('rows', 'cols', 'place_holder', 'max_length');

	/**
	 * Required properties, which will be used for js validate before the field is saved
	 *
	 * @var array
	 */
	public static $requiredProperties = array();

	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);
		if ($row->place_holder)
		{
			$this->attributes['placeholder'] = $row->place_holder;
		}
		if ($row->max_length)
		{
			$this->attributes['maxlength'] = $row->max_length;
		}
		if ($row->rows)
		{
			$this->attributes['rows'] = $row->rows;
		}
		if ($row->cols)
		{
			$this->attributes['cols'] = $row->cols;
		}
	}

	public function getInput()
	{
		$attributes = $this->buildAttributes();
		return '<textarea name="' . $this->name . '" id="' . $this->name . '"' . $attributes . $this->extraAttributes . ' >' .
			 htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
	}
}