<?php

namespace NclCareers\NclAuth\Identity;

class DevelopmentIdentity implements IdentityInterface
{
	public function getName()
	{
		return 'Local Developer';
	}

	public function getUsername()
	{
		return 'ngt11';
	}

	public function getEmail()
	{
		return 'graeme.tait@ncl.ac.uk';
	}

	public function getStudentNumber()
	{
		return false;
	}

	public function isStaff()
	{
		return true;
	}

	public function isStudent()
	{
		return false;
	}

	public function isGraduate()
	{
		return false;
	}

	public function isCareersServiceStaff()
	{
		return true;
	}
}