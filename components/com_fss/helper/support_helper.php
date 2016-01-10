<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Generic support helper class
 * 
 * Does things like load in list of status etc
 * 
 * REPLACES the old TicketHelper (FSS_Ticket_Helper)
**/

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php');

class SupportHelper
{
	static $data_lists = array();
	static function getDataList($sort, $key, $query, $translate = false)
	{
		if (!array_key_exists($key, self::$data_lists))
		{
			$db = JFactory::getDBO();
			//echo $query."<br>";
			$db->setQuery($query);
			self::$data_lists[$key]['s'] = $db->loadObjectList();
			self::$data_lists[$key]['i'] = array();
			
			if ($translate)
				FSS_Translate_Helper::Tr(self::$data_lists[$key]['s']);
			
			foreach (self::$data_lists[$key]['s'] as $item)
				self::$data_lists[$key]['i'][$item->id] = $item;
		}
		
		if ($sort)
			self::$data_lists[$key]['s'];		
		
		return self::$data_lists[$key]['i'];		
	}
	
	static function getPriorities($sort = true, $order = "id ASC")
	{
		return self::getDataList($sort, "priorities-" . $order, "SELECT * FROM #__fss_ticket_pri ORDER BY " . $order, true);
	}
	
	static function getStatuss($sort = true, $order = "ordering")
	{
		return self::getDataList($sort, "statuss-" . $order, "SELECT * FROM #__fss_ticket_status ORDER BY " . $order, true);
	}

	static function getCategories($sort = true, $order = "ordering, section, title")
	{
		return self::getDataList($sort, "categories-" . $order, "SELECT * FROM #__fss_ticket_cat WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function getProducts($sort = true, $order = "ordering")
	{
		return self::getDataList($sort, "products-" . $order, "SELECT * FROM #__fss_prod WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function getDepartments($sort = true, $order = "ordering, title")
	{
		return self::getDataList($sort, "departments-" . $order, "SELECT * FROM #__fss_ticket_dept WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function getTicketGroups($sort = true, $order = "groupname")
	{
		return self::getDataList($sort, "ticketgroups-" . $order, "SELECT * FROM #__fss_ticket_group ORDER BY " . $order);
	}
	
	static function getTags($sort = true, $order = "cnt DESC", $limit = 10)
	{
		return self::getDataList($sort, "tags-" . $order . "-" . $limit, "SELECT count(*) as cnt, tag, tag as id FROM #__fss_ticket_tags GROUP BY tag ORDER BY {$order} LIMIT {$limit}");
	}

	static function getAllowedCategories($ticket, $sort = true, $order = "ordering, section, title")
	{
		// TODO: Make this only display categories available for the current ticket
		return self::getDataList($sort, "allowed-categories-" . $order . "-" . $ticket->id, "SELECT * FROM #__fss_ticket_cat WHERE published = 1 ORDER BY " . $order, true);
	}
	
	static function parseRedirectType($status, $type)
	{
		$ticketid = FSS_Input::getInt('ticketid');
		
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid))
			$ticketid = 0;
		
		if ($type == "" && $ticketid > 0)
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false);
		
		$bits = explode("_", $type);
		
		// 2nd parameter of type is the status, so if current, use the status that has been passed in
		if ($bits[1] == "current")
			$bits[1] = $status;
		
		if (count($bits) != 2)
		{
			if ($ticketid > 0)
			{
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'), false);
			} else {
				return FSSRoute::_('index.php?option=com_fss&view=admin_support', false);
			}
		}
		
		if ($bits[0] == "list")
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=' . $bits[1], false);
		
		if ($bits[0] == "new" || $bits[0] == "old")
		{
			// get current tickets for the current handler of a specific type
			JRequest::setVar('tickets', $bits[1]);

			$tickets = new SupportTickets();
			$tickets->limitstart = 0;
			$tickets->limit = 500;
			$tickets->loadTicketsByStatus($bits[1]);
	
			$oldest_time = time();
			$oldest_id = -1;
			
			$newest_time = 0;
			$newset_id = -1;
			
			foreach ($tickets->tickets as $ticket)
			{
				$updated = strtotime($ticket->lastupdate);
				if ($updated > $newest_time)
				{
					$newest_time = $updated;
					$newset_id = $ticket->id;
				}
				if ($updated < $oldest_time)
				{
					$oldest_time = $updated;
					$oldest_id = $ticket->id;
				}
			}
			
			if ($bits[0] == "new" && $newset_id > 0)
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $newset_id, false);
			
			if ($bits[0] == "old" && $oldest_id > 0)
				return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . $oldest_id, false);
			
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&tickets=' . $bits[1], false);
		}
		
		if ($ticketid > 0)
			return FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&ticketid=' . FSS_Input::getInt('ticketid'));
		
		return FSSRoute::_('index.php?option=com_fss&view=admin_support', false);
	}	
	
	static function getUserTicketCounts($userid,$email)
	{
		$db = JFactory::getDBO();
		
		if ($userid)
		{
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' GROUP BY ticket_status_id";
		} else {
			$qry = "SELECT count(*) as cnt, ticket_status_id FROM #__fss_ticket_ticket WHERE email = '".FSSJ3Helper::getEscaped($db, $email)."' GROUP BY ticket_status_id";
		}
		
		$db->setQuery($qry);
		$rows = $db->loadObjectList();

		$out = array();
		FSS_Ticket_Helper::GetStatusList();
		$out['total'] = 0;
			
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$out[$row->ticket_status_id] = $row->cnt;
				$out['total'] += $row->cnt;
			}
		}
	
