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

class DecryptionMetadata extends \Google\Model
{
  /**
   * Chunk size used if content was encrypted with the AES 256 GCM Cipher.
   * Possible values are: - default - small
   *
   * @var string
   */
  public $aes256GcmChunkSize;
  /**
   * The URL-safe Base64 encoded HMAC-SHA256 digest of the resource metadata
   * with its DEK (Data Encryption Key); see
   * https://developers.google.com/workspace/cse/reference
   *
   * @var string
   */
  public $encryptionResourceKeyHash;
  /**
   * The signed JSON Web Token (JWT) which can be used to authorize the
   * requesting user with the Key ACL Service (KACLS). The JWT asserts that the
   * requesting user has at least read permissions on the file.
   *
   * @var string
   */
  public $jwt;
  /**
   * The ID of the KACLS (Key ACL Service) used to encrypt the file.
   *
   * @var string
   */
  public $kaclsId;
  /**
   * The name of the KACLS (Key ACL Service) used to encrypt the file.
   *
   * @var string
   */
  public $kaclsName;
  /**
   * Key format for the unwrapped key. Must be `tinkAesGcmKey`.
   *
   * @var string
   */
  public $keyFormat;
  /**
   * The URL-safe Base64 encoded wrapped key used to encrypt the contents of the
   * file.
   *
   * @var string
   */
  public $wrappedKey;

  /**
   * Chunk size used if content was encrypted with the AES 256 GCM Cipher.
   * Possible values are: - default - small
   *
   * @param string $aes256GcmChunkSize
   */
  public function setAes256GcmChunkSize($aes256GcmChunkSize)
  {
    $this->aes256GcmChunkSize = $aes256GcmChunkSize;
  }
  /**
   * @return string
   */
  public function getAes256GcmChunkSize()
  {
    return $this->aes256GcmChunkSize;
  }
  /**
   * The URL-safe Base64 encoded HMAC-SHA256 digest of the resource metadata
   * with its DEK (Data Encryption Key); see
   * https://developers.google.com/workspace/cse/reference
   *
   * @param string $encryptionResourceKeyHash
   */
  public function setEncryptionResourceKeyHash($encryptionResourceKeyHash)
  {
    $this->encryptionResourceKeyHash = $encryptionResourceKeyHash;
  }
  /**
   * @return string
   */
  public function getEncryptionResourceKeyHash()
  {
    return $this->encryptionResourceKeyHash;
  }
  /**
   * The signed JSON Web Token (JWT) which can be used to authorize the
   * requesting user with the Key ACL Service (KACLS). The JWT asserts that the
   * requesting user has at least read permissions on the file.
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
   * The ID of the KACLS (Key ACL Service) used to encrypt the file.
   *
   * @param string $kaclsId
   */
  public function setKaclsId($kaclsId)
  {
    $this->kaclsId = $kaclsId;
  }
  /**
   * @return string
   */
  public function getKaclsId()
  {
    return $this->kaclsId;
  }
  /**
   * The name of the KACLS (Key ACL Service) used to encrypt the file.
   *
   * @param string $kaclsName
   */
  public function setKaclsName($kaclsName)
  {
    $this->kaclsName = $kaclsName;
  }
  /**
   * @return string
   */
  public function getKaclsName()
  {
    return $this->kaclsName;
  }
  /**
   * Key format for the unwrapped key. Must be `tinkAesGcmKey`.
   *
   * @param string $keyFormat
   */
  public function setKeyFormat($keyFormat)
  {
    $this->keyFormat = $keyFormat;
  }
  /**
   * @return string
   */
  public function getKeyFormat()
  {
    return $this->keyFormat;
  }
  /**
   * The URL-safe Base64 encoded wrapped key used to encrypt the contents of the
   * file.
   *
   * @param string $wrappedKey
   */
  public function setWrappedKey($wrappedKey)
  {
    $this->wrappedKey = $wrappedKey;
  }
  /**
   * @return string
   */
  public function getWrappedKey()
  {
    return $this->wrappedKey;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DecryptionMetadata::class, 'Google_Service_Drive_DecryptionMetadata');
