<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $firstDeploy  = $this->get('cache')->fetch('first_deploy');
        $latestDeploy = $this->get('cache')->fetch('latest_deploy');
        $lifeSpan     = null;

        if ($firstDeploy !== false) {
            $firstDeploy  = $firstDeploy->format('r');
            $lifeSpan     = (new Carbon($firstDeploy))->diffForHumans(Carbon::now(), true);
            $latestDeploy = $latestDeploy->format('r');
        }

        return $this->render('default/index.html.twig', array(
            'first_deploy'  => $firstDeploy,
            'latest_deploy' => $latestDeploy,
            'life_span'     => $lifeSpan,
        ));
    }
}
