<?php

class ToolsController extends Zend_Controller_Action
{
    public function xpathAction()
    {
        $tidy = new Tidy;
        $tidy->parseString($this->getParam('doc'), array(), 'utf8');
        $tidy->cleanRepair();

        $dom = new Zend_Dom_Query;
        $dom->setDocumentXml($tidy->html()->value, 'utf-8');

        if ($this->getParam('xpath')) {
            $res = $dom->queryXpath($this->getParam('xpath'));

            $result = null;
            foreach ($res as $r) {
                $result[] = $r->C14N();
            }

            $this->view->result = $result;
        }

        $this->view->params = $this->getAllParams();
    }
}