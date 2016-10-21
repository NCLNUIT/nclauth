<?php

use NclCareers\NclAuth\Identity\ShibbolethIdentity;

class ShibbolethIdentityTest extends PHPUnit_Framework_TestCase
{
	private $identity;

	public function setUp()
	{
		$this->identity = new ShibbolethIdentity;
	}

	private function setShibbolethData($key, $value)
	{
		$_SERVER[$key] = $value;
	}

	public function testIdentityInterface()
	{
		$this->assertInstanceOf('NclCareers\NclAuth\Identity\IdentityInterface', $this->identity);
	}

	public function testGetName()
	{
		$name = 'Fake Name';

		$this->setShibbolethData('displayname', $name);

		$this->assertEquals($name, $this->identity->getName());
	}

	public function testGetUsername()
	{
		$username = 'nex43';

		$this->setShibbolethData('HTTP_SHIB_EP_PRINCIPALNAME', $username . '@ncl.ac.uk');

		$this->assertEquals($username, $this->identity->getUsername());
	}

	public function testGetEmail()
	{
		$email = 'example@ncl.ac.uk';

		$this->setShibbolethData('HTTP_SHIB_EP_EMAILADDRESS', $email);

		$this->assertEquals($email, $this->identity->getEmail());
	}

	public function testStaff()
	{
		$this->setShibbolethData('HTTP_SHIB_EP_STAFFORSTUDENT', 'staff');

		$this->assertTrue($this->identity->isStaff());
		$this->assertFalse($this->identity->isStudent());
		$this->assertFalse($this->identity->isGraduate());
		$this->assertFalse($this->identity->isCareersServiceStaff());
	}

	public function testStudent()
	{
		$this->setShibbolethData('HTTP_SHIB_EP_STAFFORSTUDENT', 'student');

		$this->assertTrue($this->identity->isStudent());
		$this->assertFalse($this->identity->isStaff());
		$this->assertFalse($this->identity->isGraduate());
		$this->assertFalse($this->identity->isCareersServiceStaff());
	}

	public function testIsCareersServiceStaff()
	{
		$this->setShibbolethData('HTTP_SHIB_EP_STAFFORSTUDENT', 'staff');

		$this->setShibbolethData('grouper_groups', 'NUIT');

		$this->assertFalse($this->identity->isCareersServiceStaff());

		$this->setShibbolethData('grouper_groups', 'Careers_Service');

		$this->assertTrue($this->identity->isCareersServiceStaff());
	}

	public function testGroupMembership()
	{
		$this->setShibbolethData('grouper_groups', '');

		$this->assertFalse($this->identity->isMemberOf('Test_Group'));

		$this->setShibbolethData('grouper_groups', 'Test_Group');

		$this->assertTrue($this->identity->isMemberOf('Test_Group'));
	}
}