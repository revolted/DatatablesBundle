<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Data;

use Sg\DatatablesBundle\Datatable\View\DatatableViewInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DatatableDataManager
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
class DatatableDataManager
{
    /**
     * The request.
     *
     * @var Request
     */
    private $request;

    /**
     * The serializer service.
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * Configuration settings.
     *
     * @var array
     */
    private $configs;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Request $request
     * @param Serializer   $serializer
     * @param array        $configs
     */
    public function __construct(Request $request, Serializer $serializer, array $configs)
    {
        $this->request = $request;
        $this->serializer = $serializer;
        $this->configs = $configs;
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Get query.
     *
     * @param DatatableViewInterface $datatableView
     *
     * @return DatatableQuery
     */
    public function getQueryFrom(DatatableViewInterface $datatableView)
    {
        $type = $datatableView->getAjax()->getType();
        $parameterBag = null;

        if ('GET' === strtoupper($type)) {
            $parameterBag = $this->request->query;
        }

        if ('POST' === strtoupper($type)) {
            $parameterBag = $this->request->request;
        }

        $params = $parameterBag->all();
        $query = new DatatableQuery($this->serializer, $params, $datatableView, $this->configs);

        return $query;
    }
}
