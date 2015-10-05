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
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractColumn
{
    /**
     * Default content.
     *
     * @var string
     */
    protected $default;

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
        return 'SgDatatablesBundle:Column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'column';
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
                'render' => null,
                'searchable' => false,
                'title' => '',
                'filter_class' => null,
                'type' => '',
                'visible' => true,
                'width' => '',
                'placeholder' => 'Selecteer...',
                'search_type' => 'like',
                'filter_type' => 'text',
                'filter_options' => array(),
                'filter_property' => '',
                'filter_search_column' => '',
                'default' => '',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'class' => 'string',
                'padding' => 'string',
                'name' => 'string',
                'orderable' => 'bool',
                'render' => array('string', 'null'),
                'searchable' => 'bool',
                'title' => 'string',
                'filter_class' => array('string', 'null'),
                'type' => 'string',
                'visible' => 'bool',
                'width' => 'string',
                'placeholder' => array('string', 'null'),
                'search_type' => 'string',
                'filter_type' => 'string',
                'filter_options' => array('array', 'closure') ,
                'filter_property' => 'string',
                'filter_search_column' => 'string',
                'default' => 'string',
            )
        );

        $resolver->setAllowedValues(
            array(
                'search_type' =>
                    array(
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

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     *
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
