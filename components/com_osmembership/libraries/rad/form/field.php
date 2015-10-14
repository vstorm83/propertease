<?php
/**
 * Abstract Form Field class for the RAD framework
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
abstract class RADFormField
{

	/**
	 * Id of the field object in the database
	 * 
	 * @var int
	 */
	protected $id;
	/**
	 * The form field type.
	 *
	 * @var    string	 
	 */
	protected $type;

	/**
	 * The name (and id) for the form field.
	 *
	 * @var    string	 
	 */
	protected $name;
	
	/**
	 * It is a core field or not
	 * 
	 * @var int
	 */
	protected $is_core;

	/**
	 * The title for the form field.
	 *
	 * @var    string
	 */
	protected $title;

	/**
	 * The description text for the form field. Usually used in tooltips.
	 *
	 * @var    string	 
	 */
	protected $description;

	/**
	 * The indication whether a field is required or not
	 *
	 * @var    boolean|int
	 */
	protected $required;

	/**
	 * The value of the form field.
	 *
	 * @var    mixed
	 */
	protected $value;

	/**
	 * The html attributes of the field
	 * 
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * The extra attributes of the field. You can enter any HTML attributes you want into that field
	 * 
	 * @var string
	 */
	protected $extraAttributes;

	/**
	 * The label for the form field.
	 *
	 * @var    string	 
	 */
	protected $label;

	/**
	 * The input for the form field.
	 *
	 * @var    string	
	 */
	protected $input;
	
	/**
	 * The row object used to store field definition
	 * 
	 * @var object
	 */
	protected $row;
	/**
	 * This field is used in fee calculation or not
	 *
	 * @var bool
	 */
	protected $feeCalculation;
	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   JTable  $row  the table object store form field attribute
	 *
	 */
	public function __construct($row, $value = null, $fieldSuffix = null)
	{
		$this->row = $row;
		$this->id = $row->id;
		$this->name = $row->name . $fieldSuffix;
		$this->is_core = $row->is_core;
		$this->title = $row->title;
		$this->description = $row->description;
		$this->value = $value;
		$this->required = $row->required;
		$this->extraAttributes = $row->extra;						
		$cssClasses = array();
		if ($row->css_class)
		{
			$cssClasses[] = $row->css_class;
		}
		if ($row->validation_rules)
		{
			$cssClasses[] = $row->validation_rules;
		}
		if (count($cssClasses))
		{
			$this->attributes['class'] = implode(' ', $cssClasses);
		}
		if ($row->validation_error_message)
		{
			$this->attributes['data-errormessage'] = $row->validation_error_message;
		}
		
	}

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *	 
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'id' :
			case 'type':
			case 'name':			
			case 'title':
			case 'description':
			case 'is_core':
			case 'required':	
			case 'value':	
			case 'row':	
				return $this->{$name};
				break;	
			case 'fee_formula':
			case 'fee_field':	
				return $this->row->{$name};
				break;
			case 'input':
				// If the input hasn't yet been generated, generate it.
				if (empty($this->input))
				{
					$this->input = $this->getInput();
				}				
				return $this->input;
			case 'label':
				// If the label hasn't yet been generated, generate it.
				if (empty($this->label))
				{
					$this->label = $this->getLabel();
				}
				return $this->label;
				break;
		}
		
		return null;
	}

	/**
	 * Simple method to set the value
	 *
	 * @param   mixed  $value  Value to set
	 *	 	
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	/**
	 * Add attribute to the form field
	 * @param string $name
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}
	/**
	 * Get data of the given attribute
	 * @param string $name
	 * @return string
	 */
	public function getAttribute($name)
	{
		return $this->attributes[$name];
	}
	/**
	 *
	 * @param unknown $feeCalculation
	 */
	public function setFeeCalculation($feeCalculation)
	{
		$this->feeCalculation = $feeCalculation;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *	 
	 */
	abstract protected function getInput();

	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 *
	 */
	protected function getTitle()
	{
		return $this->title;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 */
	protected function getLabel()
	{
		$label = '';
		$text = $this->title;
		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip hasTip' : '';				
		// Add the opening label tag and main attributes attributes.
		$label .= '<label id="' . $this->name . '-lbl" for="' . $this->name . '" class="' . $class . '"';
		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			JHtml::_('bootstrap.tooltip');
			$document = JFactory::getDocument();
			$document->addStyleDeclaration(".hasTip{display:block !important}");
			$label .= ' title="' . JHtml::tooltipText(trim($text, ':'), $this->description, 0) . '"';
		}
		
		// Add the label text and closing tag.
		if ($this->required)
		{
			$label .= '>' . $text . '<span class="star">&#160;*</span></label>';
		}
		else
		{
			$label .= '>' . $text . '</label>';
		}
		
		return $label;
	}

	/**
	 * Method to get a control group with label and input.
	 *
	 * @return  string  A string containing the html for the control goup
	 *
	 */
	public function getControlGroup()
	{
		if ($this->type == 'Hidden')
		{
			return $this->getInput();
		}
		else
		{
			$controlGroupAttributes = 'id="field_'.$this->name.'" ';							
			$class = $this->feeCalculation ? ' payment-calculation' : '';
			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				return '<div class="control-group'.$class.'" '.$controlGroupAttributes.'>' . '<div class="control-label">' . $this->getLabel() . '</div>' . '<div class="controls">' .
					 $this->getInput() . '</div>' . '</div>';
			}
			else
			{
				return '<div class="control-group'.$class.'" '.$controlGroupAttributes.'>' . '<div class="control-label">' . $this->title .
					 ($this->required ? '<span class="star">&#160;*</span>' : '') . '</div>' . '<div class="controls">' . $this->getInput() . '</div>' .
					 '</div>';
			}	
		}
	}

	/**
	 * Build an HTML attribute string from an array.
	 *
	 * @param  array  $attributes
	 * @return string
	 */
	public function buildAttributes()
	{
		$html = array();
		foreach ((array) $this->attributes as $key => $value)
		{
			if (is_bool($value))
			{
				$html[] = " $key ";
			}
			else
			{
				
				$html[] = $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8', false) . '"';
			}
		}
		
		return count($html) > 0 ? ' ' . implode(' ', $html) : '';
	}
}
