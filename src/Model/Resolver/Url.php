<?php

namespace Visma\SeoMetaRobots\Model\Resolver;

class Url implements RobotsTagResolverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Visma\SeoMetaRobots\Service\UrlMatcher
     */
    protected $urlMatcher;

    /**
     * @var \Visma\SeoMetaRobots\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Visma\SeoMetaRobots\Service\UrlMatcher $urlMatcher,
        \Visma\SeoMetaRobots\Helper\Configuration $configuration
    ) {
        $this->request = $request;
        $this->urlMatcher = $urlMatcher;
        $this->configuration = $configuration;
    }

    /**
     * @inheritDoc
     */
    public function resolve()
    {
        $rules = $this->configuration->getUrlRules();

        if (empty($rules)) {
            return null;
        }

        $uri = ltrim($this->request->getRequestUri(), '/');
        foreach ($rules as $rule) {
            if ($this->urlMatcher->match($uri, $rule['expression'])) {
                return $this->tagToReturnValue($rule['tag']);
            }
        }

        return null;
    }

    protected function tagToReturnValue($tag)
    {
        $tag = strtoupper($tag);

        if (!isset(\Visma\SeoMetaRobots\Model\Config\Source\Attribute\RobotsMetaTag::$tags[$tag])) {
            return null;
        }

        return \Visma\SeoMetaRobots\Model\Config\Source\Attribute\RobotsMetaTag::$tags[$tag];
    }
}
