<?php


namespace App\Notification;


use App\Entity\Contact;
use Twig\Environment;

class NotificationContact
{
    /**
     * @var Environment
     */
    private $environnement;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Environment $environment, \Swift_Mailer $swift_Mailer)
    {
        $this->mailer= $swift_Mailer;
        $this->environnement= $environment;
    }

    public function  notify(Contact $contact)
    {
        $message = (new \Swift_Message('Agence : '.$contact->getProperty()->getTitle()))
            ->setFrom('noreply@agence.fr')
            ->setTo('contact@agence.fr')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->environnement->render('emails/contact.html.twig',
                [
                    'contact'=>$contact
                ]),'text/html');
        $this->mailer->send($message);
    }
}
