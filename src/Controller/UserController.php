<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $passwordEncoder;

    public function __construct()
    {
        //$this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(UserRepository $repository): Response
    {
        return $this->render('user/index.html.twig', [
            'Usuarios' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $repository, Auth $auth): Response
    {
        $this->validateADMIN();

        $userSrv = new UserService(null, $repository, $auth);
        $userNew = new UserFireBase();
        $form = null;
        $error = null;
        try {
            //setValue from Form
            $form = $this->createForm(UserType::class, $userNew);
            $form->handleRequest($request);
            //$roles = $this->getParameter('security.role_hierarchy.roles');
            if ($form->isSubmitted() && $form->isValid()) {
                $userSrv->create($userNew);
                return $this->redirectToRoute('user_index');
            }
            return $this->render('user/new.html.twig', [
                'user' => $userNew,
                'form' => $form->createView(),
            ]);

        } catch (\Exception $e) {
            $error = $e->getMessage();
            return $this->render('user/new.html.twig', [
                'user' => $userNew,
                'form' => $form->createView(),
                'error' => $error,
            ]);
        }
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/updatePassword/{id?}", name="user_updatePassword", methods={"GET","POST"})
     */
    public function updatePassword(Auth $auth, UserRepository $repository): Response
    {
        $user = null;
        $form = $this->createForm(UserType::class, $user,['method' => 'POST']  );
        try {

            $password = $_REQUEST["password"];
            $password2 = $_REQUEST["password2"];
            if ($password != $password2)
                throw new \Exception("La clave no coincide.");
            if ( strlen($password) < 6 )
                throw new \Exception("La clave no peude ser menor de 6 carateres.");
            $userSrv = new UserService($_REQUEST["id_"], $repository, $auth);
            $user = $userSrv->getUser();
            //update form
            $form = $this->createForm(UserType::class, $user,['method' => 'POST']  );
            $userSrv->updatePassword($_REQUEST["id_"], $password);

        }catch (\Exception $e){
            $form->addError(new FormError($e->getMessage()));
        } finally {
            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function edit(Request $request, $id, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'teacher' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        $this->validateADMIN();

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/getprofile/{id?}", name="getProfile", methods={"GET", "POST"} )
     */
    public function getProfile(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/updatestatus/{id?}", name="updatestatus", methods={"GET", "POST"} )
     */
    public function updateStatus(Request $request, Auth $auth): Response
    {
        try {
            $us = $request->request->get("id");
            $newState = $request->request->get("st");
            $newState = $newState === 'true';
            //$password   = $request->attributes->get('password');
            if ( $newState )
                $updatedUser = $auth->enableUser( $us );
            else
                $updatedUser = $auth->disableUser( $us );

            return new JsonResponse("OK");
        }catch (\Exception $e){
            return new JsonResponse($e->getMessage(),500 );
        }
    }


    private function validateADMIN()
    {
        //validate access
        if( !$this->getUser()->getisAdmin())
            return $this->redirectToRoute('homepage');
    }

}
