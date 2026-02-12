<?php

namespace NaggaDIM\LaravelMaxBot\API\Helpers;

class Button
{
    protected ?string $text;
    protected ?string $payload;
    protected ?string $url;
    protected ?bool $quick;

    protected ?string $webApp;
    protected ?int $contactId;

    public function __construct(protected string $type)
    {

    }

    public static function callback(string $text, string $payload): static
    {
        return (new Button('callback'))
            ->setText($text)
            ->setPayload($payload);
    }

    public static function link(string $text, string $url): static
    {
        return (new Button('link'))
            ->setText($text)
            ->setUrl($url);
    }

    public static function requestGeoLocation(string $text, ?bool $quick = null): static
    {
        return (new Button('request_geo_location'))
            ->setText($text)
            ->setQuick($quick);
    }

    public static function requestContact(string $text): static
    {
        return (new Button('request_contact'))
            ->setText($text);
    }

    public static function openApp(string $text, ?string $webApp = null, ?int $contactId = null, ?string $payload = null): static
    {
        return (new Button('open_app'))
            ->setText($text)
            ->setWebApp($webApp)
            ->setContactId($contactId)
            ->setPayload($payload);
    }

    public static function message(string $text): static
    {
        return (new Button('message'))
            ->setText($text);
    }

    public function setText(?string $text): static
    {
        return tap($this, fn() => $this->text = $text);
    }

    public function setPayload(?string $payload): static
    {
        return tap($this, fn() => $this->payload = $payload);
    }

    public function setUrl(?string $url): static
    {
        return tap($this, fn() => $this->url = $url);
    }

    public function setQuick(?bool $quick): static
    {
        return tap($this, fn() => $this->quick = $quick);
    }

    public function setWebApp(?string $webApp): static
    {
        return tap($this, fn() => $this->webApp = $webApp);
    }

    public function setContactId(?int $contactId): static
    {
        return tap($this, fn() => $this->contactId = $contactId);
    }

    public function toJson(): array
    {
        $data = ['type' => $this->type];
        if(!empty($this->text)) { $data['text'] = $this->text; }
        if(!empty($this->payload)) { $data['payload'] = $this->payload; }
        if(!empty($this->url)) { $data['url'] = $this->url; }
        if(!empty($this->quick)) { $data['quick'] = $this->quick; }
        if(!empty($this->webApp)) { $data['web_app'] = $this->webApp; }
        if(!empty($this->contactId)) { $data['contact_id'] = $this->contactId; }
        return $data;
    }
}