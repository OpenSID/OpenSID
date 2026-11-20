<?php

namespace NotificationChannels\Telegram\Enums;

enum ParseMode: string
{
    case Markdown = 'Markdown';
    case HTML = 'HTML';
    case MarkdownV2 = 'MarkdownV2';
}
