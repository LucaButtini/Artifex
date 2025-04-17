<?php
return [
    // id_visitatore è autoincrement, non presente nel form di inserimento
    'nome'        => ['type' => 'text',     'label' => 'Nome'],
    'email'       => ['type' => 'email',    'label' => 'Email'],
    'nazionalita' => ['type' => 'text',     'label' => 'Nazionalità'],
    'telefono'    => ['type' => 'tel',      'label' => 'Telefono'],
    'lingua_base' => ['type' => 'text',     'label' => 'Lingua Base'],
    'password'    => ['type' => 'password', 'label' => 'Password'],
];