		return $out;	
	}
	
	static function TimeTaken($message) {
		if ($message->time != 0): ?>
			<span class="<?php if ($message->time < 0): ?> text-error <?php endif; ?>">
				<i class="icon-clock"></i> 
				<?php if ($message->time < 0): ?>-<?php endif; ?>
				<?php 	
					echo "<span style='display:none' class='ticket_time_dur'>" . $message->time . "</span>";
					
					$time = abs($message->time);
					$hours = floor($time / 60);
					$mins = sprintf("%02d",$time % 60);

					if ($message->timestart > 0 && $message->timeend > 0 && $message->timestart < 86400)
					{
						echo "<span style='display:none' class='ticket_time_start'>" . date("H:i", $message->timestart) . "</span>";
						echo "<span style='display:none' class='ticket_time_end'>" . date("H:i", $message->timeend) . "</span>";
						echo "<i class='ticket_time_time'>" . date("H:i", $message->timestart) . " - " . date("H:i", $message->timeend) . "</i> (<b>";
					} else if ($message->timestart > 0 && $message->timeend > 0)
					{
						echo "<span style='display:none' class='ticket_time_start'>" . $message->timestart . "</span>";
						echo "<span style='display:none' class='ticket_time_end'>" . $message->timeend . "</span>";
						echo "<i class='ticket_time_date'>" . FSS_Helper::Date($message->timestart, FSS_DATETIME_SHORT) . " - " . FSS_Helper::Date($message->timeend, FSS_DATETIME_SHORT) . "</i> (<b>";
					} else {
						echo "<span style='display:none' class='ticket_time_hours'>" .$hours . "</span>";
						echo "<span style='display:none' class='ticket_time_mins'>" . $mins . "</span>";
					}
					echo "<span class='ticket_time_duration'>".JText::sprintf("TIME_TAKEN_DISP", $hours, $mins)."</span>";
					if ($message->timestart > 0 && $message->timeend > 0) echo "</b>)";
				?> 
			</span>
			&nbsp;
		<?php endif;
	}
	
	static function attachThumbnail($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
			if (!$image)
				exit;
					
			$image_file = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS."support".DS.$attach->diskfile;
			$thumb_file = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS."thumbnail".DS.$attach->diskfile.".thumb";
					
			$thumb_path = pathinfo($thumb_file, PATHINFO_DIRNAME);

			if (!file_exists($thumb_path))
				mkdir($thumb_path, 0755, true);

			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::Thumbnail($image_file, $thumb_file);
		}
		
		exit;
	}
	
	static function attachView($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
			if (!$image)
				exit;
					
			$image_file = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS."support".DS.$attach->diskfile;
					
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::OutputImage($image_file, pathinfo($attach->filename, PATHINFO_EXTENSION));
		}
		
		exit;
	}
	
	static function attachDownload($ticketid, $fileid, $for_user = false)
	{
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid, $for_user))
		{
			$attach = $ticket->getAttach($fileid);
			
			if (substr($attach->diskfile, 0, 7) == "http://" || substr($attach->diskfile, 0, 8) == "https://")
			{
				header('Location: ' . $attach->diskfile);
				exit;
			}
			
			$file = JPATH_SITE.DS.FSS_Settings::get('attach_location').DS."support".DS.$attach->diskfile;
					
			$display_filename = FSS_Helper::basename($attach->filename);
			
			if (!$for_user)
			{
				$user = JFactory::GetUser($attach->user_id);      
				$type = FSS_Settings::get('support_filename');
				switch ($type)
				{
					case 1:
						$display_filename = $user->username . "_" . $display_filename;
						break;
					case 2:
						$display_filename = $user->username . "_" . date("Y-m-d") . "_" . $display_filename;
						break;	
					case 3:
						$display_filename = date("Y-m-d") . "_" . $user->username . "_" . $display_filename;
						break;	
					case 4:
						$display_filename = date("Y-m-d") . "_" . $display_filename;
						break;	
				}
			}
		
			require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
			FSS_File_Helper::DownloadFile($file, $display_filename);
		}
		
		exit;
	}
}