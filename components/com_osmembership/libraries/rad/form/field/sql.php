<?php
/**
 * Supports an custom SQL select list
 *
 * @package     Joomla.RAD
 * @subpackage  Form
 */
class RADFormFieldSQL extends RADFormFieldList
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'SQL';
			
	/**
	 * The query.
	 *
	 * @var    string	 
	 */
	protected $query;

	public function __construct($row, $value, $fieldSuffix)
	{
		parent::__construct($row, $value, $fieldSuffix);
		$this->query = $row->values;
	}

	/**
	 * Method to get the custom field options.
	 * Use the query attribute to supply a query to generate the list.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		try
		{
			$db = JFactory::getDbo();
			$db->setQuery($this->query);
			$options = $db->loadObjectlist();
		}
		catch (Exception $e)
		{
			$options = array();
		}
		
		return $options;
	}
}
