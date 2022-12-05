<?php

namespace App\Controller;

use App\Entity\Genere;
use App\Form\VideojocType;
use App\Repository\VideojocRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Videojoc;
use App\Form\GeneresType;
use App\Repository\GenereRepository;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Route("/api/v1")
 */

class GenereController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/generes", name="api_llistar_generes")
     * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistar(GenereRepository $gr)
    {
        return $gr->findAll();
    }

    /**
     * @Rest\Post(path="/genere/nou", name="api_insertar_genere")
     * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarGenere(EntityManagerInterface $emi, Request $request)
    {
        $genere = new Genere();
        $form = $this->createForm(GeneresType::class, $genere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->persist($genere);
            $emi->flush();
            return ($this->view($genere, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put(path="/genere/{id}/editar", name="api_editar_genere")
     * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function editarGenere(int $id, EntityManagerInterface $emi, Request $request)
    {
        $genere = $emi->find(Genere::class, $id);
        if (!$genere) {
            $this->createNotFoundException();
        }
        $form = $this->createForm(GeneresType::class, $genere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->flush();
            return ($this->view($genere, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete(path="/genere/{id}/borrar", name="api_borrar_genere")
     * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function borrarGenere(int $id, EntityManagerInterface $emi)
    {
        $genere = $emi->find(Genere::class,$id);
        if (!$genere) {
            $this->createNotFoundException();
        }
        $jocAuxiliar = $genere;
        $emi->remove($genere);
        $emi->flush();
        return $this->view(["Titol" => "Borrat genere de manera satisfactoria", "Resultat" => $jocAuxiliar], 200);
    }
}
