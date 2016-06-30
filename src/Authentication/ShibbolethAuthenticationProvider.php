<?php

namespace NclCareers\NclAuth\Authentication;

use \NclCareers\NclAuth\Identity\IdentityInterface;

class ShibbolethAuthenticationProvider implements AuthenticationProviderInterface
{
	protected
		// guest PCs don't support auto login
		$no_auto_ips = array(
			'10.67.144.10',
			'10.67.144.22',
			'10.67.144.37',
			'10.67.144.38',
			'10.67.144.47',
			'10.67.144.55',
			'10.67.144.56',
			'10.67.144.59',
			'10.67.144.60',
			'10.67.144.62',
			'10.67.144.63',
			'10.67.145.81',
			'10.67.144.124',
			'10.67.144.128',
			'10.67.144.136',
			'10.67.144.137',
			'10.67.144.140',
			'10.67.144.141',
			'10.67.146.152',
			'10.67.144.153',
			'10.67.145.166',
			'10.67.146.178',
			'10.67.146.180',
			'10.67.146.181'
		),
		$no_auto_ip_ranges = array(
			'10.12.',      // magpie
			'10.13.',      // magpie
			'10.6.',       // ivpn
			'128.240.246.' // evpn
		);

	public function __construct(IdentityInterface $identity)
	{
		$this->identity = $identity;
	}

	public function isAuthenticated()
	{
		return (bool) $this->identity->getEmail();
	}

	public function isAutoLoginSupported()
	{
		if (in_array($_SERVER['REMOTE_ADDR'], $this->no_auto_ips))
			return false;

		foreach ($this->no_auto_ip_ranges as $ip_range)
			if (stripos($_SERVER["REMOTE_ADDR"], $ip_range) === 0)
				return false;

		return true;
	}

	public function attemptAutoLogin()
	{
		if ( ! $this->isAutoLoginSupported())
			return;

		// check-browser/campus
		// user-agent string will contain campus-ncl if its able to autologin
		$remotehost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

		if (strstr($remotehost,".ncl.ac.uk") && strstr($useragent,"campus-ncl")) {
			// create IdP URL...
			$page_url = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];;
			$gateway_idp_url = "https://internal.ncl.ac.uk/Shibboleth.sso/Login?target=".$page_url;

			// check-the-idp
			ob_start();
			include("https://gateway.ncl.ac.uk/idp/profile/Status");
			$state = ob_get_contents();
			ob_end_clean();

			if ($state == "ok") {
				// check no-session
				if ( ! isset($_SERVER['HTTP_SHIB_EP_PRINCIPALNAME'])) {
					header("Location: $gateway_idp_url");
					exit();
				}
			}
		}
	}

	public function getLoginPageURL($referrer = false)
	{
		if ($referrer === true) {
			$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : dirname($_SERVER['REQUEST_URI']);
		} elseif ($referrer) {
			$redirect = $referrer;
		} else {
			$redirect = $_SERVER['REQUEST_URI'];
		}
		return 'https://internal.ncl.ac.uk/Shibboleth.sso/Login?target=https://internal.ncl.ac.uk/careers/secure/cdm' . $redirect;
	}

	public function wasAutoLoggedIn()
	{
		return isset($_SERVER['Shib-Identity-Provider'])
			and $_SERVER['Shib-Identity-Provider'] == 'https://internalauth.ncl.ac.uk/idp/shibboleth';
	}
}