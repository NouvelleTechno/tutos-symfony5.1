<?php
namespace App\Service;

use App\Entity\Annonces;
use App\Entity\Images;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ManagePicturesService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    public function add(
        array $images,
        Annonces $annonce
    )
    {
        // On boucle sur les images
        foreach($images as $image){
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            
            // On copie le fichier dans le dossier uploads
            $image->move(
                $this->params->get('images_directory'),
                $fichier
            );
            
            // On crée l'image dans la base de données
            $img = new Images();
            $img->setName($fichier);
            $annonce->addImage($img);
        }
    }
}