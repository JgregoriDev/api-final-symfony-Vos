<?php

namespace App\Controller;

use App\Form\VideojocType;
use App\Repository\VideojocRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Videojoc;
use App\Form\VotacioType;
use App\Repository\VotacioRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Faker\Factory;
use App\Entity\Usuari;
use App\Entity\Votacio;
use App\Form\FiltrarPerPreuType;
use App\Repository\GenereRepository;
use App\Repository\PlataformaRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use App\Entity\Plataforma;
use App\Entity\Genere;

/**
 * @Route("/api/v1")
 */

class VideojocController extends AbstractFOSRestController
{
    function Search($search_value, $array_name)
    {
        return (array_search($search_value, $array_name));
    }

    /**
     * @Rest\Get(path="/videojocs", name="api_llistar_jocs")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistar(VideojocRepository $vr, Request $request)
    {
        //Array de filtratge de claus
        $arrayKeys = ["id", "preu", "fechaEstreno"];
        $sort = $request->query->get("sort") ?? "ASC";
        //Obtinc parametro
        $parametro = $request->query->get("parametro");
        $results = $request->query->get("results") ?? 10;

        //Si no existeix sempre filtrar per id
        $resultat = in_array($parametro, $arrayKeys) === true ? $parametro : "id";
        $page = $request->query->get('page', 1);

        $pagesCount = ceil($vr->count([]) / $results);
        $pages = range(1, $pagesCount);

        $videojocs = $vr->findBy([], [$resultat => $sort], $results, ($results * ($page - 1)));

        foreach ($videojocs as $videojoc) {
            $this->editarPath($videojoc);
        }
        return $this->view(["Titul" => "Pàgina actual $page", "Tamany" => $pagesCount, "Resultat" => $videojocs]);
    }

    /**
     * @Rest\Get(path="/video", name="api_llistar_video")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function llistarJocs(VideojocRepository $vr, Request $request)
    {
        //Array de filtratge de claus
        $arrayKeys = ["id", "preu", "fechaEstreno"];
        $sort = $request->query->get("sort") ?? "ASC";
        //Obtinc parametro
        $parametro = $request->query->get("parametro");
        $results = $request->query->get("results") ?? 10;

        //Si no existeix sempre filtrar per id
        $resultat = in_array($parametro, $arrayKeys) === true ? $parametro : "id";
        $page = $request->query->get('page', 1);

        $pagesCount = ceil($vr->count([]) / $results);
        $pages = range(1, $pagesCount);

        $videojocs = $vr->findBy([], [$resultat => $sort], $results, ($results * ($page - 1)));

        foreach ($videojocs as $videojoc) {
            $this->editarPath($videojoc);
        }
        return $this->view(["Titul" => "Pàgina actual $page", "Tamany" => $pagesCount, "Resultat" => $videojocs]);
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
     * @Rest\Get(path="/admin/videojocs/", name="api_llistar_tots_jocs")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function methodName(EntityManagerInterface $emi)
    {

        $videojocs = $emi->getRepository(Videojoc::class)->findAll();
        return $this->view(["Title" => "Resultat Jocs", "Jocs" => $videojocs], Response::HTTP_OK);
    }

    /**
     * @Rest\Get(path="/videojoc/{id}", name="api_conseguir_videojoc")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function conseguirVideojoc(int $id, VideojocRepository $vr)
    {
        $videojoc = $vr->find($id);
        $nVotacions = count($videojoc->getVotacionsJoc());
        $this->editarPath($videojoc);

        return $this->view(["Title" => "Videojoc", "NumeroVotacions" => $nVotacions, "Videojoc" => $videojoc]);
    }

    /**
     * @Rest\Get(path="/videojoc/titol/{titol}", name="api_conseguir_videojoc_titol")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function conseguirVideojocPerTitol(string $titol, VideojocRepository $vr)
    {
        $videojoc = $vr->findOneBy(["titul" => $titol], []);
        // $videojoc->setTitul("hola");
        $nVotacions = count($videojoc->getVotacionsJoc());
        $this->editarPath($videojoc);
        return $this->view(["Title" => "Videojoc", "NumeroVotacions" => $nVotacions, "Videojoc" => $videojoc]);
    }

    /**
     * @Rest\Get(path="/videojoc/filtrar/preu/{min}/{max}", name="api_conseguir_filtrar_preu")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function filtrarVideojocPreu(int $min, int $max, Request $request, VideojocRepository $vr)
    {
        $form = $this->createForm(FiltrarPerPreuType::class, null);
        $form->handleRequest($request);

        $videojocs = $vr->filterByPrice($min, $max);
		foreach($videojocs as $videojoc){
            $this->editarPath($videojoc);
        }
        return $this->view(["Title" => "Videojoc per preu $min i $max", "cantitat" => count($videojocs), "Videojoc" => $videojocs]);
        // return $this->view(["Title" => "No hi han resultats", "Videojoc" => "Valor buit"]);
    }

    /**
     * @Rest\Post(path="/videojoc/nou", name="api_insertar_joc")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarVideojoc(EntityManagerInterface $emi, GenereRepository $gr, PlataformaRepository $pr, Request $request, SluggerInterface $slugger)
    {
        $videojoc = new Videojoc();
        $form = $this->createForm(VideojocType::class, $videojoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('portada')->getData();
            $data = $form->get('fechaEstreno')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('portades_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }


                $videojoc->setPortada($newFilename);
            } else {
                $faker = Factory::create();
                $videojoc->setPortada($faker->imageUrl(640, 480, 'animals', true));
            }

            $generes = json_decode($form->get("generes")->getData());
            $plataformes = json_decode($form->get("videojoc_plataforma")->getData());
            $videojoc->setFechaEstreno(new DateTime($data));
        
            foreach ($plataformes as $plataforma) {
        $p = $emi->getRepository(Plataforma::class)->findOneBy(["plataforma" => $plataforma]);
                if($p)$videojoc->addVideojocPlataforma($p);
            }
            foreach ($generes as $genere) {
                
                $g = $emi->getRepository(Genere::class)->findOneBy(["genere" => $genere]);
                if($g)$videojoc->addGenere($g);
            }


            $emi->persist($videojoc);
            $emi->flush();
            return ($this->view(["Title" => "Videojoc pujat de manera satisfactoria", "Videjoc" => $videojoc], Response::HTTP_OK));
        }
        return $this->view(["Title"=>"Error","result"=>$form], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put(path="/videojoc/{id}/editar", name="api_editar_joc")
     * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
     */
    public function editarVideojoc(int $id, EntityManagerInterface $emi, Request $request, SluggerInterface $slugger)
    {
        $videojoc = $emi->find(Videojoc::class, $id);
        if (!$videojoc) {
            $this->createNotFoundException();
        }
        $form = $this->createForm(VideojocType::class, $videojoc);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('portada')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('portades_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }


                $videojoc->setPortada($newFilename);
            }
            $emi->flush();
            return ($this->view($videojoc, Response::HTTP_CREATED));
        }
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete(path="/videojoc/{id}/borrar", name="api_borrar_joc")
     * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
     */
    public function borrarVideojoc(int $id, EntityManagerInterface $emi)
    {
        $videojoc = $emi->find(Videojoc::class, $id);
        if (!$videojoc) {
            $this->createNotFoundException();
        }
        $jocAuxiliar = $videojoc;
        $emi->remove($videojoc);
        $emi->flush();
        return $this->view(["Titol" => "Borrat joc de manera satisfactoria", "Resultat" => $jocAuxiliar], 200);
    }


