<?php  
$mainURL = $this->baseurl.'/templates/'.$this->template.'/';

$user = JFactory::getUser();
$status = $user->id;


$app = JFactory::getApplication();
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
	if($status<1){
		include_once("home_public.php");
	}else{
		include_once("home_logged.php");
	}
	
}
else
{
	include_once("home_logged.php");
} 
 ?>  
 