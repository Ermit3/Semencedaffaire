<?php

namespace App\Notification;

use App\Entity\CommandeClient;
use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Entity\Renewpassword;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\Routing\Annotation\Route;

class ContactNotification
{

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $render;

    /**
     * @var $bus
     */
    private $bus;

    public function __construct(MailerInterface $mailer, Environment $render, MessageBusInterface $bus = null)
    {

        $this->mailer = $mailer;
        $this->render = $render;
        $this->bus = $bus;
    }

    /**
     * Envoi un mail prevenant du client
     *
     * @Route("/email, name="mail.contact")
     *
     * @param Contact $contact
     *
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    public function notify(Contact $contact){
        $message = (new Email())
            ->from($contact->getMail())
            ->to('stephanebekale@gmail.com')
            ->cc('stephanebekale@yahoo.fr')
            ->subject('E.Service vient de recevoir un Mail de : ' . $contact->getNom() .' ' .$contact->getPrenom())
            ->ReplyTo('stephanebekale@yahoo.fr')
            ->text('NouveauTest Gratos')
            ->html($this->render->render('/emails/contact.html.twig',[
                'contact' => $contact
            ]));
        $this->mailer->send($message);
    }

    /**
     * @Route("/NotiNewsletter",name="notiNewsletter")
     * @param Newsletter $newsletter
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */

    public function notifyNewsletter(Newsletter $newsletter){
        $message = (new Email())
            ->from($newsletter->getMail())
            ->to('stephanebekale@gmail.com')
            ->cc('stephanebekale@yahoo.fr','stephanebekale@akobisoft.com')
            ->subject('E.Service vient de recevoir un Mail de : ' . $newsletter->getNom() .' ' .$newsletter->getPrenom())
            ->ReplyTo('stephanebekale@yahoo.fr')
            ->text('NouveauTest Gratos')
            ->html($this->render->render('/emails/newslet.html.twig',[
                'newslet' => $newsletter
            ]));
        $this->mailer->send($message);
    }

    /**
     * @Route("/notirenewpwd",name="notirenew")
     * @param Renewpassword $renewpass
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */

    public function notifyRenewpwd(Renewpassword $renewpass){
        $message = (new Email())
            ->from('stephanebekale@akobisoft.com')
            ->to($renewpass->getMail())
            ->cc('stephanebekale@gmail.com','stephanebekale@akobisoft.com','stephanebekale@yahoo.fr')
            ->subject('E.Service vient de vous Transferer un mail de renouvellement')
            ->ReplyTo('stephanebekale@yahoo.fr')
            ->text('NouveauTest Gratos')
            ->html($this->render->render('/emails/renouvellement.html.twig',[
                'renewpassword' => $renewpass
            ]));
        $this->mailer->send($message);
    }
}