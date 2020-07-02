<?php

use Twig\Environment;
use Twig\Loader\LoaderInterface;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class NGTwigGlobalExtension extends AbstractExtension implements GlobalsInterface
{    
    public function getGlobals(): array
    {
        global $lang, $CurrentHandler, $twigGlobal, $SYSTEM_FLAGS;

        return [
            'lang' => &$lang,
            'handler' => &$CurrentHandler,
            'global' => &$twigGlobal,
            'system_flags' => &$SYSTEM_FLAGS,
        ];
    }
}

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

        $this->env->addExtension(new NGTwigGlobalExtension());
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