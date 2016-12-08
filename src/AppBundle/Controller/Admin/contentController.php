<?php
namespace AppBundle\Controller\Admin;

use AppBundle\Utils\Classes\Etc;
use AppBundle\Entity\Categories;
use AppBundle\Entity\Contents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class contentController extends Controller
{
    /**
     * Matches /admin/contents exactly
     * @Route("/admin/contents", name="contents_list")
     */
    public function indexAction(Request $request)
    {
        $val = array();
        $item = array();
        $subs = '';
        $val['val']['process'] = 'default';

        $allContents = $this->getDoctrine()->getRepository('AppBundle:Contents')->findAll();
        if (!is_array($allContents)) $allContents[0] = $allContents;
        if($allContents){
            foreach ($allContents as $key => $value) {
                $item[$key]['id'] = $value->getId();
                $item[$key]['title'] = $value->getTitle();
                $item[$key]['content'] = substr(strip_tags($value->getContent()),0,100);
                $item[$key]['slug'] = $value->getSlug();
                $item[$key]['date'] = $value->getDate();
                $item[$key]['catId'] = $value->getCatId();

                $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($item[$key]['catId']);
                if($cats){
                    $item[$key]['cat'] = $cats->getTitle();
                }

            }
        }
        $val['val']['items'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/content.html.twig',$val);
    }

    /**
    * Matches /admin/contents/new
     * @Route("/admin/contents/new", name="contents_new")
     */
    public function newAction(Request $request)
    {
        $etc = new Etc();
        if ($_POST){
            $title = $_POST['title'];
            $catid = $_POST['cat_id'];
            $content = $_POST['content'];
            $date = new \DateTime("now");//date('Y-m-d h:i:s');
            $slug = $etc->slug($title);

            $cont = new Contents();
            $cont->setTitle($title);
            $cont->setCatId($catid);
            $cont->setContent($content);
            $cont->setDate($date);
            $cont->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($cont);
            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

        }


        $val = array();
        $item = array();
        $val['val']['process'] = 'new';

        $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($cats)) $cats[0] = $cats;
        if($cats){
            foreach ($cats as $key => $value) {
                $item[$key]['id'] = $value->getId();
                $item[$key]['title'] = $value->getTitle();
                $subcats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
                if (!is_array($subcats)) $subcats[0] = $subcats;
                foreach ($subcats as $k => $v) {
                    $item[$key]['subcats'][$k]['id'] = $v->getId();
                    $item[$key]['subcats'][$k]['title'] = $v->getTitle();
                }
            }
        }
        $val['val']['cats'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/content.html.twig',$val);
    }

    /**
    * Matches /admin/contents/edit
     * @Route("/admin/contents/edit", name="content_edit")
     */
    public function editAction(Request $request)
    {
        $id = $_GET['id'];
        $etc = new Etc();
        if ($_POST){
            $title = $_POST['title'];
            $catid = $_POST['cat_id'];
            $content = $_POST['content'];
            $slug = $etc->slug($title);

            $em = $this->getDoctrine()->getManager();
            $cont = $em->getRepository('AppBundle:Contents')->find($id);
            $cont->setTitle($title);
            $cont->setCatId($catid);
            $cont->setContent($content);
            $cont->setSlug($slug);
            $em->flush();

        }


        $val = array();
        $item = array();
        $val['val']['process'] = 'edit';

        $value = $this->getDoctrine()->getRepository('AppBundle:Contents')->find($id);
        $item['id'] = $value->getId();
        $item['title'] = $value->getTitle();
        $item['catid'] = $value->getCatId();
        $item['content'] = $value->getContent();
        $val['val']['item'] = $item;

        $item = array();
        $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($cats)) $cats[0] = $cats;
        if($cats){
            foreach ($cats as $key => $value) {
                $item[$key]['id'] = $value->getId();
                $item[$key]['title'] = $value->getTitle();
                $subcats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
                if (!is_array($subcats)) $subcats[0] = $subcats;
                foreach ($subcats as $k => $v) {
                    $item[$key]['subcats'][$k]['id'] = $v->getId();
                    $item[$key]['subcats'][$k]['title'] = $v->getTitle();
                }
            }
        }
        $val['val']['cats'] = $item;

        // replace this example code with whatever you need
        return $this->render('Admin/content.html.twig',$val);
    }

    /**
     * Matches /admin/contents/delete
     * @Route("/admin/contents/delete", name="contents_delete")
     */
    public function deleteAction(Request $request)
    {
        $id = $_GET['id'];
        $em = $this->getDoctrine()->getManager();
        $delete = $em->getRepository('AppBundle:Contents')->find($id);

        $em->remove($delete);
        $em->flush();

        return $this->redirectToRoute('contents_list');

    }
}
