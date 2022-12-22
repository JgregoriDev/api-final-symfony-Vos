<?php

namespace App\Controller;

use App\Entity\Plataforma;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\GeneresType;
use App\Form\PlataformesType;
use App\Repository\GenereRepository;
use App\Repository\PlataformaRepository;
use App\Repository\VideojocRepository;
use App\Entity\Videojoc;
/**
 * @Route("/api/v1")
 */

class PlataformaController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/plataformes", name="api_llistar_plataformes")
     * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistar(PlataformaRepository $gr)
    {
        return $gr->findAll();
    }

    /**
     * @Rest\Get(path="/plataforma/{id}", name="api_conseguir_plataforma")
     * @Rest\View(serializerGroups={"plataformes","videojocs","genere"}, serializerEnableMaxDepthChecks=true)
     */
    public function conseguir(int $id, PlataformaRepository $pr, GenereRepository $gr, VideojocRepository $vr, Request $request)
    {
        $genereId = (int)$request->query->get('genere') ?? null;
        $plataforma=$pr->find($id);
        foreach ($plataforma->getPlataformaVideojocs() as $videojoc) {
            $this->editarPath($videojoc);
        }
        

        return $pr->find($id);
    }


    public function editarPath(Videojoc $videojoc): void
        {
            if (!str_contains($videojoc->getPortada(), "http")) {
                // $videojoc->setPortada("http://vos.es/uploads/portades_directory/" . $videojoc->getPortada());
                // $videojoc->setPortada("http://app.11josep.daw.iesevalorpego.es/uploads/portades_directory/" . $videojoc->getPortada());
                if ($_SERVER['SERVER_NAME'] === "vos.es")
                    $videojoc->setPortada($this->getParameter('localhost') . $videojoc->getPortada());
                if ($_SERVER['SERVER_NAME'] === "app.11josep.daw.iesevalorpego.es")
                    $videojoc->setPortada($this->getParameter('extern') . $videojoc->getPortada());
            }
        }
    /**
     * @Rest\Post(path="/plataforma/nou", name="api_insertar_plataforma")
     * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarPlataforma(EntityManagerInterface $emi, Request $request)
    {
        $plataforma = new Plataforma();
        $form = $this->createForm(PlataformesType::class, $plataforma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->persist($plataforma);
            $emi->flush();
            return ($this->view($plataforma, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put(path="/plataforma/{id}/editar", name="api_editar_plataforma")
     * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function editarPlataforma(int $id, EntityManagerInterface $emi, Request $request)
    {
        $plataforma = $emi->find(Plataforma::class, $id);
        if (!$plataforma) {
            $this->createNotFoundException();
        }
        $form = $this->createForm(PlataformesType::class, $plataforma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->flush();
            return ($this->view($plataforma, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete(path="/plataforma/{id}/borrar", name="api_borrar_plataforma")
     * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function borrarPlataforma(int $id, EntityManagerInterface $emi)
    {
        $plataforma = $emi->find(Plataforma::class, $id);
        if (!$plataforma) {
            $this->createNotFoundException();
        }
        $jocAuxiliar = $plataforma;
        $emi->remove($plataforma);
        $emi->flush();
        return $this->view(["Titol" => "Borrat plataforma de manera satisfactoria", "Resultat" => $jocAuxiliar], 200);
    }
}
