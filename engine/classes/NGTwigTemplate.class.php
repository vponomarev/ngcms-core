<?php

use Twig\Template;

/**
 * Wrapper for template processing. Adds to each template variables:
 * _templateName
 * _templatePath
 */
abstract class NGTwigTemplate extends Template
{
    public function render(array $context)
    {
        $context['_templateName'] = $this->getTemplateName();
        $context['_templatePath'] = dirname($this->getTemplateName()).DIRECTORY_SEPARATOR;

        return parent::render($context);
    }
}