<?php

namespace NclCareers\NclAuth\Identity;

class FakeStudentIdentity implements IdentityInterface
{
	public function getName()
	{
		return 'Fake Student';
	}

	public function getUsername()
	{
		return 'a9123456';
	}

	public function getEmail()
	{
		return 'graeme.tait@ncl.ac.uk';
	}

	public function isStaff()
	{
		return false;
	}

	public function isStudent()
	{
		return true;
	}

	public function isGraduate()
	{
		return false;
	}

	public function isCareersServiceStaff()
	{
		return false;
	}
}