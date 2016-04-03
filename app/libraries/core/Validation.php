<?php

/**
 * Class \Ecne\Classes\Validation
 * @author John O'Grady
 * @date 06/07/15
 */

namespace Ecne\Library\Core;

use Ecne\Core\Model;
use Ecne\Form\Form;

class Validation
{
    /**
     * @var bool
     */
    private $passed = false;
    /**
     * @var array
     */
    private $errors = array();
    /**
     * @var \Ecne\Form\Form
     */
    private $form;

    /**
     * @method check
     * @access public
     * @param Form $form
     * @return $this
     */
    public function check(Form $form)
    {
        $this->form = $form;
        if (Input::secure()) {
            foreach ($form->getElements() as $element) {
                if ($element->isUnique() !== 'false' && preg_match('/|/', $element->isUnique())) {
                    $criteria = explode('|', $element->isUnique());
                    $check = Model::select()->eq($criteria[0], Input::cleanUserInput(Input::get($element->getHTMLAttributesArray()['name'])))->all();
                    if (($check)) {
                        $this->addError($element->getHTMLAttributesArray()['name'], Input::cleanUserInput(Input::get($element->getHTMLAttributesArray('name'))) . " already exists");
                        $element->setShortMessage(Input::cleanUserInput(Input::get($element->getHTMLAttributesArray('name'))) . " already exists");
                    }
                }
                foreach ($element->getAttributesArray() as $attr => $value) {
                    switch ($attr) {
                        case 'lt':
                            if (!(strlen(Input::get($element->getAttribute('name'))) < (int)$value)) {
                                $this->addError($element->getAttribute('name'), 'too small');
                                $element->setShortMessage('not enough characters');
                            }
                            break;
                        case 'lte':
                            if (!(strlen(Input::get($element->getAttribute('name'))) <= (int)$value)) {
                                $this->addError($element->getAttribute('name'), 'too small');
                                $element->setShortMessage('not enough characters');
                            }
                            break;
                        case 'gt':
                            if (!(strlen(Input::get($element->getAttribute('name'))) > (int)$value)) {
                                $this->addError($element->getAttribute('name'), 'too small');
                                $element->setShortMessage('not enough characters');
                            }
                            break;
                        case 'gte':
                            if (!(strlen(Input::get($element->getAttribute('name'))) >= (int)$value)) {
                                $this->addError($element->getAttribute('name'), 'too small');
                                $element->setShortMessage('not enough characters');
                            }
                            break;
                        case 'eq':
                            break;
                        default:
                            break;
                    }
                    if ($element->getShortMessage()) {
                        $element->setAttribute('value', Input::get(Input::cleanUserInput($element->getAttribute('name'))));
                    }
                }
            }
        } else {
            $this->addError('global-warning', "Error in request");
        }
        if (count($this->errors)) {
            $this->passed = false;
        } else {
            $this->passed = true;
        }
    }

    /**
     * @param Form $form
     * @param $errors
     */
    public function checkFormErrors(Form $form, $errors)
    {
        foreach ($form->getElements() as $element) {
            if (isset($errors[$element->getAttribute('name')])) {
                $element->addClass(Config::get('validation/error-class'));
            }
        }
    }

    /**
     * @method addError
     * @access private
     * @param $name
     * @param $error
     */
    private function addError($name, $error)
    {
        $this->errors[$name] = $error;
    }

    /**
     * @method errors
     * @access public
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @method passed
     * @access public
     * @return bool
     */
    public function passed()
    {
        return $this->passed;
    }
} #End Class Definition