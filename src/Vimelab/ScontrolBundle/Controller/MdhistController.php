<?php

namespace Vimelab\ScontrolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vimelab\ScontrolBundle\Entity\Mdhist;
use Vimelab\ScontrolBundle\Form\MdhistType;
use Vimelab\ScontrolBundle\Tool\Tool;

/**
 * Mdhist controller.
 *
 * @Route("/mdhist")
 */
class MdhistController extends Controller
{
    /**
     * Lists all Mdhist entities.
     *
     * @Route("/", name="mdhist")
     * @Template()
     */
    public function indexAction($pag)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();
			
			$pages = $em->getRepository('ScontrolBundle:Mdhist')->getCountPages(20);
			$pag = $pag < 1 ? 1 : $pag;
			$pag = $pag > $pages ? $pages: $pag;
			
			$entities = $em->getRepository('ScontrolBundle:Mdhist')->getPage(20, $pag);

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
            $repo = $em->getRepository('ScontrolBundle:Mdhist');
            $entities = $repo->getFilter($param);

            return $this->render("ScontrolBundle:Mdhist:index.html.twig", array('entities' => $entities, 'pages' => 1, 'pag' => 1));
        }
        else
            return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Finds and displays a Mdhist entity.
     *
     * @Route("/{id}/show", name="mdhist_show")
     * @Template()
     */
    public function showAction($id)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();

			$entity = $em->getRepository('ScontrolBundle:Mdhist')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Mdhist entity.');
			}

			$deleteForm = $this->createDeleteForm($id);

			return array(
				'entity'      => $entity,
				'delete_form' => $deleteForm->createView(),        );
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Displays a form to create a new Mdhist entity.
     *
     * @Route("/new", name="mdhist_new")
     * @Template()
     */
    public function newAction()
    {
		if(Tool::isGrant($this))
		{
			$entity = new Mdhist();
			$form   = $this->createForm(new MdhistType(), $entity);

			return array(
				'entity' => $entity,
				'form'   => $form->createView()
			);
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Creates a new Mdhist entity.
     *
     * @Route("/create", name="mdhist_create")
     * @Method("post")
     * @Template("ScontrolBundle:Mdhist:new.html.twig")
     */
    public function createAction()
    {
		if(Tool::isGrant($this))
		{
			$entity  = new Mdhist();
			$request = $this->getRequest();
			$form    = $this->createForm(new MdhistType(), $entity);
			$form->bindRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($entity);
				$em->flush();

				Tool::logger($this, $entity->getId());
				return $this->redirect($this->generateUrl('mdhist_show', array('id' => $entity->getId())));
				
			}

			return array(
				'entity' => $entity,
				'form'   => $form->createView()
			);
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    /**
     * Displays a form to edit an existing Mdhist entity.
     *
     * @Route("/{id}/edit", name="mdhist_edit")
     * @Template()
     */
    public function editAction($id)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();

			$entity = $em->getRepository('ScontrolBundle:Mdhist')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Mdhist entity.');
			}

			$editForm = $this->createForm(new MdhistType(), $entity);
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
     * Edits an existing Mdhist entity.
     *
     * @Route("/{id}/update", name="mdhist_update")
     * @Method("post")
     * @Template("ScontrolBundle:Mdhist:edit.html.twig")
     */
    public function updateAction($id)
    {
		if(Tool::isGrant($this))
		{
			$em = $this->getDoctrine()->getEntityManager();

			$entity = $em->getRepository('ScontrolBundle:Mdhist')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Mdhist entity.');
			}

			$editForm   = $this->createForm(new MdhistType(), $entity);
			$deleteForm = $this->createDeleteForm($id);

			$request = $this->getRequest();

			$editForm->bindRequest($request);

			if ($editForm->isValid()) {
				$em->persist($entity);
				$em->flush();

				Tool::logger($this, $entity->getId());
				return $this->redirect($this->generateUrl('mdhist_show', array('id' => $id)));
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
     * Deletes a Mdhist entity.
     *
     * @Route("/{id}/delete", name="mdhist_delete")
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
					$entity = $em->getRepository('ScontrolBundle:Mdhist')->find($id);
	
					if (!$entity) {
						throw $this->createNotFoundException('Unable to find Mdhist entity.');
					}
	
					$em->remove($entity);
					$em->flush();
					
					Tool::logger($this, $id);
				}
	
				return $this->redirect($this->generateUrl('mdhist'));
			}
			catch(\Exception $ex)
			{
				$sesion = $this->getRequest()->getSession();
				$sesion->setFlash('MsgVar', 'Imposible Borrar esta entidad, integridad referencial!');
				
				return $this->redirect($this->generateUrl('mdhist_edit', array('id' => $id)));
			}
		}else
			return $this->render("ScontrolBundle::alertas.html.twig");
    }

    public function reportAction($id)
    {
    	if(Tool::isGrant($this))
		{

			$em = $this->getDoctrine()->getEntityManager();
			$entity = $em->getRepository('ScontrolBundle:Mdhist')->find($id);

			$pdf = new \Tcpdf_Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('Vimelab');
			$pdf->SetTitle('INFORME DE RECONOCIMIENTO MÉDICO');
			$pdf->SetSubject('R. Médica');
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(20, 35, 20);
			$pdf->SetHeaderMargin(2);
			$pdf->SetFooterMargin(15);
			$pdf->SetAutoPageBreak(TRUE, 21);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			$pdf->setRevi(true);
			$pdf->setEntity($entity);

			$pdf->AddPage();			
			$pdf->AddPage();			
			
			$pdf->SetFont('dejavusans', '', 10);
		
			$pdf->Output('contrato No.pdf', 'I');	
		}
		else
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
