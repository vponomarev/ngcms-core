<?php

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Loads template from the filesystem.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class Twig_Loader_NGCMS implements Twig_LoaderInterface
{
    protected $paths;
    protected $cache;

    /**
     * Constructor.
     *
     * @param string|array $paths A path or an array of paths where to look for templates
     */
    public function __construct($paths)
    {
        $this->setPaths($paths);
	$this->templateConversion = array();
    }

    /**
     * Returns the paths to the templates.
     *
     * @return array The array of paths where to look for templates
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Sets the paths where templates are stored.
     *
     * @param string|array $paths A path or an array of paths where to look for templates
     */
    public function setPaths($paths)
    {
        // invalidate the cache
        $this->cache = array();

        if (!is_array($paths)) {
            $paths = array($paths);
        }

        $this->paths = array();
        foreach ($paths as $path) {
        	// Delete last '/' if provided
        	if ((strlen($path) > 1) && (substr($path, -1) == '/')) {
        		$path = substr($path, 0, -1);
        	}

        	// Lowercase if using WINDOWS
        	if (substr($path, 1, 1) == ':')
        		$path = strtolower($path);

            if (!is_dir($path)) {
                throw new Twig_Error_Loader(sprintf('The "%s" directory does not exist.', $path));
            }

            $this->paths[] = strtr($path, '\\', '/');
        }
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        $content = file_get_contents($this->findTemplate($name));
	if (isset($this->templateConversion[$name]) && is_array($this->templateConversion[$name])) {
		$tconv = $this->templateConversion[$name];
		$content = str_replace(array_keys($tconv), array_values($tconv), $content);
	}
	return $content;
    }

	public function setConversion($name, $variables) {
		$this->templateConversion[$name] = $variables;
		return true;
	}

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return $this->findTemplate($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        return filemtime($this->findTemplate($name)) < $time;
    }

    protected function findTemplate($name)
    {
        // normalize name
        $name = preg_replace('#/{2,}#', '/', strtr($name, '\\', '/'));

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

    	// For windows - convert path to lowercase
    	if (preg_match('#^[a-zA-Z]\:\/#', $name)) {
    		$name = strtolower($name);
    	}

    	// Check if we try to load template by absolute path, in this case we need to be sure,
    	// that specified path is within $this->paths
    	if ((substr($name, 0, 1) == '/')||preg_match('#^[a-z]\:\/#', $name)) {
    		foreach ($this->paths as $path) {
    			if (substr($name, 0, strlen($path)+1) == ($path.'/')) {
    				// Path found. Check for file
    				$xname = substr($name, strlen($path)+1);
    				$this->validateName($xname);
    				if (is_file($path.'/'.$xname)) {
    					return $this->cache[$name] = $path.'/'.$xname;
    				}
    				throw new Twig_Error_Loader(sprintf('Unable to find template [ABSOLUTE PATH] "%s" (looked into: %s).', $name, $path));
    			}
    		}
    		throw new Twig_Error_Loader(sprintf('Unable to find template [ABSOLUTE PATH] "%s" (looked into: %s).', $name, implode(', ', $this->paths)));
    	}

        foreach ($this->paths as $path) {
            if (is_file($path.'/'.$name)) {
                return $this->cache[$name] = $path.'/'.$name;
            }
        }

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths)));
    }

    protected function validateName($name)
    {
        $parts = explode('/', $name);
        $level = 0;
        foreach ($parts as $part) {
            if ('..' === $part) {
                --$level;
            } elseif ('.' !== $part) {
                ++$level;
            }

            if ($level < 0) {
                throw new Twig_Error_Loader(sprintf('Looks like you try to load a template outside configured directories (%s).', $name));
            }
        }
    }
}


/**
 * Wrapper for template processing. Adds to each template variables:
 * _templateName
 * _templatePath
 */
abstract class Twig_Template_NGCMS extends Twig_Template {
    public function render(array $context) {
	$context['_templateName'] = $this->getTemplateName();
	$context['_templatePath'] = dirname($this->getTemplateName()).DIRECTORY_SEPARATOR;
	return parent::render($context);
    }
}
