<?php

/**
 * @file
 * Definition of Drupal\domain\Tests\DomainFieldUI
 */

namespace Drupal\domain\Tests;
use Drupal\domain\Plugin\Core\Entity\Domain;

/**
 * Tests the domain record field interface.
 */
class DomainFieldUI extends DomainTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('domain', 'field', 'field_ui');

  public static function getInfo() {
    return array(
      'name' => 'Domain field interface',
      'description' => 'Tests the Field UI for domain records.',
      'group' => 'Domain',
    );
  }

  /**
   * Create, edit and delete a field via the user interface.
   */
  function testDomainFieldUI() {
    $this->admin_user = $this->drupalCreateUser(array('administer domains', 'administer domain fields', 'administer domain display'));
    $this->drupalLogin($this->admin_user);

    // Visit the domain field administration page.
    $this->drupalGet('admin/structure/domain/fields');
    $this->assertResponse(200);

    // Check for the extra fields.
    $fields = domain_field_extra_fields();
    $items = $fields['domain']['domain']['form'];
    foreach ($items as $key => $value) {
      $this->assertText($value['label'], format_string('Form field %field found.', array('%field' => $value['label'])));
    }

    // Visit the domain field display administration page.
    $this->drupalGet('admin/structure/domain/display');
    $this->assertResponse(200);

    // Check for the extra fields.
    $items = $fields['domain']['domain']['display'];
    foreach ($items as $key => $value) {
      $this->assertText($value['label'], format_string('Display field %field found.', array('%field' => $value['label'])));
    }

    // Create random field name.
    $label = strtolower($this->randomName(8));

    $field = array(
      'field_name' => 'field_' . $label,
      'type' => 'text',
    );
    field_create_field($field);

    $instance = array(
      'field_name' => 'field_' . $label,
      'entity_type' => 'domain',
      'label' => 'Test field',
      'bundle' => 'domain',
    );
    field_create_instance($instance);

    // Visit the domain field administration page.
    $this->drupalGet('admin/structure/domain/fields');

    // Check the new field.
    $this->assertText('Test field', 'Added a test field instance.');

    // Visit the domain display administration page.
    $this->drupalGet('admin/structure/domain/display');

    // Check the new field.
    $this->assertText('Test field', 'Added a test field display instance.');
  }

}