<?php

/**
 * @file
 * Contains \Drupal\jquery_ui_multiselect_widget\Plugin\Field\FieldWidget\JqueryUIMultiSelectWidget.
 */

namespace Drupal\jquery_ui_multiselect_widget\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'boolean_checkbox' widget.
 *
 * @FieldWidget(
 *   id = "jquery_ui_multiselect_widget",
 *   label = @Translation("Jquery UI Multi Select"),
 *   field_types = {
 *     "entity_reference",
 *     "list_integer",
 *     "list_float",
 *     "list_string"
 *   },
 *   multiple_values = TRUE
 * )
 */
class JqueryUIMultiSelectWidget extends OptionsSelectWidget {

    /**
     * {@inheritdoc}
     */
    public static function defaultSettings() {
        return array(
            'jquery_ui_multiselect_widget_show_header' => FALSE,
            'jquery_ui_multiselect_widget_show_filter' => FALSE,
            'jquery_ui_multiselect_widget_filter_placeholder' => '',
        ) + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $element, FormStateInterface $form_state) {
        $field_name = $this->fieldDefinition->getName();
        $element['jquery_ui_multiselect_widget_show_header'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Show header'),
            '#default_value' => $this->getSetting('jquery_ui_multiselect_widget_show_header'),
        );
        $element['jquery_ui_multiselect_widget_show_filter'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Show filter'),
            '#default_value' => $this->getSetting('jquery_ui_multiselect_widget_show_filter'),
        );
        $element['jquery_ui_multiselect_widget_filter_placeholder'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Filter placeholder text'),
            '#default_value' => $this->getSetting('jquery_ui_multiselect_widget_filter_placeholder'),
            '#states' => [
                'visible' => array(
                    ':input[name="fields[' . $field_name . '][settings_edit_form][settings][jquery_ui_multiselect_widget_show_filter]"]' => array('checked' => TRUE),
                ),
            ],
        );
        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)
    {
        $element['#attached'] = [
            'library' => [
                'jquery_ui_multiselect_widget/jquery_ui_multiselect_widget.js',
                'jquery_ui_multiselect_widget/jquery_ui_multiselect_widget.css'
            ],
            'drupalSettings' => [
                'jquery_ui_multiselect_widget' => [
                    $items->getName() => [
                        'header' => $this->getSetting('jquery_ui_multiselect_widget_show_header'),
                        'filter' => $this->getSetting('jquery_ui_multiselect_widget_show_filter'),
                        'filter_placeholder' => $this->getSetting('jquery_ui_multiselect_widget_filter_placeholder'),
                        'cardinality' => $this->fieldDefinition->getFieldStorageDefinition()->getCardinality(),
                    ],
                ],
            ],
        ];
        return parent::formElement($items, $delta, $element, $form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public static function isApplicable(FieldDefinitionInterface $field_definition) {
        // Applicable only for items having cardinality more than 1.
        return $field_definition->getFieldStorageDefinition()->isMultiple();
    }
}
