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

        // Get doctrine manager
        $em = $this->getDoctrine()->getManager();

        // Write page view to database, the ugly way
        $sql = "INSERT INTO page_views SET ip = '" . $this->get('request')->getClientIp() . "'";
        $em->getConnection()->exec($sql);

        // Write page view to database, the ugly way
        $sql       = "SELECT count(*) as cnt FROM page_views";
        $pageViews = $em->getConnection()->query($sql)->fetchAll(\PDO::FETCH_COLUMN)[0];

        return $this->render('default/index.html.twig', array(
            'first_deploy'  => $firstDeploy,
            'latest_deploy' => $latestDeploy,
            'life_span'     => $lifeSpan,
            'page_views'    => $pageViews
        ));
    }
}
