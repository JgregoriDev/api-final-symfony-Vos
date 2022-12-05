<?php

namespace App\Controller;
use App\Entity\Marca;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MarcaType;
use App\Repository\MarcaRepository;

/**
 * @Route("/api/v1")
 */

class MarcaController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/marques", name="api_llistar_marques")
     * @Rest\View(serializerGroups={"marca","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistar(MarcaRepository $gr)
    {
        return $gr->findAll();
    }

    /**
     * @Rest\Post(path="/marca/nou", name="api_insertar_marca")
     * @Rest\View(serializerGroups={"marca"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarMarca(EntityManagerInterface $emi, Request $request)
    {
        $marca = new Marca();
        $form = $this->createForm(MarcaType::class, $marca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->persist($marca);
            $emi->flush();
            return ($this->view($marca, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put(path="/marca/{id}/editar", name="api_editar_marca")
     * @Rest\View(serializerGroups={"marca"}, serializerEnableMaxDepthChecks=true)
     */
    public function editarMarca(int $id, EntityManagerInterface $emi, Request $request)
    {
        $marca = $emi->find(Marca::class, $id);
        if (!$marca) {
            $this->createNotFoundException();
        }
        $form = $this->createForm(MarcaType::class, $marca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emi->flush();
            return ($this->view($marca, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete(path="/marca/{id}/borrar", name="api_borrar_marca")
     * @Rest\View(serializerGroups={"marca"}, serializerEnableMaxDepthChecks=true)
     */
    public function borrarMarca(int $id, EntityManagerInterface $emi)
    {
        $marca = $emi->find(Marca::class,$id);
        if (!$marca) {
            $this->createNotFoundException();
        }
        $jocAuxiliar = $marca;
        $emi->remove($marca);
        $emi->flush();
        return $this->view(["Titol" => "Borrat genere de manera satisfactoria", "Resultat" => $jocAuxiliar], 200);
    }
}
