<?php

namespace Drupal\Tests\farm_nrcs\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for the NRCS Soil Data Access service.
 *
 * @group farm
 */
class SoilDataAccessTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_nrcs',
  ];

  /**
   * Test Soil Data Access service.
   */
  public function testSoilDataAccessService() {

    // Test getting data for a specific point.
    $expected = [
      [
        'muname' => 'Paxton and Montauk fine sandy loams, 3 to 8 percent slopes',
        'musym' => '84B',
        'nationalmusym' => '2t2qn',
      ],
    ];
    $data = \Drupal::service('nrcs.soil_data_access')->mapunitWktQuery('POINT(-71.82165840851076 41.87079806075624)');
    $this->assertNotEmpty($data);
    $this->assertEquals($expected, $data);

    // Test getting data for a polygon.
    $expected = [
      [
        'muname' => 'Canton and Charlton fine sandy loams, 3 to 15 percent slopes, extremely stony',
        'musym' => '62C',
        'nationalmusym' => '2wks7',
      ],
      [
        'muname' => 'Paxton and Montauk fine sandy loams, 3 to 8 percent slopes',
        'musym' => '84B',
        'nationalmusym' => '2t2qn',
      ]
    ];
    $data = \Drupal::service('nrcs.soil_data_access')->mapunitWktQuery('POLYGON((-71.81960962792417 41.8692198884186,-71.81904972300649 41.869218787257466,-71.81903355459855 41.868068944318594,-71.81954571476324 41.86796457836036,-71.81960962792417 41.8692198884186))');
    $this->assertNotEmpty($data);
    $this->assertEquals($expected, $data);
  }

}
