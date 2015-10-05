<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

/**
 * Interface ColumnInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnInterface
{
    /**
     * Column Types
     */
    const ACTION = 'action';
    const ARRAY_COLUMN = 'array';
    const BOOLEAN = 'boolean';
    const COLUMN = 'column';
    const DATETIME = 'datetime';
    const MULTI_SELECT = 'multiselect';
    const TIME_AGO = 'timeago';
    const VIRTUAL = 'virtual';

    /**
     * Set data.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setData($data);

    /**
     * Set dql.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setDql($data);

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias();
}
