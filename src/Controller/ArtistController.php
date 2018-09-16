<?php

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property ArtistRepository artistRepository
 */
class ArtistController extends AbstractController
{

    /**
     * @Route("/artist", name="artist")
     */
    public function index()
    {
        return $this->render('artist/index.html.twig', [
            'controller_name' => 'ArtistController',
        ]);
    }

    public function getAll(){

        $artists = $this->getDoctrine()->getRepository(Artist::class)->findAll();

        $arrayCollection = array();

        foreach($artists as $item) {

            $albums = $item->getAlbums();

            foreach ($albums as $album){

                $alb[] = array(

                    'title' => $album->getTitle(),
                    'cover' => $album->getCover(),
                    'token' => $album->getToken(),
                );
            }

            $arrayCollection[] = array(
                'name' => $item->getName(),
                'token' => $item->getToken(),
                'albums' => $alb,
            );

        }
        return new JsonResponse($arrayCollection);
    }

    public function getByToken($token){

            $artist = $this->getDoctrine()->getRepository(Artist::class)->findOneBy(['token' => $token]);

            $result = [];
            $result["name"] = $artist->getName();
            $result["token"] = $artist->getToken();


            $albums = $artist->getAlbums();

            foreach ($albums as $album){

                $alb[] = array(
                    'title' => $album->getTitle(),
                    'cover' => $album->getCover(),
                    'token' => $album->getToken(),
                );
                $result["albums"] = $alb;
            }

            if (!$artist) {
                throw $this->createNotFoundException(
                    'No Artist found for token '.$token
                );
            }

        return new JsonResponse($result);
    }


}
