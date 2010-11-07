<?php

/*
 * Copyright 2010 Francisco Alvarez Alonso <sormes@gmail.com>
 *
 * This file is part of sfMondongoFormExtraPlugin.
 *
 * sfMondongoFormExtraPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sfMondongoFormExtraPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with sfMondongoPlugin. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * sfWidgetFormMondongoJQueryAutocompleter.
 *
 * Based on sfWidgetFormDoctrineJQueryAutocompleter.
 *
 * @package sfMondongoFormExtraPlugin
 * @author  Francisco Alvarez Alonso <sormes@gmail.com>
 */
class sfWidgetFormMondongoJQueryAutocompleter extends sfWidgetFormJQueryAutocompleter
{
  /**
   * @see sfWidget
   */
  public function __construct($options = array(), $attributes = array())
  {
    $options['value_callback'] = array($this, 'toString');

    parent::__construct($options, $attributes);
  }

  /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormJQueryAutocompleter
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('collection');
    $this->addOption('method', '__toString');

    parent::configure($options, $attributes);
  }

  /**
   * Returns the text representation of a foreign key.
   *
   * @param string $value The primary key
   */
  protected function toString($value)
  {
    $class = $this->getOption('collection');

    $mondongo = sfContext::getInstance()->get('mondongo');

    $object = $mondongo->getRepository($class)->findOneByMongoId(new MongoId($value));

       
    $method = $this->getOption('method');

    if (!method_exists($this->getOption('collection'), $method))
    {
      throw new RuntimeException(sprintf('Class "%s" must implement a "%s" method to be rendered in a "%s" widget', $this->getOption('collection'), $method, __CLASS__));
    }

    return !is_null($object) ? $object->$method() : '';
  }
}
