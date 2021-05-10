<?php

/**
 * JSON Response Object
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

class Response
{
  /** @property integer HTTP Status */
  private $status;

  /** @property object Meta */
  private $meta;

  /** @property array of Error objects */
  private $errors;

  /** @property object Links */
  public $links;

  /** @property array of Any type */
  private $data;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->status = 204; // Initialize to 'No Content'
    $this->errors = array();
    $this->meta = new Meta();
    $this->data = array();
    $this->links = new Links();
  }

  /**
   * Append an error to the errors array
   *  
   * @param integer $status
   * @param string $title
   * @param string $detail
   * @return void
   */
  public function appendError($status, $title, $detail)
  {
    $this->status = $status;
    array_push($this->errors, new Error($status, $title, $detail));
  }

  /**
   * Append object to the data array
   *
   * @param mixed $object
   * 
   * @return void
   */
  public function appendData($object)
  {
    if (is_object($object)) {
      if ($type = get_class($object)) {
        $this->meta->setObject(strtolower($type));
      }
    } elseif (is_array($object)) {
      $this->meta->setObject('dictionary');
    } else {
      $this->meta->setObject('primitive');
    }

    array_push($this->data, $object);
    $this->status = 200;
  }

  /**
   * Set entire data array
   *
   * @param array $data
   * 
   * @return void
   */
  public function setData($data)
  {
    if (count($data) > 0) {
      if (is_object($data[0])) {
        if ($type = get_class($data[0])) {
          $this->meta->setObject(strtolower($type));
        }
      } elseif (is_array($data[0])) {
        $this->meta->setObject('dictionary');
      } else {
        $this->meta->setObject('primitive');
      }

      $this->status = 200;
    } else {
      $this->meta->setObject('null');
    }

    $this->data = $data;
  }

  /**
   * Override or initialize the object type in the meta object
   *
   * @param string $type
   * 
   * @return void
   */
  public function setObjectType($type)
  {
    $this->meta->setObject($type);
  }

  /**
   * Update the meta object change or add value for key
   *
   * @param string $key
   * @param string $value
   * @param string SQL::Type
   * 
   * @return void
   */
  public function setMetaKey($key, $value)
  {
    $this->meta->setKey($key, $value);
  }

  /**
   * Renders the response to the client
   *
   * @return mixed
   */
  public function render()
  {
    $assoc = array();

    if (count($this->errors) > 0) {
      // If we have errors, only set the errors array
      $errors = array();
      foreach ($this->errors as $error) {
        array_push($errors, $error->assoc());
      }
      $assoc['errors'] = $errors;
    } else {
      // If no errors, always add the links object
      $assoc['links'] = $this->links->assoc();

      if (count($this->data) > 0) {
        $assoc['meta'] = $this->meta->assoc($this->data);
        $assoc['data'] = $this->data;
      } else {
        // If no data return a success->true meta object
        $assoc['meta'] = $this->meta->noData();
      }
    }

    if ($this->meta->getCreatedCount() > 0) {
      $this->status = 201;
    }

    header('Content-Type: application/json', true, $this->status);
    echo json_encode($assoc);
  }
}
