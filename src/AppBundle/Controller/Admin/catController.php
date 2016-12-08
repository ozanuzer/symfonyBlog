<?php
namespace AppBundle\Controller\Admin;

use AppBundle\Utils\Classes\Etc;
use AppBundle\Entity\Categories;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class catController extends Controller
{
    /**
     * Matches /admin/categories exactly
     * @Route("/admin/categories", name="categories_list")
     */
    public function indexAction(Request $request)
    {
        $val = array();
        $item = array();
        $subs = '';
        $val['val']['process'] = 'default';

        $allCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($allCats)) $allCats[0] = $allCats;
        foreach ($allCats as $key => $value) {
            $item[$key]['id'] = $value->getId();
            $item[$key]['title'] = $value->getTitle();
            $item[$key]['main'] = $value->getMain();
            $item[$key]['slug'] = $value->getSlug();
            $item[$key]['sort'] = $value->getSort();

            $subCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
            if($subCats){
                if (is_array($subCats))
                    foreach ($subCats as $subvalue) {
                        $subs .= $subvalue->getTitle().', ';
                    }
                else $subs = $subCats->getTitle();
                $item[$key]['subs'] = $subs;
            } else $item[$key]['subs'] = 0;
            $item[$key]['relatedContents'] = count($this->getDoctrine()->getRepository('AppBundle:Contents')->findByCatId($item[$key]['id']));
        }
        $val['val']['items'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/category.html.twig',$val);
    }

    /**
    * Matches /admin/categories/new
     * @Route("/admin/categories/new", name="categories_new")
     */
    public function newAction(Request $request)
    {
        $etc = new Etc();
        if ($_POST){
            $title = $_POST['title'];
            $main = $_POST['main'];
            $slug = $etc->slug($title);

            $cat = new Categories();
            $cat->setTitle($title);
            $cat->setMain($main);
            $cat->setSlug($slug);
            $cat->setSort(0);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($cat);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            unset($data);
            $sort = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($main);
            $i = 1;
            if($sort){
                if(is_array($sort)) {
                    foreach ($sort as $key => $value) {
                        $em = $this->getDoctrine()->getManager();
                        $sortCat = $em->getRepository('AppBundle:Categories')->find($value->getId());
                        $sortCat->setSort($i);
                        $em->flush();
                        $i++;
                    }
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $sortCat = $em->getRepository('AppBundle:Categories')->find($cat->getId());
                    $sortCat->setSort(2);
                    $em->flush();
                }
            }

        }


        $val = array();
        $item = array();
        $val['val']['process'] = 'new';

        $cats = $this->getDoctrine()->getRepository('AppBundle:Categories');
        $cats = $cats->findByMain(0);
        if($cats){
            if (is_array($cats)) {
                foreach ($cats as $key => $value) {
                    $item[$key]['id'] = $value->getId();
                    $item[$key]['title'] = $value->getTitle();
                }
            } else {
                $item[0]['id'] = $cats->getId();
                $item[0]['title'] = $cats->getTitle();
            }
        }
        $val['val']['cats'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/category.html.twig',$val);
    }

    /**
    * Matches /admin/categories/edit
     * @Route("/admin/categories/edit", name="categories_edit")
     */
    public function editAction(Request $request)
    {
        $id = $_GET['id'];
        $etc = new Etc();
        if ($_POST){
            $title = $_POST['title'];
            $main = $_POST['main'];
            $slug = $etc->slug($title);

            $em = $this->getDoctrine()->getManager();
            $cat = $em->getRepository('AppBundle:Categories')->find($id);
            $cat->setTitle($title);
            $cat->setMain($main);
            $cat->setSlug($slug);
            $em->flush();

            $sort = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($main);
            $i = 1;
            if($sort){
                if(is_array($sort)) {
                    foreach ($sort as $key => $value) {
                        $em = $this->getDoctrine()->getManager();
                        $sortCat = $em->getRepository('AppBundle:Categories')->find($value->getId());
                        $sortCat->setSort($i);
                        $em->flush();
                        $i++;
                    }
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $sortCat = $em->getRepository('AppBundle:Categories')->find($cat->getId());
                    $sortCat->setSort(2);
                    $em->flush();
                }
            }

        }


        $val = array();
        $item = array();
        $val['val']['process'] = 'edit';

        $value = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($id);
        $item['id'] = $value->getId();
        $item['title'] = $value->getTitle();
        $item['main'] = $value->getMain();
        $val['val']['item'] = $item;

        $subCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item['id']);
        $item = array();
        foreach ($subCats as $key => $value) {
            $item[$key]['id'] = $value->getId();
            $item[$key]['title'] = $value->getTitle();
            $item[$key]['main'] = $value->getMain();
            $item[$key]['slug'] = $value->getSlug();
            $item[$key]['sort'] = $value->getSort();
            $item[$key]['relatedContents'] = count($this->getDoctrine()->getRepository('AppBundle:Contents')->findByCatId($item[$key]['id']));
        }
        $val['val']['items'] = $item;

        $item = array();
        $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if($cats){
            if (is_array($cats)) {
                foreach ($cats as $key => $value) {
                    $item[$key]['id'] = $value->getId();
                    $item[$key]['title'] = $value->getTitle();
                }
            } else {
                $item[0]['id'] = $cats->getId();
                $item[0]['title'] = $cats->getTitle();
            }
        }
        $val['val']['cats'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/category.html.twig',$val);
    }

    /**
     * Matches /admin/categories/delete
     * @Route("/admin/categories/delete", name="categories_delete")
     */
    public function deleteAction(Request $request)
    {
        $id = $_GET['id'];
        $main = $_GET['main'];
        unset($data);
        $reassign = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($main);
        if(!is_array($reassign)) $reassign[0] = $reassign;
        $i = 1;
        if($reassign){
            foreach ($reassign as $key => $value) {
                $em = $this->getDoctrine()->getManager();
                $assignCat = $em->getRepository('AppBundle:Categories')->find($value->getId());
                $assignCat->setMain(0);
                $em->flush();
                $i++;
            }
        }
        $em = $this->getDoctrine()->getManager();
        $delete = $em->getRepository('AppBundle:Categories')->find($id);

        $em->remove($delete);
        $em->flush();

        return $this->redirectToRoute('categories_list');

    }
}
