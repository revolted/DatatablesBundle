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
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class BooleanColumn extends AbstractColumn
{
    /**
     * The icon for a value that is true.
     *
     * @var string
     */
    protected $trueIcon;

    /**
     * The icon for a value that is false.
     *
     * @var string
     */
    protected $falseIcon;

    /**
     * The label for a value that is true.
     *
     * @var string
     */
    protected $trueLabel;

    /**
     * The label for a value that is false.
     *
     * @var string
     */
    protected $falseLabel;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:boolean.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'boolean';
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
                'render' => 'render_boolean',
                'searchable' => true,
                'title' => '',
                'type' => '',
                'visible' => true,
                'width' => '',
                'search_type' => 'like',
                'filter_type' => 'select',
                'filter_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No'),
                'filter_property' => '',
                'filter_search_column' => '',
                'true_icon' => '',
                'false_icon' => '',
                'true_label' => '',
                'false_label' => '',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'class' => 'string',
                'padding' => 'string',
                'name' => 'string',
                'orderable' => 'bool',
                'render' => 'string',
                'searchable' => 'bool',
                'title' => 'string',
                'type' => 'string',
                'visible' => 'bool',
                'width' => 'string',
                'search_type' => 'string',
                'filter_type' => 'string',
                'filter_options' => 'array',
                'filter_property' => 'string',
                'filter_search_column' => 'string',
                'true_icon' => 'string',
                'false_icon' => 'string',
                'true_label' => 'string',
                'false_label' => 'string',
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
     * Set false icon.
     *
     * @param string $falseIcon
     *
     * @return $this
     */
    public function setFalseIcon($falseIcon)
    {
        $this->falseIcon = $falseIcon;

        return $this;
    }

    /**
     * Get false icon.
     *
     * @return string
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * Set true icon.
     *
     * @param string $trueIcon
     *
     * @return $this
     */
    public function setTrueIcon($trueIcon)
    {
        $this->trueIcon = $trueIcon;

        return $this;
    }

    /**
     * Get true icon.
     *
     * @return string
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * Set false label.
     *
     * @param string $falseLabel
     *
     * @return $this
     */
    public function setFalseLabel($falseLabel)
    {
        $this->falseLabel = $falseLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return string
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * Set true label.
     *
     * @param string $trueLabel
     *
     * @return $this
     */
    public function setTrueLabel($trueLabel)
    {
        $this->trueLabel = $trueLabel;

        return $this;
    }

    /**
     * Get false label.
     *
     * @return string
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }
}
