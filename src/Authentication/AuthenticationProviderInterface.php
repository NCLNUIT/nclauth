<?php

namespace NclCareers\NclAuth\Authentication;

interface AuthenticationProviderInterface
{
	public function isAuthenticated();
	public function isAutoLoginSupported();
}