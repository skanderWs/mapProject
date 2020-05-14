<?php
/**
 * Created by PhpStorm.
 * Date: 2019-11-18
 * Time: 12:23
 */

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends MainController
{

    /**
     * @Route("/customerById/{id}")
     */
    public function getCustById($id)
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        return $this->render('getid.html.twig', compact('customer'));
    }

    /**
     * @Route("/customers")
     */
    public function index()
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
        return $this->render('base.html.twig', compact('customers'));
    }

    /**
     * @Route("/customer/getAll")
     * @return Response
     * @throws \Exception
     */
    public function getCustomers()
    {
        $users = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
        return $this->createResponse($users);
    }

    /**
     * @Route("/customer/getById")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function getCustomerById(Request $request)
    {
        $id = $request->request->get('id_user');
        $user = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        return $this->createResponse($user);
    }

}