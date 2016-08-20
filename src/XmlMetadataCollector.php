<?php declare(strict_types = 1);
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 20.08.16 at 12:39
 */
namespace samsonframework\containerxml;

use samsonframework\container\AbstractMetadataCollector;
use samsonframework\containercollection\CollectionClassResolver;

/**
 * XML class metadata collector.
 * Class resolves and collects class metadata from XML string.
 *
 * @author Vitaly Egorov <egorov@samsonos.com>
 */
class XmlMetadataCollector extends AbstractMetadataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect($xmlConfig, array $classesMetadata = []) : array
    {
        // Iterate config and resolve single instance
        foreach ($this->xml2array(new \SimpleXMLElement($xmlConfig)) as $key => $classesArrayData) {
            if ($key === CollectionClassResolver::KEY) {
                // If we have only one instance we need to add array
                /** @var array $classesArrayData */
                foreach (!array_key_exists(0, $classesArrayData) ? [$classesArrayData] : $classesArrayData as $classArrayData) {
                    $classMetadata = $this->resolver->resolve($classArrayData);
                    // TODO: This is only work aroud right now
                    $classesMetadata[$classMetadata->className] = $this->resolver->resolve($classArrayData, $classesMetadata[$classMetadata->className] ?? null);
                }
            }
        }

        return $classesMetadata;
    }

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @param \SimpleXMLElement|array $xmlObject
     * @param array                   $out
     *
     * @return array XML converted array
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    protected function xml2array($xmlObject, array $out = []) : array
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }
}
