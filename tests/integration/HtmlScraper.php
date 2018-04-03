<?php
namespace NonceShield\Tests\Integration;

/**
 * HtmlScraper class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class HtmlScraper
{
    public static function token($html)
    {
      $dom = new \DOMDocument;
      $dom->loadHTML($html);
      $xp = new \DOMXpath($dom);
      $nodes = $xp->query('//input[@name="_nonce_shield_token"]');
      $node = $nodes->item(0);

      return $node->getAttribute('value');
    }
}
