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

use GroNed\AdminBundle\Excel\OverviewExportValueBinder;
use GroNed\AdminBundle\Excel\SalesExportValueBinder;
use Sg\DatatablesBundle\Datatable\View\DatatableViewInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
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

    /**
     * @var DatatableQuery
     */
    private $query;

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
        $this->request->getSession()->set($datatableView->getName().'Params', $params);
        $query = new DatatableQuery($this->serializer, $params, $datatableView, $this->configs);

        return $query;
    }

    /**
     * @param DatatableViewInterface $dataTableView
     * @return DatatableQuery
     */
    public function getQueryForExport(DatatableViewInterface $dataTableView)
    {
        $params = $this->request->getSession()->get($dataTableView->getName().'Params');

        if ($this->query instanceof DatatableQuery) {
            return $this->query;
        }

        return new DatatableQuery($this->serializer, $params, $dataTableView, $this->configs);
    }

    /**
     * Export selection to excel
     *
     * @param DatatableViewInterface $dataTableView
     * @param $documentName
     * @param $author
     * @param string $exportType
     * @param null $addWhereAllCallback
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function export(
        DatatableViewInterface $dataTableView,
        $documentName,
        $author,
        $exportType = 'Excel5',
        $addWhereAllCallback = null
    ) {
        $data = $this->getExportData($dataTableView, $addWhereAllCallback);

        $document = new \PHPExcel();
        $document
            ->getProperties()
            ->setCreator($author)
            ->setLastModifiedBy($author)
            ->setTitle($documentName)
            ->setSubject($documentName);

        \PHPExcel_Cell::setValueBinder(new OverviewExportValueBinder());

        $sheet = $document->setActiveSheetIndex(0);
        $sheet->fromArray($data);

        // Make header bold
        $first = \PHPExcel_Cell::stringFromColumnIndex(0);
        $last = \PHPExcel_Cell::stringFromColumnIndex(count($data['columns'])-1);
        $headerRange = "{$first}1:{$last}1";
        $sheet->getStyle($headerRange)->getFont()->setBold(true);

        // Auto size columns
        foreach (range('A', $document->getActiveSheet()->getHighestDataColumn()) as $col) {
            $document->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        $documentWriter = \PHPExcel_IOFactory::createWriter($document, $exportType);

        $response = new Response();
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.xls"', $documentName));
        $response->sendHeaders();

        $documentWriter->save('php://output');

        return $response;
    }


    /**
     * Export selection to excel
     *
     * @param DatatableViewInterface $dataTableView
     * @param null $addWhereAllCallback
     * @return array
     */
    private function getExportData(DatatableViewInterface $dataTableView, $addWhereAllCallback = null)
    {
        $columns = array();
        $exportData = array();

        $query = $this->getQueryForExport($dataTableView);
        $query->setLineFormatter($dataTableView->getExportLineFormatter());
        $query->addWhereAll($addWhereAllCallback);
        $data = $query->getData();

        if (isset($data['data'][0])) {

            foreach ($dataTableView->getColumnBuilder()->getColumns() as $column) {
                if ($column->getVisible()) {
                    if (array_key_exists($column->getData(), $data['data'][0])) {
                        $columns['columns'][$column->getData()] = strip_tags($column->getTitle());
                    } elseif ($this->getColumn($data['data'][0], $column->getData()) !== false) {
                        $columns['columns'][$column->getData()] = strip_tags($column->getTitle());
                    }
                }
            }
            foreach ($data['data'] as $key => $item) {
                $columnData = array();
                foreach ($columns['columns'] as $column => $title) {
                    if (strpos($column, '.') === false) {
                        $columnData[$column] = is_string($item[$column]) ? strip_tags($item[$column]) : $item[$column];
                    } else {
                        $columnData[$column] = $this->getColumn($item, $column);
                    }

                }
                unset($data['data'][$key]);
                $exportData[] = $columnData;
            }
        }

        return array_merge($columns, $exportData);
    }

    /**
     * @param $data
     * @param $column
     * @return array|bool
     */
    private function getColumn($data, $column)
    {
        $columns = explode('.', $column);

        $result = $data;
        foreach ($columns as $column) {
            if (is_array($result) && array_key_exists($column, $result)) {
                $result = $result[$column];
                continue;
            } else {
                return false;
            }
        }
        return $result;
    }
}
