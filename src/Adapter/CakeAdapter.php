<?php

namespace NclCareers\NclAuth\Adapter;

use NclCareers\NclAuth;

class CakeAdapter implements AdapterInterface
{
	public function __construct(\Controller $Controller)
	{
		$this->Controller = $Controller;
	}

	// Use different tables set up with fake student data
	public function switchToFakeStudentTables()
	{
		foreach (array('CdmStudent', 'StudentDetail') as $model_name) {
			$model = \ClassRegistry::init($model_name);
			$model->tablePrefix = 'fake_';
		}
	}

	// Check if authorisation should be disabled for current request
	public function checkIfShouldBeDisabled()
	{
		$disable = false;

		// always disable authorisation for error pages
		// this is to stop infinite redirects when login page broken
		if (get_class($this->Controller) == 'CakeErrorController') {
			$disable = true;
		}

		if (isset($this->Controller->auth_disabled)) {
			if (is_array($this->Controller->auth_disabled)) {
				$disable = in_array($this->Controller->action, $this->Controller->auth_disabled);
			} elseif ($this->Controller->auth_disabled) {
				$disable = true;
			}
		}

		return $disable;
	}

	// Check which users are allowed access
	public function getPermissionsOfCurrentRequest()
	{
		if (isset($this->Controller->auth_allowed) and is_array($this->Controller->auth_allowed)) {
			if (array_key_exists($this->Controller->action, $this->Controller->auth_allowed)) {
				$permissions = $this->Controller->auth_allowed[$this->Controller->action];
			} else {
				$permissions = $this->Controller->auth_allowed;
			}
			return $permissions;
		}
		return false;
	}
}