<?php

namespace Drupal\farm_nrcs;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Soil Data Access.
 */
class SoilDataAccess implements SoilDataAccessInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Class constructor.
   *
   * @param \GuzzleHttp\Client $http_client
   *   The HTTP client.
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function mapunitWktQuery(string $wkt): array {

    // Define the columns to query.
    $columns = [
      'muname',
      'musym',
      'nationalmusym',
    ];

    // Assemble the SQL query that will be sent to the Soil Data Access API.
    $parts = [];
    foreach ($columns as $col) {
      $parts[] =  'MU.' . $col;
    }
    $query = "SELECT " . implode(', ', $parts) . " FROM SDA_Get_Mukey_from_intersection_with_WktWgs84('" . $wkt . "') K LEFT JOIN mapunit MU ON K.mukey = MU.mukey";

    // Run the query.
    $response = $this->tabularRestQuery($query);

    // Create an associative array of data.
    $data = [];
    if (!empty($response['Table'])) {
      foreach ($response['Table'] as $i => $result) {
        foreach ($result as $j => $value) {
          $data[$i][$columns[$j]] = $value;
        }
      }
    }

    // Return the data.
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function tabularRestQuery(string $query): array {

    // Send request to the Soil Data Access API.
    try {
      $url = 'https://sdmdataaccess.sc.egov.usda.gov/tabular/post.rest';
      $options = [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
        ],
        'json' => [
          'query' => $query,
          'format' => 'json',
        ],
      ];
      /** @var \GuzzleHttp\Psr7\Response $response */
      $response = $this->httpClient->request('POST', $url, $options);
    }
    catch (RequestException $e) {
      watchdog_exception('farmier', $e);
    }

    // Decode the JSON response and return the result.
    return Json::decode($response->getBody()->getContents());
  }
}
