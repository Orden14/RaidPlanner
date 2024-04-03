<?php

namespace App\Util\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

final readonly class FormFlashHelper
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    /**
     * @param FormErrorIterator<FormError|FormErrorIterator<FormError>> $formErrors
     */
    public function showFormErrorsAsFlash(FormErrorIterator $formErrors): void
    {
        if ($formErrors->count() > 0) {
            /** @var Session $session */
            $session = $this->requestStack->getSession();

            foreach ($formErrors->current() as $formError) {
                $session->getFlashBag()->add('danger', $formError->getMessage());
            }
        }
    }
}
