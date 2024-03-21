<?php

namespace App\Model;

use DOMDocument;
use SimpleXMLElement;
use stdClass;

class XmlManager
{
    private string $xmlFilePath;

    public function __construct(string $xmlFilePath)
    {
        $this->xmlFilePath = $xmlFilePath;

        if (!file_exists($this->xmlFilePath)) {
            $this->createXmlFile();
        }
    }

    public function createXmlFile(): bool
    {
        $xml = new SimpleXMLElement('<?xml version="1.0"?><petshop></petshop>');
        return $this->saveXml($xml);
    }

    public function saveXml($xml): bool
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $dom->loadXML($xml->asXML());
        return $dom->save($this->xmlFilePath) !== false;
    }

    public function loadXml(): SimpleXMLElement
    {
        return simplexml_load_file($this->xmlFilePath);
    }

    public function unifyStructure(SimpleXMLElement $xml): stdClass
    {
        $jsonString = json_encode($xml);
        $newXml = json_decode($jsonString);

        if (!is_array($newXml->pet)) {
            $newXml->pet = [$newXml->pet];
        }

        return $newXml;
    }
}