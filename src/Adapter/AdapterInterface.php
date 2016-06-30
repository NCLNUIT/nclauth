<?php

namespace NclCareers\NclAuth\Adapter;

interface AdapterInterface
{
	public function switchToFakeStudentTables();
	public function getPermissionsOfCurrentRequest();
}