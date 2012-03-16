<?php

namespace Acme\DemoBundle;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ParamRouterListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathinfo();
        $prefix = '/params';

        if (strpos($path, $prefix) === 0) { // only listen for paths starting with /ajax so you can use the routing component for other paths
            $parts = explode('/', 
                substr($path, strlen($prefix) + 1)
            );

            // parse the param1/value1/param2/value2 structure
            $keys = array();
            foreach (range(0, count($parts) - 1, 2) as $i)
            {
                $keys[] = $parts[$i];
            }

            $values = array();
            foreach (range(1, count($parts) - 1, 2) as $i)
            {
                $values[] = $parts[$i];
            }

            // maybe there is a param without a value at the end of the url
            if (count($keys) > count($values)) {
                $values[] = null;
            }

            $arguments = array_combine($keys, $values);

            $request->attributes->add($arguments); // add the parsed parameters
            $request->attributes->set('_controller', 'AcmeDemoBundle:Demo:params');
        }
        
        // if the _controller attributes hasn't been set the router component will do it's job
    }
}
