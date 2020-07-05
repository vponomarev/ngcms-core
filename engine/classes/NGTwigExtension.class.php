<?php

// namespace NG\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

use Twig\TwigFilter;
use Twig\TwigFunction;

use Twig\Compiler;
use Twig\Node\Expression\FunctionExpression;

class NGTwigLocalPathFunctionExpression extends FunctionExpression
{
    protected function compileArguments(Compiler $compiler, $isArray = false): void
    {
        $compiler->raw($isArray ? '[' : '(');

        $compiler->string($this->getTemplateName());

        $compiler->raw($isArray ? ']' : ')');
    }
}

class NGTwigExtension extends AbstractExtension implements GlobalsInterface
{    
    public function getFilters()
    {
        return [
            new TwigFilter('truncateHTML', 'twigTruncateHTML'),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('pluginIsActive', 'getPluginStatusActive'),
            new TwigFunction('localPath', 'twigLocalPath', ['node_class' => NGTwigLocalPathFunctionExpression::class]),
            new TwigFunction('getLang', 'twigGetLang'),
            new TwigFunction('isLang', 'twigIsLang'),
            new TwigFunction('isHandler', 'twigIsHandler'),
            new TwigFunction('isCategory', 'twigIsCategory'),
            new TwigFunction('isNews', 'twigIsNews'),
            new TwigFunction('isPerm', 'twigIsPerm'),
            new TwigFunction('callPlugin', 'twigCallPlugin'),
            new TwigFunction('isSet', 'twigIsSet', array('needs_context' => true)),
            new TwigFunction('debugContext', 'twigDebugContext', array('needs_context' => true)),
            new TwigFunction('debugValue', 'twigDebugValue'),
            new TwigFunction('getCategoryTree', 'twigGetCategoryTree'),
            new TwigFunction('engineMSG', 'twigEngineMSG'),
        ];
    }

    public function getGlobals(): array
    {
        global $lang, $CurrentHandler, $twigGlobal, $SYSTEM_FLAGS, $systemAccessURL;

        return [
            'lang' => &$lang,
            'handler' => &$CurrentHandler,
            'global' => &$twigGlobal,
            'system_flags' => &$SYSTEM_FLAGS,
            'skins_url' => skins_url,
            'admin_url' => admin_url,
            'home' => home,
            'currentURL' => $systemAccessURL,
        ];
    }
}