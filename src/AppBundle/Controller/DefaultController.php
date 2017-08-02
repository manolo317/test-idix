<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @Route("/films", name="homepage")
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // find all movies in DB
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Film');
        $films = $repository->findAll();

        return $this->render('AppBundle:Default:index.html.twig', ['films' => $films]);
    }

    /**
     * @param Request $request
     * @param $filmId
     * @return Response
     */
    public function viewAction(Request $request, $filmId)
    {
        // find a movie
        $em = $this->getDoctrine()->getManager();
        $film = $em->getRepository('AppBundle:Film')
            ->find($filmId);

        return $this->render('AppBundle:Default:film_details.html.twig', [
            'film' => $film
        ]);
    }

}
