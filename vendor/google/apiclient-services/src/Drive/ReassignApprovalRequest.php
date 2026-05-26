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

class ReassignApprovalRequest extends \Google\Collection
{
  protected $collection_key = 'replaceReviewers';
  protected $addReviewersType = AddReviewer::class;
  protected $addReviewersDataType = 'array';
  /**
   * Optional. A message to send to the new reviewers. This message is included
   * in notifications for the action and in the approval activity log.
   *
   * @var string
   */
  public $message;
  protected $replaceReviewersType = ReplaceReviewer::class;
  protected $replaceReviewersDataType = 'array';

  /**
   * Optional. The list of reviewers to add.
   *
   * @param AddReviewer[] $addReviewers
   */
  public function setAddReviewers($addReviewers)
  {
    $this->addReviewers = $addReviewers;
  }
  /**
   * @return AddReviewer[]
   */
  public function getAddReviewers()
  {
    return $this->addReviewers;
  }
  /**
   * Optional. A message to send to the new reviewers. This message is included
   * in notifications for the action and in the approval activity log.
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
   * Optional. The list of reviewer replacements.
   *
   * @param ReplaceReviewer[] $replaceReviewers
   */
  public function setReplaceReviewers($replaceReviewers)
  {
    $this->replaceReviewers = $replaceReviewers;
  }
  /**
   * @return ReplaceReviewer[]
   */
  public function getReplaceReviewers()
  {
    return $this->replaceReviewers;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReassignApprovalRequest::class, 'Google_Service_Drive_ReassignApprovalRequest');
