<?php

namespace Symsonte\Resource;

//use Symsonte\Validation\Validator\ExceptionValidator;
//use Symsonte\Validation\Validator\ArrayValidator;

abstract class AbstractDefinition implements Definition
{
    /**
     * @inheritdoc
     */
    public function import($data)
    {
        //$this->validate($data);

        if ($data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        $r = new \ReflectionClass($this);
        $properties = $r->getProperties();
        $export = [];
        foreach ($properties as $property) {
            if (null !== $property->getValue($this)) {
                $export[$property->name] = $property->getValue($this);
            }
        }

        return $export;
    }

//    /**
//     * @param array $data
//     * @return void
//     */
//    private function validate($data)
//    {
//        $r = new \ReflectionClass($this);
//        $properties = $r->getProperties();
//        $allowedKeys = [];
//        foreach ($properties as $property) {
//            $allowedKeys[] = $property->name;
//        }
//
//        $validator = new ExceptionValidator(new ArrayValidator(array(
//            'allowedKeys' => $allowedKeys,
//            'allowExtra' => false
//        )));
//
//        $validator->validate($data);
//    }
}
