<?php
namespace Ecommerce\EcommerceBundle\Services;

use http\Env\Response;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GetFacture
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function facture($facture)
    {
        $html = $this->container->get('templating')->render('UtilisateursBundle:Default:layout/facturePDF.html.twig', array('facture' => $facture));

        $html2pdf = new Html2Pdf('P','A4','fr');
        $html2pdf->pdf->SetAuthor('Fromagerie Christophe');
        $html2pdf->pdf->SetTitle('Facture '.$facture->getReference());
        $html2pdf->pdf->SetSubject('Facture Fromagerie Christophe');
        $html2pdf->pdf->SetKeywords('facture,fromagerie christophe');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('Facture.pdf');

        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');

        return $response;
    }

}