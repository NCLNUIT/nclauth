<?php

namespace NclCareers\NclAuth;

use NclCareers\NclAuth\Authentication\AuthenticationProviderInterface;
use NclCareers\NclAuth\Authorisation\Permissions;
use NclCareers\NclAuth\Adapter\AdapterInterface;
use NclCareers\NclAuth\Identity;

class AuthHandler
{
	protected $disabled = false;

	public function __construct(AuthenticationProviderInterface $authentication, Permissions $authorisation, AdapterInterface $adapter, Identity\IdentityInterface $identity)
	{
		$this->authentication = $authentication;
		$this->authorisation = $authorisation;
		$this->adapter = $adapter;
		$this->identity = $identity;

		if ($this->adapter->checkIfShouldBeDisabled()) {
			$this->disable();
		}
	}

	public function authenticate()
	{
		// $this->rememberRequestedURL();

		if ($this->authentication->isAutoLoginSupported()) {
			$this->authentication->attemptAutoLogin();
		}

		$this->checkAuthentication();

		// $this->runLoginCallback();
	}

	// Careers staff should be able to access student pages as a fake student
	public function overrideAccessForCareersStaff()
	{
		if ($this->careersStaffButNotAllowedAccess()) {
			$this->setupFakeStudentIdentity();
		}
	}

	// Check which users are allowed access
	public function setPermissionsOfCurrentAction()
	{
		if ($permissions = $this->adapter->getPermissionsOfCurrentRequest()) {
			$this->setPermissions($permissions);
		}
	}

	public function setupFakeStudentIdentity()
	{
		$this->identity = new Identity\FakeStudentIdentity;
		$this->adapter->switchToFakeStudentTables();
	}

	protected function careersStaffButNotAllowedAccess()
	{
		return ( ! $this->authorisation->isUserAllowed($this->identity))
			and $this->identity->isCareersServiceStaff();
	}

	public function setPermissions($permissions)
	{
		$this->authorisation->setAllowed($permissions);
	}

	// Keep them out if they shouldn't be here
	public function checkAuthentication()
	{
		if ($this->isDisabled()) {
			return true;
		}

		return $this->authentication->isAuthenticated();
	}

	public function checkAuthorisation()
	{
		if ($this->isDisabled()) {
			return true;
		}

		return $this->authorisation->isUserAllowed($this->identity);
	}

	public function isDisabled()
	{
		return $this->disabled;
	}

	public function disable()
	{
		$this->disabled = true;
	}

	public function enable()
	{
		$this->disabled = false;
	}

	public function getLoginPageURL($requested_url)
	{
		return $this->authentication->getLoginPageURL($requested_url);
	}

	public function identity()
	{
		return $this->identity;
	}
}