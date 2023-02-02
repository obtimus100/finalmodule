<?php


if(!defined('_PS_VERSION_'))
{
    exit;
}

class FinaleModule extends Module {

    public function __construct()
    {
        $this->name = 'finalmodule';
        $this->tab ='front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->versions_compliancy = [
            'min' => 1.6,
            _PS_VERSION_
        ];
        parent::__construct();
        $this->displayName = 'finale module';
        $this->description = 'le meilleur module du monde';

    }

    public function install()
    {
        // crée deux variables de configuration

        if(!parent::install() ||
        !Configuration::updateValue('ANNEES', '1993') ||
        !Configuration::updateValeur('MOIS', 'decembre'))
        {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if(!parent::uninstall() ||
        !Configuration::deleteByName('ANNEES') ||
        !Configuration::deleteByName('MOIS'))
        {
            return false;
        }
        return true;
    }

    public function getContent()
    {

        return $this->PostProcess().$this->renderForm();
    }

    public function renderForm()
    {
            $fieldsForm[0]['form'] = [
                'legend' => [
                    'title' => 'settings'
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => 'annees',
                        'name' => 'ANNEES',
                        'required' => true

                    ],
                    [
                        'type' => 'text',
                        'label' => 'mois',
                        'name' => 'MOIS',
                        'required' => true
                    ]
                    ],
                    'submit' => [
                        'title' => 'valider',
                        'class' => 'btn btn-primary',
                        'name' => 'save'
                    ]
            ];

            $helper = new HelperForm();
            $helper->module = $this;
            $helper->name_controller = $this->name;
            $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            // $helper->fields_value recupère la value du champ.
            $helper->fields_value['ANNEES'] = Configuration::get('ANNEES');
            $helper->fields_value['MOIS'] = Configuration::get('MOIS');
            
            
            return $helper->generateForm($fieldsForm);


    }

    public function PostProcess()
    {
            if(Tools::isSubmit('save')){

            // les champs vide.

            $annees = Tools::getValue('ANNEES');
            $mois = Tools::getValue('MOIS');

            $errors =  false;

            if(empty($annees))
            {
                $this->displayError("l'année ne doit pas être vide");
                $errors = true;
            }

            if(empty($mois))
            {
                $this->displayError("le mois  ne doit pas être vide");
                $errors = true;
            }

            if(count($errors) < 1)
            {
                Configuration::updateValue('ANNEES', $annees);
                Configuration::updateValue('MOIS', $mois);

                $this->displayConfirmation('Les données ont été modifiés avec success');
            }


    }
}