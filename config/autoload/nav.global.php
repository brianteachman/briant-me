<?php

return array(
    'service_manager' => array(
        'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <-- add this
         ),
    ),
    'navigation' => array(
        'default' => array(
            // array(
            //     'label' => 'Home',
            //     'route' => 'home',
            // ),
            // array(
            //     'label' => 'About',
            //     'route' => 'about',
            // ),
            // array(
            //     'label' => 'Services',
            //     'route' => 'services',
            // ),

            array(
                'label' => 'FAQ',
                'route' => 'faq',
            ),
            // array(
            //     'label' => 'Pricing',
            //     'route' => 'pricing',
            // ),
            array(
                'label' => 'Contact',
                'route' => 'contact',
            ),
        ),
    ),
);