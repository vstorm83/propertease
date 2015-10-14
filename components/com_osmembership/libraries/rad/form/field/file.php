<?php

class RADFormFieldFile extends RADFormField
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 *	 
	 */
	protected  $type = 'File';
	
	public function __construct($row, $value = null, $fieldSuffix = null)
	{
		parent::__construct($row, $value, $fieldSuffix);				
		if ($row->size)
		{
			$this->attributes['size'] = $row->size;
		}
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *	 
	 */
	protected function getInput()
	{
		$attributes = $this->buildAttributes();
	
		/*if ($this->value && file_exists(JPATH_ROOT.'/media/com_osmembership/upload/'.$this->value))*/
		if ($this->value)
		{
			if($this->name=="osm_avatar"){
				$str='<div class="avatar" onclick="jQuery(\'#'.$this->name.'\').trigger(\'click\');" title="Click to Change" style="max-height:100px;overflow:hidden;"><img style="max-width:100px;" src="'.JURI::base().'media/com_osmembership/upload/'.OSMembershipHelper::getOriginalFilename($this->value).'"/></div>';
				$str.='<div><input style="display:none" onchange="jQuery(\'#osm_form\').submit()" type="file" name="' . $this->name . '" id="' . $this->name . '" value=""' . $attributes. $this->extraAttributes. ' /></div>';
				return $str;
			}else{
				return '<input type="file" name="' . $this->name . '" id="' . $this->name . '" value=""' . $attributes. $this->extraAttributes. ' />. '.JText::_('OSM_CURRENT_FILE').' <strong>'.OSMembershipHelper::getOriginalFilename($this->value).'</strong> <a href="index.php?option=com_osmembership&task=download_file&file_name='.$this->value.'">'.JText::_('OSM_DOWNLOAD').'</a>';
			}
		}
		else 
		{
			return '<input type="file" name="' . $this->name . '" id="' . $this->name . '" value=""' . $attributes. $this->extraAttributes. ' />';
		}		
	}
}