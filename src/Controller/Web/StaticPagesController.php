<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController
{
    /**
     * @Route({
     *     "/imprint",
     *     "de": "/impressum"
     * }, name="imprint", methods={"GET"})
     *
     * @Template("web/static/imprint.html.twig")
     */
    public function imprint(): void
    {
    }

    /**
     * @Route({
     *     "/privacy",
     *     "de": "/datenschutz"
     * }, name="privacy", methods={"GET"})
     *
     * @Template("web/static/privacy.html.twig")
     */
    public function privacy(): void
    {
    }
    /**
     * @Route({
     *     "/terms-and-conditions",
     *     "de": "/agb"
     * }, name="termsandconditions", methods={"GET"})
     *
     * @Template("web/static/terms-and-conditions.html.twig")
     */
    public function termsandconditions(): void
    {
    }
}
