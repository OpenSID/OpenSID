<?php

namespace NotificationChannels\Telegram\Enums;

/**
 * Enum FileType
 *
 * Represents the different file types supported by Telegram Bot API.
 */
enum FileType: string
{
    case Document = 'document';
    case Photo = 'photo';
    case Audio = 'audio';
    case Video = 'video';
    case Animation = 'animation';
    case Voice = 'voice';
    case VideoNote = 'video_note';
    case Sticker = 'sticker';

    /**
     * Get the mime type associated with this file type.
     */
    public function getMimeType(): string
    {
        return match ($this) {
            self::Document => 'application/octet-stream',
            self::Photo => 'image/jpeg',
            self::Audio => 'audio/mp3',
            self::Video => 'video/mp4',
            self::Animation => 'video/mp4',
            self::Voice => 'audio/ogg',
            self::VideoNote => 'video/mp4',
            self::Sticker => 'image/webp',
        };
    }

    /**
     * Get allowed file extensions for this type.
     *
     * @return array<string>
     */
    public function getAllowedExtensions(): array
    {
        return match ($this) {
            self::Document => [], // Any extension allowed
            self::Photo => ['jpg', 'jpeg', 'png', 'webp'],
            self::Audio => ['mp3', 'ogg', 'm4a'],
            self::Video => ['mp4', 'avi', 'mov', 'mkv'],
            self::Animation => ['gif', 'mp4'],
            self::Voice => ['ogg', 'mp3'],
            self::VideoNote => ['mp4'],
            self::Sticker => ['png', 'webp', 'tgs', 'webm'],
        };
    }

    /**
     * Check if a file extension is allowed for this type.
     */
    public function isExtensionAllowed(string $extension): bool
    {
        $extensions = $this->getAllowedExtensions();

        // Document allows all extensions
        if ($this === self::Document || empty($extensions)) {
            return true;
        }

        return in_array(strtolower($extension), $extensions, true);
    }

    /**
     * Get all file types as an array.
     *
     * @return array<string, string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
