<?php

/**
 * JSON Links Object
 *
 * @category  Server Software
 * @package   apsxj/json
 * @author    Roderic Linguri <apsxj@mail.com>
 * @copyright 2021 Roderic Linguri
 * @license   https://github.com/apsxj/json/blob/main/LICENSE MIT
 * @link      https://github.com/apsxj/json
 * @version   0.1.0
 * @since     0.1.0
 */

namespace apsxj\json;

class Links
{
  /** @property string the link that was called */
  private $self;

  /** @property string the link for the first page in the response */
  private $first;

  /** @property string the link for the previous page in the response */
  private $prev;

  /** @property string the link for the next page in the response */
  private $next;

  /** @property string for the last page in the response */
  private $last;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->self = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
  }

  /**
   * First Setter
   *
   * @param string uri without the https:://host.domain.tld
   * 
   * @return void
   */
  public function setFirst($route)
  {
    $this->first = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $route;
  }

  /**
   * Prev Setter
   *
   * @param string uri without the https:://host.domain.tld
   * 
   * @return void
   */
  public function setPrev($route)
  {
    $this->prev = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $route;
  }

  /**
   * Next Setter
   *
   * @param string uri without the https:://host.domain.tld
   * 
   * @return void
   */
  public function setNext($route)
  {
    $this->next = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $route;
  }

  /**
   * Last Setter
   *
   * @param string uri without the https:://host.domain.tld
   * 
   * @return void
   */
  public function setLast($route)
  {
    $this->last = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $route;
  }

  /**
   * Returns the links object as an associative array
   *
   * @return mixed
   */
  public function assoc()
  {
    $array = array(
      'self' => $this->self
    );

    if (isset($this->first)) {
      $array['first'] = $this->first;
    }

    if (isset($this->prev)) {
      $array['prev'] = $this->prev;
    }

    if (isset($this->next)) {
      $array['next'] = $this->next;
    }

    if (isset($this->last)) {
      $array['last'] = $this->last;
    }

    return $array;
  }
}
