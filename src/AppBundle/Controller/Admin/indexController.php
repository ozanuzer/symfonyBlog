<?php
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Categories;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class indexController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $request)
    {
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Categories')
            ->findAll();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id 1'
            );
        }
        //echo $product->getId();
        //print_r($product);

        // replace this example code with whatever you need
        return $this->render('Admin/index.html.twig',
            array("val" => array("id" => 'deneme'))
        );
    }
}
