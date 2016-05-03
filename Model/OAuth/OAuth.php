<?php

namespace App\Module\User\Model\OAuth;

use App\Module\User\Model\OAuth\Provider\AbstractProvider;
use App\Module\User\Model\OAuth\Provider\ProviderInterface;
use Dore\Core\Exception\EnvironmentExceptions\NotExistsException;
use Dore\Core\Exception\SemanticExceptions\InvalidArgumentException;

/**
 * Class OAuth
 * @package App\Module\User\Model\OAuth
 * @method int getId()
 * @method string getNameProvider()
 * @method bool authenticate()

 */
class OAuth
{

    /** @var AbstractProvider */
    protected $provider;

    /**
     * OAuth constructor.
     *
     * @param string $provider
     * @param array  $component
     *
     * @throws NotExistsException
     * @throws InvalidArgumentException
     */
    public function __construct($provider, array $component)
    {
        $class = __NAMESPACE__ . '\\Provider\\' . ucwords(strtolower($provider));

        if (!class_exists($class)) {
            throw new NotExistsException("Class [{$class}] does not exist");
        }

        $provider = new $class($component);

        if ($provider instanceof ProviderInterface) {
            $this->provider = $provider;
        } else {
            throw new InvalidArgumentException('OAuth only expects instance of the type' .
                ProviderInterface::class
            );
        }
    }

    /**
     * Call method of this class or methods of adapter class
     *
     * @param $method
     * @param $params
     *
     * @return AbstractProvider
     */
    public function __call($method, $params)
    {
        if (method_exists($this->provider, $method)) {
            return $this->provider->$method();
        }
    }
}