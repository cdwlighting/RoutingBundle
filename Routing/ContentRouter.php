<?php

namespace Symfony\Cmf\Bundle\ChainRoutingBundle\Routing;

use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\RequestContext;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\ChainRoutingBundle\Controller\ControllerResolver;

class ContentRouter implements RouterInterface
{
    protected $om;
    protected $resolver;
    protected $context;

    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    public function generate($name, $parameters = array(), $absolute = false)
    {
        /* TODO */
    }

    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function setControllerResolver(ControllerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Returns an array of parameter like this
     *
     * array(
     *   "_controller" => "NameSpace\Controller::action", 
     *   "reference" => $document,
     * )
     *
     * @param string $url
     * @return array
     */
    public function match($url)
    {
        $document = $this->om->find(null, $url);

        if (!$document  instanceof RouteObjectInterface) {
            return false;
        }

        $defaults = $this->resolver->getController($document);
        if (empty($defaults['_controller'])) {
            return false;
        }

        $defaults['reference'] = $document->getReference();

        return $defaults;
    }

}
