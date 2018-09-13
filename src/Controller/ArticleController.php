<?php

namespace App\Controller;

use App\Entity\Article;

use phpDocumentor\Reflection\Types\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     * @Method("GET")
     */
    public function index()
    {
        $articles=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig',array(
            'articles' => $articles
        ));

    }

    /**
     * @Route("/article/new",name="new_article")
     * @Method({"GET","POST"})
     */
    public function new1(Request $request){
        $article = new Article();
        $form = $this->createFormBuilder($article)->add('title',TextType::class,array('attr' =>
        array('class' => 'form-control')))
            ->add('body',TextareaType::class,array(

                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save',SubmitType::class,array(

              'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $article = $form->getData();

           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($article);
           $entityManager->flush();
           return $this->redirectToRoute('article_list');


        }
        return $this->render('article/new.html.twig',array(
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/article/{id}",name="article_show")
     */

    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/show.html.twig',array('article' => $article));
    }
    /**
     * @Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request,$id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        $response = new Response();
        $response->send();

    }
//    /**
//     * @Route("/article/save")
//     */
//     public function save(){
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $article = new Article();
//        $article->setTitle('Article Two');
//        $article->setBody('Article two Body');
//        $entityManager->persist($article);
//        $entityManager->flush();
//        return new Response('saves article with the id of'.$article->getId());
//    }

}



