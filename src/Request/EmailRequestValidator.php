<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailRequestValidator
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $startDate
     * 
     * @return Collection
     */
    public function rules(): Collection
    {
        return new Assert\Collection([ 
            'period' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 10, 'min' => 5]),
                new Assert\Type(['type' => 'string']),
            ],
            'date_range' => [
                new Assert\NotBlank(),
                new Assert\Type('array'),
                new Assert\Count(min:2),
                new Assert\Collection([
                    'start' => [
                        new Assert\NotBlank(),
                        new Assert\DateTime([
                            'format' => 'Y-m-d H:i:s',
                        ]),
                    ],
                    'end' => [
                        new Assert\NotBlank(),
                        new Assert\DateTime([
                            'format' => 'Y-m-d H:i:s',
                        ]),
                        new GreaterThan([
                            'value' => 'start',
                        ]),
                    ],
                ]),
            ],
        ]);
    }
}