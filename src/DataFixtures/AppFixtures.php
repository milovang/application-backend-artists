<?php
/**
 * Created by PhpStorm.
 * User: Milovan HP
 * Date: 16-Sep-18
 * Time: 19:40
 */

namespace App\DataFixtures;
use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = file_get_contents('https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json');
        $items = json_decode($data, true);

        foreach ($items as $artists) {

            $artist = new Artist();
            $artist->setName($artists['name']);
            $tokenArtist = TokenGenerator::generate(6);
            $artist->setToken($tokenArtist);
            $manager->persist($artist);
            $manager->flush();

            foreach ($artists['albums'] as $albums) {
                $album = new Album();
                $album->setTitle($albums['title']);
                $album->setCover($albums['cover']);
                $album->setDescription($albums['description']);
                $album->setArtist($artist);
                $tokenAlbum = TokenGenerator::generate(6);
                $album->setToken($tokenAlbum);
                $manager->persist($album);
                $manager->flush();

                foreach ($albums['songs'] as $songs) {
                    $song = new Song();
                    $song->setTitle($songs['title']);
                    $song->setAlbum($album);
                    $length = $songs['length'];

                    $time = "00:" . $length;
                    $seconds = strtotime("1970-01-01 $time UTC");

                    $song->setLength($seconds);
                    $manager->persist($song);
                    $manager->flush();
                }
            }
        }
    }
}