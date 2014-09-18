<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class mod_easyfolderlistingInstallerScript
{
	/**
	 * Called before any type of action. Method to run before an install/update/uninstall method.
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function preflight($route, $adapter) 
	{
	}
 
	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install($adapter) 
	{
	}
 
	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function update($adapter) 
	{
	}
 
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	function uninstall($adapter) 
	{
	}
 
	/**
	 * Called after any type of action. Method to run after an install/update/uninstall method.
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function postflight($route, $adapter) 
	{
		if (stripos($route, 'install') !== false || stripos($route, 'update') !== false)
		{
			return $this->fixManifest($adapter);
		}
	}
	
	private function fixManifest($adapter)
	{
		$filesource = $adapter->get('parent')->getPath('source').'/_manifest.xml';
		$filedest = $adapter->get('parent')->getPath('extension_root').'/mod_easyfolderlisting.xml';
		
		if (!(JFile::copy($filesource, $filedest)))
		{
			JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_FAIL_COPY_FILE', $filesource, $filedest), JLog::WARNING, 'jerror');
			
			if (class_exists('JError'))
			{
				JError::raiseWarning(1, 'JInstaller::install: '.JText::sprintf('Failed to copy file to', $filesource, $filedest));
			}
			else
			{
				throw new Exception('JInstaller::install: '.JText::sprintf('Failed to copy file to', $filesource, $filedest));
			}
			return false;
		}
		
		return true;
	}
}
