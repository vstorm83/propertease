<?php

class SupportActionsAcySMS extends SupportActionsPlugin
{
	var $title = "ACY SMS Plugin";
	var $description = "Call the Freestyle Support ACY SMS plugin. Need to ACY SMS Plugin to also be installed.";

	function User_Open($ticket, $params)
	{
		$dispatcher->trigger('ticketSubmitted');
	}	
}