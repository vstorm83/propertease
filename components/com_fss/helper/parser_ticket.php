<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSSParserTicket
{
	static function header(&$parser, $custom_fields = null)
	{
		// header. find and replace any {order field display} tags

		$text = $parser->template;

		while (strpos($text, "{order ") !== FALSE)
		{
			$start = strpos($text, "{order ");
			$end = strpos($text, "}", $start + 1) + 1;
			$tag = substr($text, $start+7, $end-($start+8));
			list($field, $display) = explode(" ", $tag, 2);
			$replace = "<a href='#' onclick='fssAdminOrder(\"$field\");return false;'>" . $display . "</a>";

			$text = substr($text,0, $start) . $replace . substr($text, $end);
		}

		$parser->template = $text;
	}

	static function forAdmin(&$parser, $ticket = null, $custom_fields = null)
	{
		$parser->Clear();
	
		self::core($parser, $ticket, $custom_fields);
	
		if ($ticket)
		{	
			$title = $ticket->getTitle();

			$parser->SetVar('link', FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id ));
			$parser->SetVar('checkbox', "<input type='checkbox' class='ticket_cb' style='display: none' id='ticket_cb_{$ticket->id}' name='ticket_{$ticket->id}'>");
			
			$parser->SetVar('subject',"<input type='checkbox' class='ticket_cb' style='display: none' id='ticket_cb_{$ticket->id}' name='ticket_{$ticket->id}'><a href='".FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id )."'>" . $title . "</a>");
			$parser->SetVar('subject_text',$title);

			$cotime = FSS_Helper::GetDBTime() - strtotime($ticket->checked_out_time);
			if ($cotime < FSS_Settings::get('support_lock_time') && $ticket->checked_out != JFactory::getUser()->id && $ticket->checked_out > 0)
			{
				$html = "<div>" . $ticket->co_user->name . " (" .  $ticket->co_user->email . ")</div>";
				$parser->SetVar('lock', "<img class='fssTip' title='<b>Ticket locked</b><br />".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/lock.png'>");
			}

			if (!FSS_Settings::get('support_hide_tags'))
			{
				if (isset($ticket->tags) && count($ticket->tags) > 0)
				{
					$html = "";
					foreach($ticket->tags as $tag)
					{
						$html .= "<div>" . $tag . "</div>";
					}
					$parser->SetVar('tags', "<img class='fssTip' title='".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/tag.png'>");
				}
			}

			if (isset($ticket->attach) && count($ticket->attach) > 0)
			{
				$html = "";
				foreach($ticket->attach as $attach)
				{
					$html .= "<div>" . $attach->filename ." (" . FSS_Helper::display_filesize($attach->size) . ")</div>";
				}

				$parser->SetVar('attach', "<img class='fssTip' title='".htmlentities($html,ENT_QUOTES,"utf-8")."' src='". JURI::root( true ) . "/components/com_fss/assets/images/attach.png'>");
			}
			
			$parser->SetVar('icons','');

			if (FSS_Settings::get('support_show_msg_counts'))
			{
				
				$parser->SetVar("msgcount_total", $ticket->msgcount['total']);
				$parser->SetVar("msgcount_user", $ticket->msgcount['0']);
				$parser->SetVar("msgcount_handler", $ticket->msgcount['1']);
				$parser->SetVar("msgcount_private", $ticket->msgcount['2']);
				$parser->SetVar("msgcount_draft", $ticket->msgcount['4']);
				
				$tip = "<strong>".$ticket->msgcount['total'] . " " . JText::_('MESSAGES') . ":</strong><br>";
				if ($ticket->msgcount['0'] > 0)
					$tip .= $ticket->msgcount['0'] . " " . JText::_('USER') . "<br>";
				if ($ticket->msgcount['1'] > 0)
					$tip .= $ticket->msgcount['1'] . " " . JText::_('HANDLER') . "<br>";
				if ($ticket->msgcount['2'] > 0)
					$tip .= $ticket->msgcount['2'] . " " . JText::_('PRIVATE') . "<br>";
				if ($ticket->msgcount['4'] > 0)
					$tip .= $ticket->msgcount['4'] . " " . JText::_('DRAFT') . "<br>";
				$parser->SetVar('msgcnt', "<span class='fssTip label label-default' title='".htmlentities($tip,ENT_QUOTES,"utf-8")."'>".$ticket->msgcount['total']."</span>");
			}
			
			$parser->SetVar("source", $ticket->source);

			$delete = "<a class='pull-right btn btn-default btn-mini' href='" . FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.delete&tickets='.FSS_Input::getCmd('tickets').'&ticketid=' . $ticket->id) . "'>";
			$delete .= JText::_("DELETE") . "</a>";
			$parser->SetVar('deletebutton',$delete);

			$archive = "<a class='pull-right btn btn-default btn-mini' href='" . FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=archive.archive&tickets='.FSS_Input::getCmd('tickets').'&ticketid=' . $ticket->id) . "'>";
			$archive .= JText::_("ARCHIVE") . "</a>";		
			$parser->SetVar('archivebutton',$archive);
			
					
			$handler_highlight = '<span class="fssTip label label-warning pull-right" title="'. JText::_('UNASSIGNED_TICKET') . '">' . $parser->GetVar('handlername') . '</span>';
			
			if ($ticket->admin_id == JFactory::getUser()->id)
				$handler_highlight = '<span class="fssTip label label-success pull-right" title="'. JText::_('MY_TICKET') . '">' . $parser->GetVar('handlername') . '</span>';
			else if ($ticket->admin_id > 0)
				$handler_highlight = '<span class="fssTip label label-info pull-right" title="' . JText::_('OTHER_HANDLERS_TICKET'). '">' . $parser->GetVar('handlername') . '</span>';
			
			$parser->SetVar('handler_tag',$handler_highlight);
			
			$style = "";
			//$trhl = " onmouseover='highlightticket({$ticket->id})' onmouseout='unhighlightticket({$ticket->id})' ";
			$trhl = " "; // no longer highlighting tickets!
			
			$priority = $ticket->getPriority();
			if ($priority->backcolor) $style .= "background-color: {$priority->backcolor};"; 	

			if (FSS_Settings::get('support_entire_row'))
			{
				$style .= "cursor: pointer;";
				$trhl .= " onclick='window.location=\"".FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $ticket->id )."\"' ";
			}
			
			$trhl .= " style='$style' ";
			
			$parser->SetVar('trhl',$trhl);
			
			$parser->SetVar('class',static::$rowclass . " ticket_{$ticket->id}");

			if (static::$rowclass == "odd")
			{
				static::$rowclass = "even";
			} else {
				static::$rowclass = "odd";
			}					
		}
	
		$parser->SetVar("hidehandler",FSS_Settings::get('support_hide_handler') == 1);
		$parser->SetVar("candelete",FSS_Settings::get('support_delete'));
	}
	
	static function core(&$parser, $ticket, $custom_fields = null)
	{
		if ($ticket)
		{		
			$parser->SetVar('ref',$ticket->reference);
			
			$parser->SetVar("status","<span style='color:" . $ticket->color . ";'>" . $ticket->status . "</span>");
			$parser->SetVar("status_text",$ticket->status);
			$parser->SetVar("status_color",$ticket->color);

			if ($ticket->user_id == 0)
			{
				$name = $ticket->unregname . " (" . JText::_("UNREG") . ")";
			} else {
				$name = $ticket->name;
			}
			$parser->SetVar("name",$name);
			
			$parser->SetVar("lastactivity",FSS_Helper::TicketTime($ticket->lastupdate,FSS_DATETIME_SHORT));
			$parser->SetVar("opened",FSS_Helper::TicketTime($ticket->opened,FSS_DATETIME_SHORT));
			$parser->SetVar("closed",FSS_Helper::TicketTime($ticket->closed,FSS_DATETIME_SHORT));
			
			$parser->SetVar("department",$ticket->department);
			$parser->SetVar("category",$ticket->category);
			$parser->SetVar("product",$ticket->product);
			$parser->SetVar("priority","<span style='color:" . $ticket->pricolor . ";'>" . $ticket->priority . "</span>");
			$parser->SetVar("priority_text",$ticket->priority);
			$parser->SetVar("priority_color",$ticket->pricolor);

			if (FSS_Settings::get('time_tracking') != "")
			{
				if ($ticket->timetaken > 0)
				{
					$hours = floor($ticket->timetaken / 60);
					$mins = sprintf("%02d",$ticket->timetaken % 60);
					$parser->SetVar("time_taken","<i class='icon-clock'></i>".JText::sprintf("TIME_TAKEN_DISP", $hours, $mins));
				} else {
					$parser->SetVar("time_taken","<i class='icon-clock'></i>".JText::sprintf("TIME_TAKEN_DISP", "0", "00"));
				}
			} else {
				$parser->SetVar("time_taken",'');
			}
			
			$group_names = array();
			if (isset($ticket->groups))
				foreach ($ticket->groups as $group)
					$group_names[] = $group->groupname;
			$parser->SetVar('groups', implode(", ", $group_names));

			if (empty($ticket->custom) && !empty($ticket->fields))
				$ticket->custom = $ticket->fields;
 
			if (isset($ticket->customfields))
			{
				foreach($ticket->customfields as $field)
				{
					if ($field['type'] != "plugin") continue;
					$aparams = FSSCF::GetValues($field);
					$plugin = FSSCF::get_plugin($aparams['plugin']);
					$id = $field['id'];

					$value = "";
					if (isset($ticket->custom) && array_key_exists($field['id'], $ticket->custom))
						$value = $ticket->custom[$field['id']];

					if (is_array($value))
						$value = $value['value'];

					$text = $plugin->Display($value, $aparams['plugindata'], array('ticketid' => $ticket->id, 'userid' => $ticket->user_id, 'ticket' => $ticket, 'inlist' => 1), $field['id']);
					$parser->SetVar("custom".$id,$text);	
					$parser->SetVar("custom_".$id,$text);	
					$cf_alias = $field['alias'];
					$parser->SetVar("custom_".$cf_alias,$text);	
					$parser->SetVar("custom_".$id."_name",$field['description']);	
				}
			}

			if (isset($ticket->custom))
			{
				$allcustom = array();
				if (count($ticket->custom) > 0)
				{
					foreach	($ticket->custom as $id => $value)
					{
						if (is_array($value))
							$value = $value['value'];
						
						if (isset($ticket->customfields) && array_key_exists($id,$ticket->customfields))
						{
							$field = $ticket->customfields[$id];

							if ($field['inlist'] < 1) continue;
														
							if ($field['type'] == "plugin")
							{
								$aparams = FSSCF::GetValues($field);
								if (array_key_exists("plugin", $aparams) && array_key_exists("plugindata", $aparams))
								{	
									$plugin = FSSCF::get_plugin($aparams['plugin']);
									$value = $plugin->Display($value, $aparams['plugindata'], array('ticketid' => $ticket->id, 'userid' => $ticket->user_id, 'ticket' => $ticket, 'inlist' => 1), $field['id']);
								}
							}
							
							$prefix = "<span class='cust_field_label cust_field_label_$cf_alias cust_field_label_$id'>" . $ticket->customfields[$id]['description'] . ":</span> ";
							if ($ticket->customfields[$id]['type'] == "checkbox")
							{
								if ($value == "on")
									$text = JText::_("Yes");
								else
									$text = JText::_("No");
							} else {
								$text = $value;
							}

							
							$parser->SetVar("custom".$id,$text);	
							$parser->SetVar("custom_".$id,$text);	
							
							$cf_alias = $ticket->customfields[$id]['alias'];
							$parser->SetVar("custom_".$cf_alias,$text);	
							
							$parser->SetVar("custom_".$id."_name",$ticket->customfields[$id]['description']);	
							
							$allcustom[] = $prefix."<span class='cust_field_value cust_field_value_$cf_alias cust_field_value_$id'>".$text."</span>";
						}
					}
				}
				$parser->SetVar("custom",implode(", ",$allcustom));
			}

			if ($ticket->assigned == '')
			{
				$parser->SetVar('handlername',JText::_("UNASSIGNED"));
			} else {
				$parser->SetVar('handlername',$ticket->assigned);
			}
			$parser->SetVar('username',$ticket->username);
			$parser->SetVar('email',$ticket->useremail);
			$parser->SetVar('handlerusername',$ticket->handlerusername);
			$parser->SetVar('handleremail',$ticket->handleremail);
						
			// product image
			$prod = self::getProduct($ticket);
			if ($prod) $parser->SetVar('product_img', JURI::root( true ) . "/images/fss/products/" . $prod->image);
			$dept = self::getDepartment($ticket);
			if ($dept)$parser->SetVar('department_img', JURI::root( true ) . "/images/fss/departments/" . $dept->image);

			if (strpos($parser->template, "{last_poster}") !== false || 
				strpos($parser->template, "{last_poster_username}") !== false)
			{
				$parser->SetVar('last_poster','');
				$parser->SetVar('last_poster_username','');

				$db = JFactory::getDBO();
				$qry = "SELECT user_id, posted FROM #__fss_ticket_messages WHERE ticket_ticket_id = " . $db->escape($ticket->id) . " AND admin IN (0, 1) ORDER BY posted DESC LIMIT 1";
				$db->setQuery($qry);
				$rows = $db->loadObjectList();
				
				if ($rows)
				{
					$row = reset($rows);
				
					if ($row)
					{
						$user_id = $row->user_id;
						$user = JFactory::getUser($user_id);
						$parser->SetVar('last_poster',$user->name);
						$parser->SetVar('last_poster_username',$user->username);
					}
				}
			}

		}
		
	}

	static $rowclass = "odd";

	static function forUser(&$parser, $ticket, $custom_fields = null)
	{
		$parser->Clear();
		
		if ($ticket)
		{
			// overwrite status of ticket when combined
			$statuss = SupportHelper::getStatuss(false);
			FSS_Translate_Helper::Tr($statuss);
			
			$cur_status = $statuss[$ticket->ticket_status_id];
			if ($cur_status->combine_with > 0)
			{
				$new_status = $statuss[$cur_status->combine_with];
				$ticket->color = $new_status->color;
				$ticket->status = $new_status->title;
				if ($new_status->userdisp)
					$ticket->status = $new_status->userdisp;
				$ticket->ticket_status_id = $new_status->id;
			} else {
				if ($cur_status->userdisp)
					$ticket->status = $cur_status->userdisp;
			}
		}
		
		self::core($parser, $ticket, $custom_fields);
		
		if ($ticket)
		{
			// TODO TODO TODO : 
			//$title = $ticket->getTitle();
			$title = self::parseTitle($ticket->title, $ticket->id);
			//$title = $ticket->title;
			
			$parser->SetVar('link', FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $ticket->id ));
			$parser->SetVar('subject',"<a href='".FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $ticket->id )."'>" . $title . "</a>");
			$parser->SetVar('subject_text',$title);
			
			$style = "";
			//$trhl = " onmouseover='highlightticket({$ticket->id})' onmouseout='unhighlightticket({$ticket->id})' ";
			$trhl = " "; // no longer highlighting tickets!

			if (FSS_Settings::get('support_entire_row'))
			{
				$style .= "cursor: pointer;";
				$trhl .= " onclick='window.location=\"".FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $ticket->id )."\"' ";
			}
			
			$trhl .= " style='$style' ";
			
			$parser->SetVar('trhl',$trhl);
			
			$parser->SetVar('class',static::$rowclass." ticket_{$ticket->id}");

			if (static::$rowclass == "odd")
			{
				static::$rowclass = "even";
			} else {
				static::$rowclass = "odd";
			}
		}
		
		$parser->SetVar("hidehandler",FSS_Settings::get('support_hide_handler') > 0);		
		$parser->SetVar("multiuser",$parser->multiuser);
	}
	
	static function getProduct($ticket)
	{
		$prods = SupportHelper::getProducts(false);
		if (array_key_exists($ticket->prod_id, $prods))
			return $prods[$ticket->prod_id];
	
		return null;
	}	
	
	static function getDepartment($ticket)
	{
		$depts = SupportHelper::getDepartments(false);
		if (array_key_exists($ticket->ticket_dept_id, $depts))
			return $depts[$ticket->ticket_dept_id];
	
		return null;
	}	

	static function parseTitle($title, $ticketid)
	{
		// This needs updating to use loaded messages if we have them
		if (trim($title) != "")
		{
			return $title;
		} else {
			// no title for the ticket, so load the oldest message, and display the first part of that
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_messages WHERE ticket_ticket_id = " . $db->escape($ticketid) . " ORDER BY posted ASC LIMIT 1";
			$db->setQuery($qry);
			$message = $db->loadObject();
			$msg = $message->body;
			$msg = FSS_Helper::ParseBBCode($msg);
			$msg = strip_tags($msg);
			if (trim($msg) != "")
			{
				if (strlen($msg) > 50) return substr($msg, 0, 50) . "...";	
				
				return $msg;
			} else {
				return JText::_('NO_SUBJECT');
			}
		}	
	}	
}