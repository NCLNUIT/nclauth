<?php

namespace NclCareers\NclAuth\Identity;

interface IdentityInterface
{
	public function getName();

	public function getUsername();

	public function getEmail();

	public function getStudentNumber();

	public function isStaff();

	public function isStudent();

	public function isGraduate();

	public function isCareersServiceStaff();
}