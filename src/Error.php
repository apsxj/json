<?php

/**
 * json Error object
 *
 * @category   Utilities
 * @package    json
 * @link       https://apsxj.com
 * @author     Roderic Linguri <apsxj@mail.com>
 * @copyright  2023 APSXJ * All Rights Reserved
 * @license    http://www.apache.org/licenses/ Apache License
 * @version    0.1.1
 * @since      0.1.1
 */

class JSONError
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
