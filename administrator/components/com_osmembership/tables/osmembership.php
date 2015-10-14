<?php
/**
 * @version        1.6.7
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Subscription Plan Table Class
 *
 */
class PlanOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_plans', 'id', $db);
	}
}

/**
 * Coupon Table class
 *
 */
class CouponOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_coupons', 'id', $db);
	}
}

/**
 * Custom Field Table Class
 *
 */
class FieldOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_fields', 'id', $db);
	}
}

/**
 * Subscriber Table Class
 *
 */
class SubscriberOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_subscribers', 'id', $db);
	}
}

/**
 * Upgrade Rule table class
 *
 */
class RuleOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_upgraderules', 'id', $db);
	}
}

/**
 * Config Table Class
 *
 */
class ConfigOsMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_configs', 'id', $db);
	}
}

/**
 * Custom Field Value Table Class
 *
 */
class FieldValueOsMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_field_value', 'id', $db);
	}
}

/**
 * Payment Plugin Table Class
 *
 */
class PluginOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_plugins', 'id', $db);
	}
}

/**
 * Category Table Class
 *
 */
class CategoryOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_categories', 'id', $db);
	}
}

/**
 * Email Messages Table Class
 *
 */
class MessageOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_messages', 'id', $db);
	}
}

/**
 * Countries table class
 *
 */
class CountryOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_countries', 'id', $db);
	}
}

/**
 * State table class
 *
 */
class StateOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_states', 'id', $db);
	}
}

/**
 * Tax table class
 *
 */
class TaxOSMembership extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__osmembership_taxes', 'id', $db);
	}
}