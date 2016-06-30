<?php

namespace NclCareers\NclAuth\Authorisation;

use \NclCareers\NclAuth\Identity\IdentityInterface;

class Permissions
{
	protected $allowed = array();

	public function isUserAllowed(IdentityInterface $identity)
	{
		if ( ! empty($this->allowed['users'][$identity->getUsername()])) {
			return true;
		}

		if (( ! empty($this->allowed['staff'])) and $identity->isStaff()) {
			return true;
		}

		if (( ! empty($this->allowed['students'])) and $identity->isStudent()) {
			return true;
		}

		if (( ! empty($this->allowed['graduates'])) and $identity->isGraduate()) {
			return true;
		}

		return false;
	}

	public function allowStaff()
	{
		$this->allowed['staff'] = true;
		return $this;
	}

	public function allowStudents()
	{
		$this->allowed['students'] = true;
		return $this;
	}

	public function allowGraduates()
	{
		$this->allowed['graduates'] = true;
		return $this;
	}

	public function allowUsers($users)
	{
		foreach ($users as $user) {
			$this->allowed['users'][$user] = true;
		}

		return $this;
	}

	public function allowAll()
	{
		$this->allowStaff()->allowStudents()->allowGraduates();
		return $this;
	}

	public function denyAll()
	{
		$this->allowed = array();
		return $this;
	}

	public function setAllowed($allowed)
	{
		if ( ! empty($allowed['all'])) {
			$this->allowAll();
			return;
		}

		if ( ! empty($allowed['staff'])) {
			$this->allowStaff();
		}

		if ( ! empty($allowed['students'])) {
			$this->allowStudents();
		}

		if ( ! empty($allowed['graduates'])) {
			$this->allowGraduates();
		}

		if ( ! empty($allowed['users'])) {
			$this->allowUsers($allowed['users']);
		}
	}
}