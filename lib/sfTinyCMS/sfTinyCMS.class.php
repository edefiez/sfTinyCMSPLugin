<?php
/**
 * Utility methods
 */
class sfTinyCMS
{
	/**
	 * Checks if the current user has rights to edit content
	 *
	 * @return boolean false if user doesn't have rights, true otherwise
	 */
	public static function checkAccess()
	{
		$user = sfContext::getInstance()->getUser();

		$securityConf = sfConfig::get('app_tinycms_security');

		// If no configuration is found regarding security, do not allow any action
		if (empty($securityConf) || !isset($securityConf['edit']))
		{
			return false;
		}

		$editSecurity = $securityConf['edit'];

		// Check authentication
		if (!array_key_exists('authenticated', $editSecurity)) {
			return false;
		}

		if ($editSecurity['authenticated'] != false && !$user->isAuthenticated()) {
			return false;
		}

		// Check credentials
		if (!array_key_exists('credentials', $editSecurity)) {
			return false;
		}

		if ($editSecurity['credentials'] != false && !$user->hasCredential($editSecurity['credentials'])) {
			return false;
		}

		return true;
	}
}