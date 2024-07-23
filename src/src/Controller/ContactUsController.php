<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

// IMHERE: fix this
/**
 * The controller to send a feedback form by an email.
 */
#[Route('/api/contact-us', name: 'app_contact_us', methods: 'POST', format: 'json')]
class ContactUsController extends AbstractController
{
    public function __construct(
        #[Autowire('%feedbackRecipient%')]
        private readonly string $feedbackRecipient,
        #[Autowire('%emailFrom%')]
        private readonly string $emailFrom,
        private readonly MailerInterface $mailer,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $csrfToken = $request->get('_token');
        $address = $request->get('email');
        $message = $request->get('message');
        $telegram = $request->get('telegram', '');

        if (!$csrfToken || !$address || !$message) {
            throw new BadRequestHttpException('The form is not filled correctly');
        }

        if (!$this->isCsrfTokenValid('contact-form', $csrfToken)) {
            throw new BadRequestHttpException('The csrf token is incorrect');
        }

        $email = (new TemplatedEmail())
            ->from($this->emailFrom)
            ->to($this->feedbackRecipient)
            ->subject('Contact form sent')
            ->htmlTemplate('site/emails/contact.html.twig')
            ->context([
                'address' => $address,
                'message' => $message,
                'telegram' => $telegram,
            ]);
        $this->mailer->send($email);

        return new Response();
    }
}