    /**
     * @Rest\Get(path="/videojoc/buscar/{slug}", name="api_buscar_joc")
     * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
     */
    public function buscarVideojoc(String $slug, VideojocRepository $vr)
    {
        $jocAuxiliar = $vr->obtindreJocBuscanElTitol($slug);
        return $this->view(["Titol" => "Buscar joc amb titol $slug", "Resultat" => $jocAuxiliar], 200);
    }

    /**
     * @Rest\Get(path="/videojoc/{id}/comentaris", name="api_llistar_comentaris")
     * @Rest\View(serializerGroups={"videojoc","votacio","usuari","generes"}, serializerEnableMaxDepthChecks=true)
     */
    public function obtindreComentarisVideojocs(int $id, VotacioRepository $emi)
    {

        $votacions = $emi->findBy(["videojoc" => $id], []);
        if (!$votacions) {
            return $this->createNotFoundException();
        }
        return $votacions;
    }

    /**
     * @Rest\Get(path="/videojoc/plataforma/{id}", name="api_llistar_jocs_plataforma_get")
     * @Rest\View(serializerGroups={"videojoc","genere","plataforma"}, serializerEnableMaxDepthChecks=true)
     */
    public function conseguirVideojocPerFiltres(int $id, VideojocRepository $vr, Request $request)
    {
        $genere = $sort = (int)$request->query->get("genere") ?? 0;
        if (!is_int($genere)) {
            return $this->view(["Titol" => "Error", "Resultat" => "Error el genere ha de ser un valor numeric"], Response::HTTP_BAD_REQUEST);
        }
        // var_dump($genere);
        $result = $vr->findByPlataformaVideojocAndGenere($id, $genere);
        if (!$result) {
            $this->createNotFoundException();
        }
		
		foreach($result as $videojoc){
            $this->editarPath($videojoc);
        }
        // var_dump($videojocs);
        return $this->view(["Titol" => "Videojoc amb plataforma $id", "Resultat" => $result], 200);
    }

    /**
     * @Rest\Post(path="/videojoc/{id}/usuari/{ide}/comentari/nou", name="api_insertar_comentaris")
     * @Rest\View(serializerGroups={"videojoc","votacio","usuari"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarComentariVideojocs(int $id, int $ide, EntityManagerInterface $mr, Request $request, VotacioRepository $emi)
    {


        $usuari = $mr->getRepository(Usuari::class)->find($ide);
        if (!$usuari) {
            $this->createNotFoundException("Usuari no trobat");
        }
        $videojoc = $mr->getRepository(Videojoc::class)->find($id);
        $votacions = $emi->findBy(["usuari_votacio" => $usuari, "videojoc" => $id], []);
        if (!$votacions) {
            $this->createNotFoundException();
        }
        if (count($votacions) === 0) {
            $this->createNotFoundException();
        }
        if ($votacions) {
            return $this->view(["title" => "No pots tornar a votar " . $usuari->getEmail() . " ja que ja has votat", "votacio" => $votacions], Response::HTTP_BAD_REQUEST);
        }
        $votacio = new Votacio();
        $form = $this->createForm(VotacioType::class, $votacio);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $votacio->setUsuariVotacio($usuari)
                ->setVideojoc($videojoc);
            $mr->persist($votacio);
            $mr->flush();
            return $this->view(["Title" => "Votacio insertada", "result" => $votacio]);
        }
        return $this->view([$form]);
    }
}
