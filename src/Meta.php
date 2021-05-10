<?php

/**
 * JSON Meta Object
 *
 * @category  Server Software
 * @package   apsxj/json
 * @author    Roderic Linguri <apsxj@mail.com>
 * @copyright 2021 Roderic Linguri
 * @license   https://github.com/apsxj/json/blob/main/LICENSE MIT
 * @link      https://github.com/apsxj/json
 * @version   0.1.1
 * @since     0.1.0
 */

namespace apsxj\json;

class Meta
{
  /** @property string describes the data object */
  private $object;

  /** @property mixed additional keys requested for meta response */
  private $addedKeys;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->object = 'object';
    $this->addedKeys = array();
  }

  /**
   * Sets the object property
   *
   * @param string $object
   * 
   * @return void
   */
  public function setObject($object)
  {
    $this->object = $object;
  }

  /**
   * Set a property
   *
   * @param string $key
   * @param string $value
   * 
   * @return void
   */
  public function setKey($key, $value)
  {
    $this->addedKeys[$key] = $value;
  }

  /**
   * Dictionary representation of the meta object
   *
   * @param array $data
   * 
   * @return void
   */
  public function assoc($data)
  {
    $meta = array(
      'timestamp' => intval(date('U')),
      'object' => $this->object,
      'count' => intval(count($data))
    );

    foreach ($this->addedKeys as $k => $v) {
      $meta[$k] = $v;
    }

    return $meta;
  }

  /**
   * The meta object to return if there is no data
   *
   * @return mixed
   */
  public function noData()
  {

    $meta = array(
      'timestamp' => intval(date('U')),
      'success' => boolval(true),
      'object' => 'none',
      'count' => intval(0)
    );

    return $meta;
  }

  /**
   * If objects were created, meta contains the count
   *
   * @return integer
   */
  public function getCreatedCount()
  {
    $created = 0;
    if (isset($this->addedKeys['created'])) {
      $created = $this->addedKeys['created'];
    }
    return $created;
  }
}
