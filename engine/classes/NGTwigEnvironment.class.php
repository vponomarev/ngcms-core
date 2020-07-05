<?php

// namespace NG\Twig;

use Twig\Environment;
use Twig\Loader\LoaderInterface;

use Twig\Extension\StringLoaderExtension;

class NGTwigEnvironment
{
    /**
     * Twig env
     * 
     * @var Environment;
     */
    public $env;

    /**
     * @param LoaderInterface $loader Twig loader
     * @param array $options Env options
     */
    public function __construct(LoaderInterface $loader, $options = [])
    {
        $this->env = new Environment($loader, $options);

        $this->env->addExtension(new StringLoaderExtension());
        $this->env->addExtension(new NGTwigExtension());
    }

    public function __call($method, $parameters)
    {
        if ($method === 'loadTemplate') {
            return call_user_func_array([$this->env, 'load'], $parameters);
        }

        if (method_exists($this->env, $method)) {
            return call_user_func_array([$this->env, $method], $parameters);
        }
    }
}