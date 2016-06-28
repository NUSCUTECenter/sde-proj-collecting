<?php
namespace Collecting\Controller\Admin;

use Collecting\Form\CollectingForm;
use Omeka\Mvc\Exception;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $site = $this->currentSite();
        $collectingForms = $this->api()
            ->search('collecting_forms', ['site_id' => $site->id()])->getContent();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('collectingForms', $collectingForms);
        return $view;
    }

    public function adminAction()
    {
        $site = $this->currentSite();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        return $view;
    }

    public function addAction()
    {
        $site = $this->currentSite();
        $form = $this->getForm(CollectingForm::class);

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('form', $form);
        return $view;
    }

    public function editAction()
    {
        $site = $this->currentSite();
        $collectingForm = $this->api()
            ->read('collecting_forms', $this->params('id'))->getContent();
        $form = $this->getForm(CollectingForm::class);
        $form->setData($collectingForm->jsonSerialize());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                var_dump($data);exit;
            } else {
                $this->messenger()->addError('There was an error during validation');
            }
        }

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('form', $form);
        $view->setVariable('collectingForm', $collectingForm);
        return $view;
    }
}

