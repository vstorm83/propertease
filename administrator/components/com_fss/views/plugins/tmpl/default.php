<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$k = 0;
?>

<div id="editcell">
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th>
                Type
            </th>
			<th>
                Name
            </th>
			<th>
                Title
            </th>
            <th>
                Description
            </th>
			<th width="1%" nowrap="nowrap">
				Enabled
			</th>
		</tr>
    </thead>
	
    <?php foreach ($this->plugins as $plugin): ?>
        <tr class="<?php echo "row$k"; $k = 1 - $k; ?>">
			<td>
			    <?php 
					switch ($plugin->type)
			    {
			    	case 'tickets':
			    		echo "Ticket Action Plugin";
			    		break;
			    	case 'gui':
			    		echo "GUI Plugin";
			    		break;
			    	case 'ticketprint':
			    		echo "Ticket Print Layout";
			    		break;
			    	default:
			    		echo $plugin->type;
			    		break;
			    }
				 ?>
			</td>
			<td>
			    <?php echo $plugin->name; ?>
			</td>
			<td>
			    <?php echo $plugin->title; ?>
			</td>
			<td>
   				<?php echo $plugin->description; ?>
			</td>
			<!--<td nowrap="nowrap">
				<p><a href="#" class="btn">Config</a></p>
				<p><a href="#" class="btn">Another Button</a></p>
			</td>-->
			<td align="center">
				<?php if ($plugin->enabled): ?>
					<a href='<?php echo JRoute::_('index.php?option=com_fss&view=plugins&task=disable&type=' . $plugin->type . "&name=" . $plugin->name); ?>' class="label label-success fssTip" title="Click to disable">Enabled</a>
				<?php else: ?>
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=plugins&task=enable&type=' . $plugin->type . "&name=" . $plugin->name); ?>" class="label label-important fssTip" title="Click to Enable">Disabled</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
    </table>
</div>
