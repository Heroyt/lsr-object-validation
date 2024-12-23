<?php
declare(strict_types=1);

namespace Lsr\ObjectValidation\Exceptions;

class ValidationMultiException extends ValidationException
{

    /**
     * @param  ValidationException[]  $exceptions
     */
    public function __construct(
        public readonly array $exceptions = [],
    ) {
       $messages = [];
        foreach ($this->exceptions as $exception) {
            if (!$exception instanceof ValidationException) {
                throw new \InvalidArgumentException('Argument #0 ($exceptions) of '.$this::class .' must be a list of \Lsr\ObjectValidation\ValidationException objects.');
            }
            $messages[] = $exception->getMessage();
        }

        parent::__construct(
            "Validation error:\n-".implode("\n-", $messages)
        );
    }

}