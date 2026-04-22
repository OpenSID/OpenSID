<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Drive;

class GenerateCseTokenResponse extends \Google\Model
{
  /**
   * The current Key ACL Service (KACLS) ID associated with the JWT.
   *
   * @var string
   */
  public $currentKaclsId;
  /**
   * Name of the KACLs that the returned KACLs ID points to.
   *
   * @var string
   */
  public $currentKaclsName;
  /**
   * The fileId for which the JWT was generated.
   *
   * @var string
   */
  public $fileId;
  /**
   * The signed JSON Web Token (JWT) for the file.
   *
   * @var string
   */
  public $jwt;
  /**
   * Output only. Identifies what kind of resource this is. Value: the fixed
   * string `"drive#generateCseTokenResponse"`.
   *
   * @var string
   */
  public $kind;

  /**
   * The current Key ACL Service (KACLS) ID associated with the JWT.
   *
   * @param string $currentKaclsId
   */
  public function setCurrentKaclsId($currentKaclsId)
  {
    $this->currentKaclsId = $currentKaclsId;
  }
  /**
   * @return string
   */
  public function getCurrentKaclsId()
  {
    return $this->currentKaclsId;
  }
  /**
   * Name of the KACLs that the returned KACLs ID points to.
   *
   * @param string $currentKaclsName
   */
  public function setCurrentKaclsName($currentKaclsName)
  {
    $this->currentKaclsName = $currentKaclsName;
  }
  /**
   * @return string
   */
  public function getCurrentKaclsName()
  {
    return $this->currentKaclsName;
  }
  /**
   * The fileId for which the JWT was generated.
   *
   * @param string $fileId
   */
  public function setFileId($fileId)
  {
    $this->fileId = $fileId;
  }
  /**
   * @return string
   */
  public function getFileId()
  {
    return $this->fileId;
  }
  /**
   * The signed JSON Web Token (JWT) for the file.
   *
   * @param string $jwt
   */
  public function setJwt($jwt)
  {
    $this->jwt = $jwt;
  }
  /**
   * @return string
   */
  public function getJwt()
  {
    return $this->jwt;
  }
  /**
   * Output only. Identifies what kind of resource this is. Value: the fixed
   * string `"drive#generateCseTokenResponse"`.
   *
   * @param string $kind
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GenerateCseTokenResponse::class, 'Google_Service_Drive_GenerateCseTokenResponse');
