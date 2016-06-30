<?php

namespace NclCareers\NclAuth;

use Illuminate\Support\ServiceProvider;

class NclAuthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(
			'NclCareers\NclAuth\Authentication\AuthenticationProviderInterface',
			'NclCareers\NclAuth\Authentication\ShibbolethAuthenticationProvider'
		);
		$this->app->singleton(
			'NclCareers\NclAuth\Identity\IdentityInterface',
			'NclCareers\NclAuth\Identity\DevelopmentIdentity'
		);
		$this->app->singleton(
			'NclCareers\NclAuth\Adapter\AdapterInterface',
			'NclCareers\NclAuth\Adapter\LaravelAdapter'
		);
		$this->app->singleton('NclCareers\NclAuth\AuthHandler');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}