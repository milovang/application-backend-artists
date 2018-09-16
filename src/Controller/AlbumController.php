<?php

namespace App\Controller;

use App\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     * @Route("/album", name="album")
     */
    public function index()
    {
        return $this->render('album/index.html.twig', [
            'controller_name' => 'AlbumController',
        ]);
    }


    public function getByToken($token){

        $album = $this->getDoctrine()->getRepository(Album::class)->findOneBy(['token' => $token]);

        if (!$album) {
            throw $this->createNotFoundException(
                'No Album found for token '.$token
            );
        }

        $result = [];
        $result["title"] = $album->getTitle();
        $result["token"] = $album->getToken();


        $artists = $album->getArtist();
        foreach ($artists as $artist){

            $art[] = array(
               // 'name' => $artist->getName(),
               // 'token' => $artist->getToken(),
            );

            $result["artists"] = $art;
        }

        $songs = $album->getSongs();
        foreach ($songs as $song){

            $sng[] = array(

                'title' => $song->getTitle(),
                'length' => $song->getlength(),
            );

            $result["songs"] = $sng;
        }


        return new JsonResponse($result);
    }
}
