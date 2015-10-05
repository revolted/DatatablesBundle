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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class TimeagoColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class TimeagoColumn extends AbstractColumn
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new \InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:timeago.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'timeago';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'class' => '',
                'padding' => '',
                'name' => '',
                'orderable' => true,
                'render' => 'render_timeago',
                'searchable' => true,
                'placeholder' => 'Selecteer...',
                'title' => '',
                'filter_class' => 'daterange',
                'type' => '',
                'visible' => true,
                'width' => '',
                'search_type' => 'like',
                'filter_type' => 'text',
                'filter_options' => array(),
                'filter_property' => '',
                'filter_search_column' => '',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'class' => 'string',
                'padding' => 'string',
                'name' => 'string',
                'placeholder' => array('string', 'null'),
                'title' => 'string',
                'filter_class' => array('string', 'null'),
                'type' => 'string',
                'visible' => 'bool',
                'width' => 'string',
                'render' => "string",
                'orderable' => 'bool',
                'searchable' => 'bool',
                'search_type' => 'string',
                'filter_type' => 'string',
                'filter_options' => 'array',
                'filter_property' => 'string',
                'filter_search_column' => 'string',
            )
        );

        $resolver->setAllowedValues(
            array(
                'search_type' => array(
                    'like',
                    'notLike',
                    'eq',
                    'neq',
                    'lt',
                    'lte',
                    'gt',
                    'gte',
                    'in',
                    'notIn',
                    'isNull',
                    'isNotNull',
                ),
            )
        );
        $resolver->setAllowedValues(array('filter_type' => array('text', 'select')));

        return $this;
    }
}
