<?php

namespace RecupBundle\Controller;

use Axia\BiblioBundle\Entity\Anime;
use Axia\BiblioBundle\Entity\BD;
use Axia\BiblioBundle\Entity\Collection;
use Axia\BiblioBundle\Entity\Comics;
use Axia\BiblioBundle\Entity\Editeur;
use Axia\BiblioBundle\Entity\Film;
use Axia\BiblioBundle\Entity\Jeu;
use Axia\BiblioBundle\Entity\Livre;
use Axia\BiblioBundle\Entity\Manga;
use Axia\BiblioBundle\Entity\Num_Collection_Film;
use Axia\BiblioBundle\Entity\Num_Collection_Livre;
use Axia\BiblioBundle\Entity\Personne;
use Axia\BiblioBundle\Entity\Serie;
use Axia\BiblioBundle\Entity\Tag;
use Axia\BiblioBundle\Entity\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class RecupController extends Controller
{
    public $em;

    public function indexAction()
    {
        return $this->render('RecupBundle:Recup:index.html.twig');
    }

    public function traitAction(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        if($request->isXmlHttpRequest())
        {
            $str_type = $request->get('type');
            $tableau = json_decode(file_get_contents("http://old.perso.dev/app_dev.php/api/extract/".$str_type),true);
            foreach ($tableau as $objet)
            {
                var_dump($objet);
                if($str_type == 'Livre')
                {
                    $element = new Livre();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    if($objet['date_parution'] != "-0001-11-30T00:00:00+0009"):
                        $date = new \DateTime($objet['date_parution']);
                        $element->setDateParution($date);
                    endif;
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    if(array_key_exists('collection',$objet) && $objet['collection']):
                        $element = $this->create_collection($objet['collection'], $element, $str_type, $objet['num_tome']);
                    endif;
                    $this->em->persist($element);
                }
                elseif($str_type == 'Manga')
                {
                    $element = new Manga();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    $element->setFini($objet['fini']);
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $this->em->persist($element);
                }
                elseif($str_type == 'Comic')
                {
                    $element = new Comics();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    $element->setFini($objet['fini']);
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $this->em->persist($element);
                }
                elseif($str_type == 'BD')
                {
                    $element = new BD();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    $element->setFini($objet['fini']);
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $this->em->persist($element);
                }
                elseif($str_type == 'Jeu')
                {
                    $element = new Jeu();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    /*if($objet['date_parution'] != "-0001-11-30T00:00:00+0009"):
                        $date = new \DateTime($objet['date_parution']);
                        $element->setDateParution($date);
                    endif;*/
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $element = $this->get('crud.services')->operation($str_type, $element);
                    $this->em->persist($element);
                }
                elseif($str_type == 'Film')
                {
                    $element = new Film();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    if($objet['date_parution'] != "-0001-11-30T00:00:00+0009"):
                        $date = new \DateTime($objet['date_parution']);
                        $element->setDateParution($date);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $element = $this->get('crud.services')->operation($str_type, $element);
                    $this->em->persist($element);
                }
                elseif($str_type == 'Anime')
                {
                    $element = new Anime();
                    $element->setTitre($objet['nom']);
                    $element->setFiche($objet['fiche']);
                    $element->setFini($objet['fini']);
                    if($objet['date_parution'] != "-0001-11-30T00:00:00+0009"):
                        $date = new \DateTime($objet['date_parution']);
                        $element->setDateParution($date);
                    endif;
                    if(array_key_exists('editeurs',$objet) && $objet['editeurs']):
                        $element = $this->create_editeur($objet['editeurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('createurs',$objet) && $objet['createurs']):
                        $element = $this->create_auteur($objet['createurs'], $element, $str_type);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $element = $this->get('crud.services')->operation($str_type, $element);
                    $this->em->persist($element);
                }
                elseif($str_type == 'Serie')
                {
                    $element = new Serie();
                    if(array_key_exists('collection',$objet) && $objet['collection']['nom']):
                        $element->setTitre($objet['collection']['nom']);
                    else:
                        $element->setTitre($objet['nom']);
                    endif;
                    $element->setFiche($objet['fiche']);
                    $element->setFini($objet['fini']);
                    if(array_key_exists('num_tome',$objet) && $objet['num_tome']):
                        $element->setNbEpisode($objet['num_tome']);
                    endif;
                    if($objet['date_parution'] != "-0001-11-30T00:00:00+0009"):
                        $date = new \DateTime($objet['date_parution']);
                        $element->setDateParution($date);
                    endif;
                    if(array_key_exists('tags',$objet) && $objet['tags']):
                        $element = $this->create_tag($objet['tags'], $element);
                    endif;
                    $element = $this->get('crud.services')->operation($str_type, $element);
                    $this->em->persist($element);
                }
                $this->em->flush();
            }
        }
        return $this->render('RecupBundle:Recup:index.html.twig');
    }

    public function create_type($str_type)
    {
        $type = $this->get('type.services')->findOne(array('libelle' => $str_type));
        if($type == null)
        {
            $type = new Type();
            $type->setLibelle($str_type);
            $this->em->persist($type);
        }
        return $type;
    }

    public function create_editeur($array, $element, $str_type)
    {
        foreach ($array as $editeur)
        {
            $mon_editeur = $this->get('editeur.services')->findOne(array('nom' => $editeur['nom']));
            if($mon_editeur == null)
            {
                $mon_editeur = new Editeur();
                $mon_editeur->setNom($editeur['nom']);
                $this->em->persist($mon_editeur);
            }
            $type = $this->create_type($str_type);
            $types = $mon_editeur->getTypes();
            if(($types == null) || ($types != null && $types->contains($type) == false))
            {
                echo '_____'.$mon_editeur->getNom();
                if($types != null):
                    echo $types->contains($type);
                endif;
                $mon_editeur->addType($type);
            }
            $element->addEditeur($mon_editeur);
        }
        return $element;
    }

    public function create_auteur($array, $element, $str_type)
    {
        foreach ($array as $auteur)
        {
            $mon_auteur = $this->get('personne.services')->findOne(array('nom' => $auteur['nom'], 'prenom' => $auteur['prenom']));
            if($mon_auteur == null)
            {
                $mon_auteur = new Personne();
                $mon_auteur->setNom($auteur['nom']);
                $mon_auteur->setPrenom($auteur['prenom']);
                $this->em->persist($mon_auteur);
            }
            $type = $this->create_type($str_type);
            $types = $mon_auteur->getTypes();
            if(($types == null) || ($types != null && $types->contains($type) == false))
            {
                $mon_auteur->addType($type);
            }
            $element->addAuteur($mon_auteur);
        }
        return $element;
    }

    public function create_tag($array, $element)
    {
        foreach ($array as $tag)
        {
            $mon_tag = $this->get('tag.services')->findOne(array('libelle' => $tag['nom']));
            if($mon_tag == null)
            {
                $mon_tag = new Tag();
                $mon_tag->setLibelle($tag['nom']);
                $this->em->persist($mon_tag);
            }
            $element->addTag($mon_tag);
        }
        return $element;
    }

    public function create_collection($collection, $element, $str_type, $num)
    {
        $nom = $collection['nom'];
        $ma_coll = $this->get('collection.services')->findOne(array('nom' => $nom));
        if($ma_coll == null)
        {
            $ma_coll = new Collection();
            $ma_coll->setNom($nom);
            $this->em->persist($ma_coll);
        }
        if($str_type == 'Livre')
        {
            $num_coll = new Num_Collection_Livre();
            $num_coll->setCollection($ma_coll);
            $num_coll->setLivre($element);
            $num_coll->setNumero($num);
            $this->em->persist($num_coll);
            $element->setCollection($num_coll);
        }
        elseif($str_type == 'Film')
        {
            $num_coll = new Num_Collection_Film();
            $num_coll->setCollection($ma_coll);
            $num_coll->setFilm($element);
            $num_coll->setNumero($num);
            $this->em->persist($num_coll);
            $element->setCollection($num_coll);
        }
        return $element;
    }
}
