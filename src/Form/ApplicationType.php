<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {
    /**
     *  Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration(string $label = "", string $placeholder = "", array $options = []): array {
        $paramArray = [];
        if (!empty($label)) $paramArray["label"] = $label;
        if (!empty($placeholder)) $paramArray["attr"]["placeholder"] = $placeholder;

        return array_merge_recursive($paramArray, $options);
    }
}