<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $usuario =new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            //$form['password']->getData() me trae los datos cargados en el formulario !!!
            $usuario->setPassword($passwordEncoder->encodePassword($usuario, $form['password']->getData()));
            $em->persist($usuario);
            $em->flush();
            $this->addFlash('exito', Usuario::REGISTRO_EXITOSO);
            return $this->redirectToRoute('registro');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'Hola Mundo Sebastian',
            'formulario' => $form->createView()
        ]);
    }

    /**
     * @Route("/editarReg/{id}", name="editarReg")
     */
    public function editar(Usuario $usuario, Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $form = $this->createForm(UsuarioType::class, $usuario);
        $em=$this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->find($usuario->getId());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $usuario->setUsername($form->get('username')->getData());
            $usuario->setPassword($passwordEncoder->encodePassword($usuario, $form->get('password')->getData()));
            $em->flush();

            return $this->redirectToRoute('pagprincipal');
        }
        return $this->render('registro/editar.html.twig', [
            'formulario' => $form->createView()
        ]);
    }

    /**
     * @Route("/editarPass/{id}", name="editarPass")
     */
    public function editarPassword(Usuario $usuario, Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $form = $this->createFormBuilder()
        ->add('password', PasswordType::class)
        ->add('new_password', PasswordType::class)
        ->add('Actualizar', SubmitType::class)
        ->getForm();

        $em=$this->getDoctrine()->getManager();
        $usuario = $em->getRepository(Usuario::class)->find($usuario->getId());
        $form->handleRequest($request);
//
        if($form->isSubmitted() && $form->isValid()){
            //$usuario->setPassword($form->get('password')->getData());
            $passUno = $form['password']->getData();
            $passDos = $form['new_password']->getData();

            if($passUno == $passDos){
                $usuario->setPassword($passwordEncoder->encodePassword($usuario, $form->get('new_password')->getData()));
                $em->flush();
                $this->addFlash('mensaje', Usuario::REGISTRO_EXITOSO);
                return $this->redirectToRoute('editarPass', ['id'=> $usuario->getId()]);
            }else{
                $this->addFlash('mensaje', Usuario::REGISTRO_INVALIDO);
                return $this->redirectToRoute('editarPass', ['id'=> $usuario->getId()]);
            }

        }
        return $this->render('registro/editarPassword.html.twig', [
            'formulario' => $form->createView(),
            'usuario' => $usuario
        ]);
    }
}
