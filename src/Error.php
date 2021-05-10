<?php

/**
 * JSON Error Object
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

class Error
{
  /** @property integer status code */
  private $status;

  /** @property string error title */
  private $title;

  /** @property string error detail */
  private $detail;

  /**
   * Constructor
   *
   * @param integer $status
   * @param string $title
   * @param string $detail
   */
  public function __construct($status, $title, $detail)
  {
    $this->status = $status;
    $this->title = $title;
    $this->detail = $detail;
  }

  /**
   * JSON Encodable Output
   *
   * @return mixed
   */
  public function assoc()
  {
    return array(
      'status' => intval($this->status),
      'title' => $this->title,
      'detail' => $this->detail
    );
  }
}
