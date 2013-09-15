<?php
namespace Application\Model;


use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\I18n\Validator\Alnum;

/**
 * Represents the information passed in a message
 * 
 * @link http://packages.zendframework.com/docs/latest/manual/en/user-guide/database-and-models.html#the-model-files 
 * @link http://framework.zend.com/apidoc/2.0/classes/Zend.InputFilter.InputFilterAwareInterface.html
 */
class MessageInfo implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $email;
    public $message;

    protected $inputFilter;

    /**
     * Zend\Stdlib\Hydrator\ArraySerializable method
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id']: null;
        $this->name = (isset($data['name'])) ? $data['name']: null;
        $this->email = (isset($data['email'])) ? $data['email']: null;
        $this->message = (isset($data['question'])) ? $data['question']: null;
    }
    
    /**
     * Zend\Stdlib\Hydrator\ArraySerializable method
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Contract method
     *
     * Could also be implemented using: 
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Tsk, tsk; you know were not using that!');
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory(); // alias for Zend\InputFilter\Factory

            $inputFilter->add(array(
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    array('name' => 'not_empty'),
                    array(
                        'name' => 'string_length',
                        'options' => array(
                            'min' => 5
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    array('name' => 'strip_tags'),
                    array('name' => 'string_trim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'not_empty',
                        'break_chain_on_failure' => true, // when >1 validator
                        'options' => array(
                            'messages' => array('isEmpty' => 'Email address is required.'),
                        ),
                    ),
                    array(
                        'name' => 'email_address',
                        'options' => array(
                            'messages' => array('emailAddressInvalidFormat' => 'Please enter a valid email address.'),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'message',
                'required' => true,
                'filters' => array(
                    array('name' => 'strip_tags'),
                    array('name' => 'string_trim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'alnum',
                        'options' => array(
                            'messages' => array('notAlnum' => 'What is that? We only allow Aa thru Zz and the digits 0 thru 9.'),
                        ),
                    ),
                    array(
                        'name' => 'not_empty',
                        'options' => array(
                            'messages' => array('isEmpty' => 'Message must exist'),
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'submit',
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
