<?php

namespace Vimelab\ScontrolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vimelab\ScontrolBundle\Entity\Mdvisu;
use Vimelab\ScontrolBundle\Form\MdvisuType;
use Vimelab\ScontrolBundle\Tool\Tool;

/**
 * Mdvisu controller.
 *
 * @Route("/mdvisu")
 */
class MdvisuController extends Controller
{
    /**
     * Lists all Mdvisu entities.
     *
     * @Route("/", name="mdvisu")
     * @Template()
     */
    public function indexAction($pag)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();
			
			$pages = $em->getRepository('ScontrolBundle:Mdvisu')->getCountPages(20);
			$pag = $pag < 1 ? 1 : $pag;
			$pag = $pag > $pages ? $pages: $pag;
			
			$entities = $em->getRepository('ScontrolBundle:Mdvisu')->getPage(20, $pag);

			return array('entities' => $entities, 'pages' => $pages, 'pag' => $pag);
		}
		else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }
    
	public function filterAction($param = '')
    {
        if(Tool::isGrant($this))
        {
            $em = $this->getDoctrine()->getEntityManager();
            $repo = $em->getRepository('ScontrolBundle:Mdvisu');
            $entities = $repo->getFilter($param);

            return $this->render("ScontrolBundle:Mdvisu:index.html.twig", array('entities' => $entities, 'pages' => 1, 'pag' => 1));
        }
        else
            return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Finds and displays a Mdvisu entity.
     *
     * @Route("/{id}/show", name="mdvisu_show")
     * @Template()
     */
    public function showAction($id, $lv)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$entity = $em->getRepository('ScontrolBundle:Mdvisu')->find($id);
			
			if($lv == 1)
			{
				if (!$entity) 
				{
					throw $this->createNotFoundException('Unable to find Mdvisu entity.');
				}

				$deleteForm = $this->createDeleteForm($id);

				return array('entity' => $entity, 'delete_form' => $deleteForm->createView());
			}
			else if($lv == 2)
				return $this->render("ScontrolBundle:Mdvisu:_show.html.twig", array('entity' => $entity, 'RMSG' => $entity->getId()."-A. Visual creada con exito!"));
			else if($lv == 4)
				return $this->render("ScontrolBundle:Mdvisu:_show.html.twig", array('entity' => $entity, 'RMSG' => 'NONE'));
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Displays a form to create a new Mdvisu entity.
     *
     * @Route("/new", name="mdvisu_new")
     * @Template()
     */
    public function newAction($lv)
    {
		if(Tool::isGrant($this))
		{
			$entity = new Mdvisu();
			$form   = $this->createForm(new MdvisuType(), $entity);

			if($lv == 1)
				return array('entity' => $entity, 'form'   => $form->createView());
			else
				return $this->render("ScontrolBundle:Mdvisu:_new.html.twig", array('entity' => $entity, 'form'   => $form->createView(), 'RMSG' => 'LOAD'));
		}
		else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Creates a new Mdvisu entity.
     *
     * @Route("/create", name="mdvisu_create")
     * @Method("post")
     * @Template("ScontrolBundle:Mdvisu:new.html.twig")
     */
    public function createAction($lv)
    {
		if(Tool::isGrant($this))
		{
			$entity  = new Mdvisu();
			$request = $this->getRequest();
			$form    = $this->createForm(new MdvisuType(), $entity);
			$form->bindRequest($request);

			if ($form->isValid()) 
			{
				try
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($entity);
					$em->flush();
	
					Tool::logger($this, $entity->getId());
					
					if($lv == 1)
						return $this->redirect($this->generateUrl('mdvisu_show', array('id' => $entity->getId())));
					else
						return $this->redirect($this->generateUrl('mdvisu_show', array('id' => $entity->getId(), 'lv' => '2')));
					
				}
				catch(\Exception $e)
				{
					if($lv == 1)
						return array('entity' => $entity, 'form'   => $form->createView());
					else	
						return $this->render("ScontrolBundle:Mdvisu:_new.html.twig", array('entity' => $entity, 'form'   => $form->createView(), 'RMSG' => '1-Imposible Crear A. Visual, Error Referencial!'));
				}
				
			}

			if($lv == 1)
				return array('entity' => $entity, 'form'   => $form->createView());
			else
				return $this->render("ScontrolBundle:Mdvisu:_new.html.twig", array('entity' => $entity, 'form'   => $form->createView(), 'RMSG' => 'LOAD'));
		}
		else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Displays a form to edit an existing Mdvisu entity.
     *
     * @Route("/{id}/edit", name="mdvisu_edit")
     * @Template()
     */
    public function editAction($id)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();

			$entity = $em->getRepository('ScontrolBundle:Mdvisu')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Mdvisu entity.');
			}

			$editForm = $this->createForm(new MdvisuType(), $entity);
			$deleteForm = $this->createDeleteForm($id);

			return array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
			);
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Edits an existing Mdvisu entity.
     *
     * @Route("/{id}/update", name="mdvisu_update")
     * @Method("post")
     * @Template("ScontrolBundle:Mdvisu:edit.html.twig")
     */
    public function updateAction($id)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();

			$entity = $em->getRepository('ScontrolBundle:Mdvisu')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Mdvisu entity.');
			}

			$editForm   = $this->createForm(new MdvisuType(), $entity);
			$deleteForm = $this->createDeleteForm($id);

			$request = $this->getRequest();

			$editForm->bindRequest($request);

			if ($editForm->isValid()) {
				$em->persist($entity);
				$em->flush();
				
				Tool::logger($this, $entity->getId());
				return $this->redirect($this->generateUrl('mdvisu_show', array('id' => $id)));
			}

			return array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
			);
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Deletes a Mdvisu entity.
     *
     * @Route("/{id}/delete", name="mdvisu_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
		if(Tool::isGrant($this))
		{
			try
			{
				$form = $this->createDeleteForm($id);
				$request = $this->getRequest();
	
				$form->bindRequest($request);
	
				if ($form->isValid()) {
					$em = $this->getDoctrine()->getEntityManager();
					$entity = $em->getRepository('ScontrolBundle:Mdvisu')->find($id);
	
					if (!$entity) {
						throw $this->createNotFoundException('Unable to find Mdvisu entity.');
					}
	
					$em->remove($entity);
					$em->flush();
					
					Tool::logger($this, $id);
				}
				
				return $this->redirect($this->generateUrl('mdvisu'));
			}
			catch(\Exception $ex)
			{
				$sesion = $this->getRequest()->getSession();
				$sesion->setFlash('MsgVar', 'Imposible Borrar esta entidad, integridad referencial!');
				
				return $this->redirect($this->generateUrl('mdvisu_edit', array('id' => $id)));
			}
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
