<?php

namespace AppBundle\Controller;

use AppBundle\Utils\Classes\Etc;
use AppBundle\Entity\Categories;
use AppBundle\Entity\Contents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
    * Matches /
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $val = array();
        $item = array();
        $subs = '';

        $allCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($allCats)) $allCats[0] = $allCats;
        foreach ($allCats as $key => $value) {
            $item[$key]['id'] = $value->getId();
            $item[$key]['title'] = $value->getTitle();
            $item[$key]['slug'] = $value->getSlug();

            $subCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
            if (!is_array($subCats)) $subCats[0] = $subCats;
            if($subCats){
                foreach ($subCats as $subkey => $subvalue) {
                    $subs[$subkey]['id'] = $value->getId();
                    $subs[$subkey]['title'] = $subvalue->getTitle();
                    $subs[$subkey]['slug'] = $subvalue->getSlug();
                }
                $item[$key]['subs'] = $subs;
            } else $item[$key]['subs'] = 0;
        }
        $val['val']['cats']['items'] = $item;

        $item = array();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Contents');
        $query = $repository->createQueryBuilder('c')
            ->setMaxResults(5)
            ->getQuery();

        $allContents = $query->getResult();
        if (!is_array($allContents)) $allContents[0] = $allContents;
        if($allContents){
            foreach ($allContents as $key => $value) {
                $item[$key]['id'] = $value->getId();
                $item[$key]['title'] = $value->getTitle();
                $item[$key]['content'] = substr(strip_tags($value->getContent()),0,250);
                $item[$key]['slug'] = $value->getSlug();
                $item[$key]['date'] = $value->getDate();
                $item[$key]['catId'] = $value->getCatId();

                $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($item[$key]['catId']);
                if($cats){
                    $item[$key]['cat']['title'] = $cats->getTitle();
                    $item[$key]['cat']['slug'] = $cats->getSlug();
                }

            }
        }
        $val['val']['contents']['items'] = $item;

        return $this->render('default/index.html.twig',$val);
    }

    /**
    * Matches /categories/*
     * @Route("/categories/{slug}", name="cats_list")
     */
    public function catAction($slug)
    {

        $val = array();
        $item = array();
        $subs = '';

        $cat = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBySlug($slug);
        if ($cat) {
            $id = $cat->getId();
            $item['id'] = $cat->getId();
            $item['title'] = $cat->getTitle();
            $item['slug'] = $cat->getSlug();
        }
        $val['val']['cat']['item'] = $item;

        $item = array();
        $allCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($allCats)) $allCats[0] = $allCats;
        foreach ($allCats as $key => $value) {
            $item[$key]['id'] = $value->getId();
            $item[$key]['title'] = $value->getTitle();
            $item[$key]['slug'] = $value->getSlug();

            $subCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
            if (!is_array($subCats)) $subCats[0] = $subCats;
            if($subCats){
                foreach ($subCats as $subkey => $subvalue) {
                    $subs[$subkey]['id'] = $value->getId();
                    $subs[$subkey]['title'] = $subvalue->getTitle();
                    $subs[$subkey]['slug'] = $subvalue->getSlug();
                }
                $item[$key]['subs'] = $subs;
            } else $item[$key]['subs'] = 0;
        }
        $val['val']['cats']['items'] = $item;

        $item = array();
        $allContents = $this->getDoctrine()->getRepository('AppBundle:Contents')->findByCatId($id);
        if (!is_array($allContents)) $allContents[0] = $allContents;
        if($allContents){
            foreach ($allContents as $key => $value) {
                $item[$key]['id'] = $value->getId();
                $item[$key]['title'] = $value->getTitle();
                $item[$key]['content'] = substr(strip_tags($value->getContent()),0,250);
                $item[$key]['slug'] = $value->getSlug();
                $item[$key]['date'] = $value->getDate();
                $item[$key]['catId'] = $value->getCatId();

                $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($item[$key]['catId']);
                if($cats){
                    $item[$key]['cat']['title'] = $cats->getTitle();
                    $item[$key]['cat']['slug'] = $cats->getSlug();
                }

            }
        }
        $val['val']['contents']['items'] = $item;

        return $this->render('default/categories.html.twig',$val);
    }

    /**
    * Matches /content/*
     * @Route("/content/{slug}", name="content_detail")
     */
    public function contentAction($slug)
    {

        $val = array();
        $item = array();
        $subs = '';

        $content = $this->getDoctrine()->getRepository('AppBundle:Contents')->findOneBySlug($slug);
        if ($content) {
            $id = $content->getId();
            $item['id'] = $content->getId();
            $item['title'] = $content->getTitle();
            $item['content'] = $content->getContent();
            $item['slug'] = $content->getSlug();
            $item['date'] = $content->getDate();
            $item['catId'] = $content->getCatId();

            $cats = $this->getDoctrine()->getRepository('AppBundle:Categories')->find($item['catId']);
            if($cats){
                $item['cat']['title'] = $cats->getTitle();
                $item['cat']['slug'] = $cats->getSlug();
            }
        }
        $val['val']['content']['item'] = $item;

        $item = array();
        $allCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain(0);
        if (!is_array($allCats)) $allCats[0] = $allCats;
        foreach ($allCats as $key => $value) {
            $item[$key]['id'] = $value->getId();
            $item[$key]['title'] = $value->getTitle();
            $item[$key]['slug'] = $value->getSlug();

            $subCats = $this->getDoctrine()->getRepository('AppBundle:Categories')->findByMain($item[$key]['id']);
            if (!is_array($subCats)) $subCats[0] = $subCats;
            if($subCats){
                foreach ($subCats as $subkey => $subvalue) {
                    $subs[$subkey]['id'] = $value->getId();
                    $subs[$subkey]['title'] = $subvalue->getTitle();
                    $subs[$subkey]['slug'] = $subvalue->getSlug();
                }
                $item[$key]['subs'] = $subs;
            } else $item[$key]['subs'] = 0;
        }
        $val['val']['cats']['items'] = $item;

        return $this->render('default/content.html.twig',$val);
    }
}
