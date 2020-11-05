<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class MaintenanceListener{
    private $maintenance;
    private $twig;

    public function __construct($maintenance, Environment $twig){
        $this->maintenance = $maintenance;
        $this->twig = $twig;
    }

    public function onKernelRequest(RequestEvent $event){
        // On vérifie si le fichier .maintenance n'existe pas
        if(!file_exists($this->maintenance)){
            return;
        }

        // Le fichier existe

        // On définit la réponse
        $event->setResponse(
            new Response(
                $this->twig->render('maintenance/maintenance.html.twig'),
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );
        // On stoppe le traitement des évènements
        $event->stopPropagation();
    }
}