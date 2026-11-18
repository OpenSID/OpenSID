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

class StartApprovalRequest extends \Google\Collection
{
  /**
   * The behavior is unspecified.
   */
  public const FILE_CONTENT_CHANGE_BEHAVIOR_FILE_CONTENT_CHANGE_BEHAVIOR_UNSPECIFIED = 'FILE_CONTENT_CHANGE_BEHAVIOR_UNSPECIFIED';
  /**
   * Any ReviewerResponse with a Response of APPROVED will be reset to
   * NO_DECISION when the file content changes while the approval has a Status
   * of IN_PROGRESS. When the approval has a Status of APPROVED and
   * RESET_APPROVAL is selected, the file is locked.
   */
  public const FILE_CONTENT_CHANGE_BEHAVIOR_RESET_APPROVAL = 'RESET_APPROVAL';
  /**
   * No action is taken when the file content changes.
   */
  public const FILE_CONTENT_CHANGE_BEHAVIOR_NO_APPROVAL_ACTION = 'NO_APPROVAL_ACTION';
  protected $collection_key = 'reviewerEmails';
  /**
   * Optional. The time that the approval is due.
   *
   * @var string
   */
  public $dueTime;
  /**
   * Optional. The behavior of the approval when the file content changes.
   *
   * @var string
   */
  public $fileContentChangeBehavior;
  /**
   * Optional. Whether to lock the file when starting the approval.
   *
   * @var bool
   */
  public $lockFile;
  /**
   * Optional. A message to send to reviewers when notifying them of the
   * approval request.
   *
   * @var string
   */
  public $message;
  /**
   * Required. The emails of the users who are set to review the approval.
   *
   * @var string[]
   */
  public $reviewerEmails;

  /**
   * Optional. The time that the approval is due.
   *
   * @param string $dueTime
   */
  public function setDueTime($dueTime)
  {
    $this->dueTime = $dueTime;
  }
  /**
   * @return string
   */
  public function getDueTime()
  {
    return $this->dueTime;
  }
  /**
   * Optional. The behavior of the approval when the file content changes.
   *
   * Accepted values: FILE_CONTENT_CHANGE_BEHAVIOR_UNSPECIFIED, RESET_APPROVAL,
   * NO_APPROVAL_ACTION
   *
   * @param self::FILE_CONTENT_CHANGE_BEHAVIOR_* $fileContentChangeBehavior
   */
  public function setFileContentChangeBehavior($fileContentChangeBehavior)
  {
    $this->fileContentChangeBehavior = $fileContentChangeBehavior;
  }
  /**
   * @return self::FILE_CONTENT_CHANGE_BEHAVIOR_*
   */
  public function getFileContentChangeBehavior()
  {
    return $this->fileContentChangeBehavior;
  }
  /**
   * Optional. Whether to lock the file when starting the approval.
   *
   * @param bool $lockFile
   */
  public function setLockFile($lockFile)
  {
    $this->lockFile = $lockFile;
  }
  /**
   * @return bool
   */
  public function getLockFile()
  {
    return $this->lockFile;
  }
  /**
   * Optional. A message to send to reviewers when notifying them of the
   * approval request.
   *
   * @param string $message
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }
  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * Required. The emails of the users who are set to review the approval.
   *
   * @param string[] $reviewerEmails
   */
  public function setReviewerEmails($reviewerEmails)
  {
    $this->reviewerEmails = $reviewerEmails;
  }
  /**
   * @return string[]
   */
  public function getReviewerEmails()
  {
    return $this->reviewerEmails;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StartApprovalRequest::class, 'Google_Service_Drive_StartApprovalRequest');
