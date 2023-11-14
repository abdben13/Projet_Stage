<?php
namespace App\Validators;

class PostValidator extends AbstractValidator {
    public function __construct(array $data, array $marques)
    {
        parent::__construct($data);
        $this->validator->rule('required', ['name']);
        $this->validator->rule('lengthBetween', 'name', 3, 200);
        $this->validator->rule('required', 'content');
        $this->validator->rule('required', 'prix');
        $this->validator->rule('required', 'kilometrage');
        $this->validator->rule('required', 'mise_en_circulation');
        $this->validator->rule('required', 'energie');
        $this->validator->rule('subset', 'marquesIds', array_keys($marques));
    }
}