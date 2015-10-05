<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class DateTimeColumn extends TimeagoColumn
{
    /**
     * DateTime format string.
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;

    /**
     *  DateTime format string for JS rendering
     *
     * @var string
     */
    protected $renderDateFormat;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        if (!isset($options['name'])) {
            $options['name'] = 'daterange_'.$this->getData();
        }


        $this->options = $resolver->resolve($options);

        $this->setOptions($this->options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:datetime.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'datetime';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'class' => 'daterange',
            'render' => 'render_datetime',
            'date_format' => 'DD-MM-YYYY',
            'render_date_format' => 'DD-MM',
        ));

        $resolver->addAllowedTypes(array(
            'date_format' => 'string',
            'render_date_format' => array('string', 'null'),
        ));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set date format.
     *
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || !is_string($dateFormat)) {
            throw new \InvalidArgumentException('setDateFormat(): Expecting non-empty string.');
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get date format.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @return string
     */
    public function getRenderDateFormat()
    {
        return $this->renderDateFormat;
    }

    /**
     * @param string $renderDateFormat
     */
    public function setRenderDateFormat($renderDateFormat)
    {
        $this->renderDateFormat = $renderDateFormat;
    }
}
