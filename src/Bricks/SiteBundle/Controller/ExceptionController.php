<?php

namespace Bricks\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Exception controller.
 */
class ExceptionController extends Controller
{
    /**
     * @param Request $request
     * @param FlattenException $exception
     * @param DebugLoggerInterface $logger
     * @param string $_format
     * @return Response
     *
     * @Template()
     */
    public function showAction(Request $request, FlattenException $exception)
    {
        $code = $exception->getStatusCode();

        return array(
            'exception'      => $exception
        );
    }
}