<?php
/**
 * Form Field class for the Joomla RAD.
 * Supports a radiolist custom field.
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldRadio extends RADFormField
{

	/**
	 * The form field type.
	 *
	 * @var string
	 *
	 */
	protected $type = 'Radio';

	protected $values;

	protected $size;

	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);
		
		$this->values = $row->values;
		$size = (int) $row->size;
		if ($size)
		{
			$this->size = $size;
		}
		else
		{
			$this->size = 1; // Each item in one line by default
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return string The field input markup.
	 *        
	 */
	protected function getInput()
	{
		$html = array();
		$options = (array) $this->getOptions();
		$attributes = $this->buildAttributes();
		$value = trim($this->value);
		$html[] = '<fieldset id="' . $this->name . '"' . '>';
		$html[] = '<ul class="clearfix">';
		$i = 0;
		$span = intval(12 / $this->size);
		$numberOptions = count($options);
		foreach ($options as $option)
		{
			$i++;
			$optionValue = trim($option);
			$checked = ($optionValue == $value) ? 'checked' : '';
			$html[] = '<li class="span' . $span . '">';
			$html[] = '<label for="' . $this->name . $i . '" ><input type="radio" id="' . $this->name . $i . '" name="' . $this->name . '" value="' .
				 htmlspecialchars($optionValue, ENT_COMPAT, 'UTF-8') . '"' . $checked . $attributes . $this->extraAttributes . '/> ' . $option .
				 '</label>';
			$html[] = '</li>';
			if ($i % $this->size == 0 && $i < $numberOptions)
			{
				$html[] = '</ul>';
				$html[] = '<ul class="clearfix">';
			}
		}
		// End the checkbox field output.
		$html[] = '</fieldset>';
		
		return implode($html);
	}

	protected function getOptions()
	{
		$options = array();
		if (is_array($this->values))
		{
			$options = $this->values;
		}
		elseif (strpos($this->values, "\r\n") !== FALSE)
		{
			$values = explode("\r\n", $this->values);
		}
		else
		{
			$values = explode(",", $this->values);
		}
		return $values;
	}
}