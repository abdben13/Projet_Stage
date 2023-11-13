<?php
namespace App\Validators;

class MarqueValidator extends AbstractValidator {
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['name', 'slug']);
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200);
        $this->validator->rule('slug', 'slug');
    }
}