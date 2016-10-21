<?php

namespace NclCareers\NclAuth\Identity;

class ShibbolethIdentity implements IdentityInterface
{
	public function getName()
	{
		return $this->getShibAttribute('displayname');
	}

	public function getUsername()
	{
		if ($username = $this->getShibAttribute('HTTP_SHIB_EP_PRINCIPALNAME')) {
			return str_replace('@ncl.ac.uk', '', $username);
		}

		return false;
	}

	public function getEmail()
	{
		return $this->getShibAttribute('HTTP_SHIB_EP_EMAILADDRESS');
	}

	public function getStudentNumber()
	{
		return $this->getShibAttribute('HTTP_SHIB_STUDENT_NUMBER');
	}

	public function isStaff()
	{
		return $this->getShibAttribute('HTTP_SHIB_EP_STAFFORSTUDENT') == 'staff';
	}

	public function isStudent()
	{
		return $this->getShibAttribute('HTTP_SHIB_EP_STAFFORSTUDENT') == 'student';
	}

	public function isGraduate()
	{
		return false;
	}

	public function isCareersServiceStaff()
	{
		return $this->isStaff() and $this->isMemberOfCareersService();
	}

	public function isMemberOfCareersService()
	{
		return $this->isMemberOf('Careers_Service');
	}

	public function isMemberOf($group)
	{
		return (strpos($this->getShibAttribute('grouper_groups'), $group) !== false);
	}

	private function getShibAttribute($key)
	{
		return isset($_SERVER[$key]) ? $_SERVER[$key] : false;
	}
}