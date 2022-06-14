<?php

namespace Drupal\farm_nrcs;

/**
 * Soil Data Access interface.
 */
interface SoilDataAccessInterface {

  /**
   * Query for mapunit data by a given WKT geometry.
   *
   * @param string $wkt
   *   The WKT geometry to query for.
   *
   * @return array
   *   Returns an array of mapunit data. Each result is an associative array
   *   with muname, musym, and nationalmusym values.
   */
  public function mapunitWktQuery(string $wkt): array;

  /**
   * Send an SQL query to the Soil Data Access tabular REST API endpoint.
   *
   * @param string $query
   *   The WKT geometry to query for.
   *
   * @return array
   *   Returns data from the API endpoint, decoded from JSON.
   */
  public function tabularRestQuery(string $query): array;

}
