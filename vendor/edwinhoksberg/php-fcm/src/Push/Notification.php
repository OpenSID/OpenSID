<?php

namespace Fcm\Push;

use Fcm\Exception\NotificationException;
use Fcm\Request;

class Notification implements Request
{
    use Push ;
    
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $sound;

    /**
     * @var string
     */
    private $icon;
    
    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $tag;    
    
    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var int
     */
    private $badge;

    /**
     * @var string
     */
    private $click_action;
    
    /**
     * @param string $title
     * @param string $body
     * @param string $recipient
     */
    public function __construct(string $title = '', string $body = '', string $recipient = '', string $sound = '', string $icon = '', string $color = '', int $badge = 0, string $tag = '', string $subtitle = '', array $data = [], string $click_action = '')
    {
        $this->title = $title;
        $this->body = $body;
        $this->sound = $sound;
        $this->color = $color;
        $this->icon = $icon;
        $this->badge = $badge;
        $this->tag = $tag;
        $this->subtitle = $subtitle;

        if (!empty($click_action)) {
            $this->click_action = $click_action;
        }
        
        if (!empty($data)) {
            $this->data = $data;
        } 
        
        if (!empty($recipient)) {
            $this->addRecipient($recipient);
        }
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
    
    /**
     * @param string $sound
     *
     * @return $this
     */
    public function setSound(string $sound): self
    {
        $this->sound = $sound;

        return $this;
    } 
    
    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string $color
     *
     * @return $this
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
    
    /**
     * @param string badge
     *
     * @return $this
     */
    public function setBadge(int $badge): self
    {
        $this->badge = $badge;

        return $this;
    }
    
    /**
     * @param string $tag
     *
     * @return $this
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @param string $subtitle
     *
     * @return $this
     */
    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @param string $click_action
     *
     * @return $this
     */
    public function setClickAction(string $click_action): self {
        $this->click_action = $click_action;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBody(): array
    {
        if (empty($this->recipients) && empty($this->topics)) {
            throw new NotificationException('Must minimaly specify a single recipient or topic.');
        }

        if (!empty($this->recipients) && !empty($this->topics)) {
            throw new NotificationException('Must either specify a recipient or topic, not more then one.');
        }
        /*
        if (empty($this->data)) {
            throw new NotificationException('Data should not be empty for a Data Notification.');
        }
        */
        $request = [];

        if (!empty($this->recipients)) {
            if (\count($this->recipients) === 1) {
                $request['to'] = current($this->recipients);
            } else {
                $request['registration_ids'] = $this->recipients;
            }
        }

        if (!empty($this->topics)) {
            $request['condition'] = array_reduce($this->topics, function ($carry, string $topic) {
                $topicSyntax = "'%s' in topics";

                if (end($this->topics) === $topic) {
                    return $carry .= sprintf($topicSyntax, $topic);
                }

                return $carry .= sprintf($topicSyntax, $topic) . '||';
            });
        }

        $request['notification']['title'] = $this->title;
        $request['notification']['body'] = $this->body;
        $request['notification']['sound'] = $this->sound;
        $request['notification']['icon'] = $this->icon;
        $request['notification']['color'] = $this->color;
        $request['notification']['tag'] = $this->tag;
        $request['notification']['subtitle'] = $this->subtitle;
        if ($this->badge>0) {
            $request['notification']['badge'] = $this->badge;
        }

        if (!empty($this->click_action)) {
            $request['notification']['click_action'] = $this->click_action;
        }
        
        if (!empty($this->data)) {
            $request['data'] = $this->data;
        }
        return $request; 
    }
}
