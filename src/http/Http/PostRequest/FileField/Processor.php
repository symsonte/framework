<?php

namespace Symsonte\Http\PostRequest\FileField;

use Symsonte\Http\PostRequest\FileField;

/**
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class Processor
{
    /**
     * @param FileField   $field
     * @param string|null $filename
     *
     * @return string The full path where the file was moved
     */
    public function process(FileField $field, $filename = null)
    {
        $filename = $filename ?: sprintf(
            '%s/%s.%s',
            sys_get_temp_dir(),
            uniqid(),
            pathinfo($field->getFile(), PATHINFO_EXTENSION)
        );

        $result = @move_uploaded_file(
            $field->getFile(),
            $filename
        );

        if ($result === false) {
            throw new \InvalidArgumentException();
        }

        return $filename;
    }
}
